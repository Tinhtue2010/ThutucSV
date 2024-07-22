<?php

namespace App\Http\Controllers\CheDoChinhSach;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Hamcrest\Core\HasToString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheDoChinhSachPhongDaoTaoController extends Controller
{
    function index()
    {
        $lop = Lop::get();
        $phieu_2 = Phieu::where('status', 1)
            ->where('key', 'DSMGHP')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        return view('phong_dao_tao.create_ds_che_do_chinh_sach.index', ['lop' => $lop, 'phieu_2' => $phieu_2]);
    }

    function getData(Request $request)
    {
        $query = StopStudy::where('type', 4)
            ->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'lops.hocphi');


        if (isset($request->type_miengiamhp)) {
            $query->where('stop_studies.type_miengiamhp', $request->type_miengiamhp);
        }
        if (isset($request->year)) {
            $query->whereYear('stop_studies.created_at', $request->year);
        }
        if (isset($request->status)) {
            $query->where('status', $request->status);
        }
        $data = $this->queryPagination($request, $query, ['students.full_name', 'students.student_code']);

        return $data;
    }

    function updatePercent(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        $stopStudy->phantramgiam =  $request->phantramgiam;
        $stopStudy->save();
        return;
    }
    function createList(Request $request)
    {
        $query = StopStudy::where('type', 4)
            ->studentActive()
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->where(function ($query) {
                $query->where('stop_studies.status', 1)
                    ->orWhere('stop_studies.status', 2);
            })
            ->select('stop_studies.*')
            ->whereNull('parent_id')
            ->update(['status' => 2]);
        $username = Auth::user()->name;
        $users = User::where('role', 6)->get();
        foreach ($users as $item) {
            $this->notification("Danh sách chế độ chính sách đã được tạo bởi " . $username, null, "GHP", $item->id);
        }
        return redirect()->back();
    }
    function deleteList(Request $request)
    {
        $query = StopStudy::where('type', 4)
            ->studentActive()
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->where(function ($query) {
                $query->where('stop_studies.status', 1)
                    ->orWhere('stop_studies.status', 2);
            })
            ->select('stop_studies.*')
            ->whereNull('parent_id')
            ->update(['status' => 1]);

        return redirect()->back();
    }

    function guiTBSV()
    {
        $query = StopStudy::where('type', 4)
            ->studentActive()
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->whereNull('parent_id')
            ->where('stop_studies.status', 3)
            ->select('stop_studies.*')
            ->get();
        foreach ($query as $stopStudy) {
            $stopStudy->status = 4;
            $stopStudy->save();
            $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
            $this->notification("Danh sách hưởng chế độ chính sách dự kiến", null, "CDCS", $user_id);

            $users = User::where('role','!=', 1)->get();
            foreach ($users as $item) {
                $this->notification("Danh sách hưởng chế độ chính sách dự kiến", null, "CDCS", $item->id);
            }
            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $stopStudy->id;
            $newStopStudy->note = "Gửi thông báo về danh sách";
            $newStopStudy->save();
        }
        return redirect()->back();
    }
    function importFileKTX(Request $request)
    {
        set_time_limit(3600);
        if ($request->hasFile('csv_file')) {
            $data = $this->importCSV($request->file('csv_file'));
            $error_line = 0;

            DB::beginTransaction();
            try {

                $indexHeader = [];
                foreach ($data['header'] as $index => $value) {
                    $indexHeader[$value] = $index;
                }
                foreach ($data['data'] as $index => $item) {
                    $error_line = $index + 2;
                    $student = Student::where('full_name', $item[$indexHeader['ho_ten']])->where('student_code', $item[$indexHeader['ma_sinh_vien']])->first();
                    if (!$student) {
                        return response()->json([
                            'error' => "Không tìm thấy sinh viên " . $item[$indexHeader['ho_ten']] . ", mã sinh viên là: " . $item[$indexHeader['ma_sinh_vien']],
                            'file' => "",
                            'line' => $error_line,
                        ], 500);
                    }
                    $check_phieu = StopStudy::where('student_id', $student->id)->where('type', 4)
                        ->where('status', '>=', 1)
                        ->whereNull('parent_id')
                        ->where('doi_tuong_chinh_sach', 'like', '%1%')
                        ->first();
                    if ($check_phieu) {
                        $che_do_chinh_sach_data = json_decode($check_phieu->che_do_chinh_sach_data, true);
                        $che_do_chinh_sach_data["ktx"] = [
                            "bat_dau" => $item[$indexHeader['ngay_vao']],
                            "so_thang" => $item[$indexHeader['so_thang']],
                            "diachi" => $check_phieu->diachi,
                            "km" => $check_phieu->km
                        ];

                        $check_phieu->che_do_chinh_sach_data = json_encode($che_do_chinh_sach_data);
                        $check_phieu->save();
                    }
                    $check_phieu = StopStudy::where('student_id', $student->id)->where('type', 4)
                        ->where('status', '>=', 1)
                        ->whereNull('parent_id')
                        ->where('doi_tuong_chinh_sach', 'like', '%4%')
                        ->first();
                    if ($check_phieu) {
                        $che_do_chinh_sach_data = json_decode($check_phieu->che_do_chinh_sach_data, true);
                        if (!isset($che_do_chinh_sach_data['diem'])) {
                            $check_phieu->status = -88;
                            $check_phieu->save();
                            continue;
                        }
                        $che_do_chinh_sach_data["ktx"] = [
                            "bat_dau" => $item[$indexHeader['ngay_vao']],
                            "so_thang" => $item[$indexHeader['so_thang']],
                            "diachi" => $check_phieu->diachi,
                            "km" => $check_phieu->km
                        ];

                        $check_phieu->che_do_chinh_sach_data = json_encode($che_do_chinh_sach_data);
                        $check_phieu->save();
                    }
                }
            } catch (\Exception $e) {
                DB::rollback();
                \Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
                return response()->json([
                    'error' => "Có lỗi khi thêm dữ liệu",
                    'file' => $e->getFile(),
                    'line' => isset($error_line) ? $error_line : $e->getLine()
                ], 500);
            }
            DB::commit();

            return true;
        }
        return true;
    }

    function importFileDiemSV(Request $request)
    {
        set_time_limit(3600);
        if ($request->hasFile('csv_file')) {
            $data = $this->importCSV($request->file('csv_file'));

            $error_line = 0;

            DB::beginTransaction();
            try {

                $indexHeader = [];
                foreach ($data['header'] as $index => $value) {
                    $indexHeader[$value] = $index;
                }

                foreach ($data['data'] as $index => $item) {
                    $error_line = $index + 2;
                    $student = Student::where('student_code', $item[$indexHeader['ma_sinh_vien']])
                        ->leftJoin('lops', 'students.lop_id', '=', 'lops.id')
                        ->select('students.*', 'lops.name as lop_name', 'lops.hocphi')
                        ->first();
                    if (!$student) {
                        return response()->json([
                            'error' => "Không tìm thấy sinh viên " . $item[$indexHeader['ho_ten']] . ", mã sinh viên là: " . $item[$indexHeader['ma_sinh_vien']],
                            'file' => "",
                            'line' => $error_line,
                        ], 500);
                    }
                    $check_phieu = StopStudy::where('student_id', $student->id)->where('type', 4)
                        ->where('status', '>=', 1)
                        ->whereNull('parent_id')
                        ->first();

                    if ($check_phieu) {
                        $che_do_chinh_sach_data = json_decode($check_phieu->che_do_chinh_sach_data, true);
                        $che_do_chinh_sach_data["diem"] = [
                            "diemtb" => $item[$indexHeader['diem_trung_binh']],
                            "diemrenluyen" => $item[$indexHeader['diem_ren_luyen']],
                        ];
                        $che_do_chinh_sach_data["mghp"] = [
                            "hoc_phi" => $student->hocphi,
                            "so_thang" => 5,
                            "phan_tram_giam" => 100,
                            "phan_tram_da_giam" => 0,
                            "so_tien_1_thang_giam" => $student->hocphi / 5,
                            "so_tien_1_thang_da_giam" => 0
                        ];
                        if (strpos($check_phieu->doi_tuong_chinh_sach, '1') !== false) {
                            $check_phieu->doi_tuong_chinh_sach = json_encode(["1", "3"]);
                        }
                        if (strpos($check_phieu->doi_tuong_chinh_sach, '4') !== false) {
                            $check_phieu->doi_tuong_chinh_sach = json_encode(["3", "4"]);
                        }
                        $check_phieu->che_do_chinh_sach_data = json_encode($che_do_chinh_sach_data);
                        $check_phieu->save();
                    } else {
                        $don = new StopStudy();
                        $don->student_id = $student->id;
                        $don->type = 4;
                        $don->status = 2;
                        $don->doi_tuong_chinh_sach = json_encode(["3"]);
                        $che_do_chinh_sach_data["diem"] = [
                            "diemtb" => $item[$indexHeader['diem_trung_binh']],
                            "diemrenluyen" => $item[$indexHeader['diem_ren_luyen']],
                        ];
                        $che_do_chinh_sach_data["mghp"] = [
                            "hoc_phi" => $student->hocphi,
                            "so_thang" => 5,
                            "phan_tram_giam" => 100,
                            "phan_tram_da_giam" => 0,
                            "so_tien_1_thang_giam" => $student->hocphi / 5,
                            "so_tien_1_thang_da_giam" => 0
                        ];
                        $don->che_do_chinh_sach_data = json_encode($che_do_chinh_sach_data);
                        $don->save();
                    }
                }
            } catch (\Exception $e) {
                DB::rollback();
                \Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
                return response()->json([
                    'error' => "Có lỗi khi thêm dữ liệu",
                    'file' => $e->getFile(),
                    'line' => isset($error_line) ? $error_line : $e->getLine()
                ], 500);
            }
            DB::commit();

            return true;
        }
        return true;
    }
    function ImportQTMGHP(Request $request)
    {
        $phieu = Phieu::find($request->phieu_id);
        $data = json_decode($phieu->content, true);
        $error_line = 0;
        DB::beginTransaction();
        try {
            foreach ($data[1] as $index => $item) {
                $check_phieu = StopStudy::where('student_id', $item['student_id'])->where('type', 4)
                    ->whereNull('parent_id')
                    ->where('stop_studies.status', '>=', 1)
                    ->where('doi_tuong_chinh_sach', 'like', '%4%')
                    ->first();
                if ($check_phieu) {
                    $check_phieu = $this->setDataQT2($check_phieu, $item, 4);
                    $check_phieu->save();
                    continue;
                }

                $check_phieu = StopStudy::where('student_id', $item['student_id'])->where('type', 4)
                    ->whereNull('parent_id')
                    ->where('stop_studies.status', '>=', 1)
                    ->where('doi_tuong_chinh_sach', 'like', '%1%')
                    ->first();
                if ($check_phieu) {
                    $check_phieu = $this->setDataQT2($check_phieu, $item, 1);
                    $check_phieu->save();
                    continue;
                }

                $check_phieu = StopStudy::where('student_id', $item['student_id'])
                    ->whereNull('parent_id')
                    ->where('type', 4)
                    ->where('status', '>=', 1)
                    ->where(function ($query) {
                        $query->where('doi_tuong_chinh_sach', 'like', '%2%')
                            ->orWhere('doi_tuong_chinh_sach', 'like', '%3%');
                    })
                    ->firstOrNew([
                        'student_id' => $item['student_id'],
                        'type' => 4,
                        'status' => 2
                    ]);
                $check_phieu = $this->setDataQT2($check_phieu, $item, 0);
                $check_phieu->save();
            }
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            return response()->json([
                'error' => "Có lỗi khi thêm dữ liệu",
                'file' => $e->getFile(),
                'line' => isset($error_line) ? $error_line : $e->getLine()
            ], 500);
        }
        DB::commit();
    }
    function cancelImport()
    {
        DB::beginTransaction();
        try {
            DB::table('stop_studies')
                ->where('status', '>=', 1)
                ->whereNull('parent_id')
                ->where('type', 4)
                ->where('doi_tuong_chinh_sach', 'like', '%4%')
                ->update([
                    'che_do_chinh_sach_data' => json_encode([]),
                    "doi_tuong_chinh_sach" => json_encode(["4"])
                ]);

            DB::table('stop_studies')
                ->where('status', '>=', 1)
                ->whereNull('parent_id')
                ->where('type', 4)
                ->where('doi_tuong_chinh_sach', 'like', '%1%')
                ->update([
                    'che_do_chinh_sach_data' => json_encode([]),
                    "doi_tuong_chinh_sach" => json_encode(["1"])
                ]);
            DB::table('stop_studies')
                ->where('status', '>=', 1)
                ->whereNull('parent_id')
                ->where('type', 4)
                ->where(function ($query) {
                    $query->where('doi_tuong_chinh_sach', 'not like', '%1%')
                        ->where('doi_tuong_chinh_sach', 'not like', '%4%');
                })
                ->delete();

            DB::table('stop_studies')
                ->where('status', -88)
                ->whereNull('parent_id')
                ->where('type', 4)
                ->where(function ($query) {
                    $query->where('doi_tuong_chinh_sach', 'like', '%1%')
                        ->orWhere('doi_tuong_chinh_sach', 'like', '%4%');
                })
                ->update([
                    'status' => 2,
                ]);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            return response()->json([
                'error' => "Có lỗi khi sử lý dữ liệu",
                'file' => $e->getFile(),
                'line' => isset($error_line) ? $error_line : $e->getLine()
            ], 500);
        }
        DB::commit();

        return redirect()->back();
    }
    function setDataQT2($data, $item, $type = 1)
    {
        $che_do_chinh_sach_data = json_decode($data->che_do_chinh_sach_data, true);

        $che_do_chinh_sach_data["htta"] = [
            "so_thang" => 5,
            "muc_ho_tro" => 600000
        ];

        $che_do_chinh_sach_data["mghp"] = [
            "so_thang" => 5,
            "phan_tram_da_giam" => $item['ti_le_giam'] ?? 0,
            "so_tien_1_thang_da_giam" => $item['so_tien_giam_1_thang'] ?? 0,
            "phan_tram_giam" => 100 - ($item['ti_le_giam'] ?? 0),
            "hoc_phi" => $item['muc_hoc_phi'],
            "so_tien_1_thang_giam" => ($item['muc_hoc_phi'] / 5) - ($item['so_tien_giam_1_thang'] ?? 0),
        ];


        $data->che_do_chinh_sach_data = json_encode($che_do_chinh_sach_data);

        $doi_tuong_chinh_sach = json_decode($data->doi_tuong_chinh_sach, true);

        if ($type == 1) {
            if (strpos($data->doi_tuong_chinh_sach, '3') !== false) {
                $doi_tuong_chinh_sach = ["1", "2", "3"];
            } else {
                $doi_tuong_chinh_sach = ["1", "2"];
            }
        } elseif ($type == 4) {
            if (strpos($data->doi_tuong_chinh_sach, '3') !== false) {
                $doi_tuong_chinh_sach = ["2", "3", "4"];
            } else {
                $doi_tuong_chinh_sach = ["2", "4"];
            }
        } elseif ($type == 0) {
            $doi_tuong_chinh_sach = ["2"];
        }


        $data->doi_tuong_chinh_sach = json_encode($doi_tuong_chinh_sach, true);
        return $data;
    }

    function guiTBSALL()
    {
        $phieu_DSCDTA = Phieu::where('key', 'DSCDTA')->orderBy('created_at', 'desc')->first();
        $phieu_DSCDHP = Phieu::where('key', 'DSCDHP')->orderBy('created_at', 'desc')->first();
        $phieu_DSCDKTX1 = Phieu::where('key', 'DSCDKTX1')->orderBy('created_at', 'desc')->first();
        $phieu_QDCDKTX4 = Phieu::where('key', 'DSCDKTX4')->orderBy('created_at', 'desc')->first();

        $users = User::get();
        foreach ($users as $item) {
            $this->notification("Danh sách trợ cấp chi phí học tập", $phieu_DSCDTA->id, "CDCS", $item->id);
            $this->notification("Danh sách trợ cấp chi phí học tập", $phieu_DSCDHP->id, "CDCS", $item->id);
            $this->notification("Danh sách trợ cấp chi phí học tập", $phieu_DSCDKTX1->id, "CDCS", $item->id);
            $this->notification("Danh sách trợ cấp chi phí học tập", $phieu_QDCDKTX4->id, "CDCS", $item->id);
        }


        $phieu = Phieu::where('status', 0)->update(['status' => 1]);
        $query = StopStudy::where('type', 4)
            ->studentActive()
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->whereNull('parent_id')
            ->where('stop_studies.status', 6)
            ->select('stop_studies.*')
            ->get();
        foreach ($query as $stopStudy) {
            $stopStudy->status = -99;
            $stopStudy->save();
        }
        return redirect()->back();
    }
    function createQuyetDinh(Request $request)
    {
        $date = Carbon::createFromFormat('d/m/Y', $request->thoi_gian_tao);
        $nam = $request->nam;
        $ky = $request->ky;

        $this->quyetDinh(
            $date,
            $request,
            $nam,
            $ky
        );

        return true;
    }

    function quyetDinh($date, $request, $nam, $ky)
    {
        $day = $date->day;
        $month = $date->month;
        $year = $date->year;
        $content = [
            "so_QD_TA" => $request->so_QD_TA,
            "so_QD_HP" => $request->so_QD_HP,
            "so_QD_KTX_1" => $request->so_QD_KTX_1,
            "so_QD_KTX_4" => $request->so_QD_KTX_4,
            "tom_tat" => $request->tom_tat,
            "thoi_gian_tao_ngay" => $day,
            "thoi_gian_tao_thang" => $month,
            "thoi_gian_tao_nam" => $year,
            "nam" => $nam,
            "ky" => $ky,
            "tong_cdta" => 0,
            "tong_cdhp" => 0,
            "tong_hs_cdktx_1" => 0,
            "tong_hs_cdktx_4" => 0
        ];

        $query = StopStudy::where('type', 4)
            ->where('stop_studies.status', '>=', 1)
            ->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'lops.hocphi')
            ->get();

        $content_DSCDTA = [];
        $content_DSCDHP = [];
        $content_DSCDKTX_1 = [];
        $content_DSCDKTX_4 = [];

        $content["tong_hs_cdta"] = 0;
        $content["tong_hs_cdhp"] = 0;

        foreach ($query as $item) {
            if (strpos($item->doi_tuong_chinh_sach, '1') !== false || strpos($item->doi_tuong_chinh_sach, '2') !== false) {
                if (strpos($item->doi_tuong_chinh_sach, '1') !== false) {
                    $doituong = 1;
                }
                if (strpos($item->doi_tuong_chinh_sach, '2') !== false) {
                    $doituong = 2;
                }
                $content_DSCDTA[] =  [
                    "ho_ten" => $item->full_name,
                    "ngay_sinh" => Carbon::createFromFormat('Y-m-d', $item->date_of_birth)->format('d/m/Y'),
                    "lop" => $item->lop_name,
                    "doi_tuong" => 'Đối tượng ' . $doituong,
                    "muc_tro_cap" => 600000,
                    "so_thang_tro_cap" => 5,
                    "tro_cap_ky" => 5 * 600000
                ];
                $content["tong_cdta"] += 5 * 600000;
                $content["tong_hs_cdta"] += 1;
            }
            if (strpos($item->doi_tuong_chinh_sach, '1') !== false || strpos($item->doi_tuong_chinh_sach, '3') !== false) {
                if (strpos($item->doi_tuong_chinh_sach, '1') !== false) {
                    $doituong = 1;
                }
                if (strpos($item->doi_tuong_chinh_sach, '3') !== false) {
                    $doituong = 2;
                }
                $che_do_chinh_sach_data = json_decode($item->che_do_chinh_sach_data, true);
                if ($che_do_chinh_sach_data["mghp"]["phan_tram_giam"] == 0) {
                } else {
                    $content_DSCDHP[] =  [
                        "ho_ten" => $item->full_name,
                        "ngay_sinh" => Carbon::createFromFormat('Y-m-d', $item->date_of_birth)->format('d/m/Y'),
                        "lop" => $item->lop_name,
                        "doi_tuong" => 'Đối tượng ' . $doituong,
                        "so_thang_tro_cap" => 5,
                        "diem_ht" => $che_do_chinh_sach_data["diem"]["diemtb"],
                        "diem_rl" => $che_do_chinh_sach_data["diem"]["diemrenluyen"],
                        "phan_tram_giam" => $che_do_chinh_sach_data["mghp"]["phan_tram_giam"],
                        "tro_cap_thang" => $che_do_chinh_sach_data["mghp"]["so_tien_1_thang_giam"],
                        "tro_cap_ky" => $che_do_chinh_sach_data["mghp"]["so_tien_1_thang_giam"] * 5,
                        "xep_loai" => $this->xepLoaiHocLuc($che_do_chinh_sach_data["diem"]["diemtb"], $che_do_chinh_sach_data["diem"]["diemrenluyen"]),
                    ];
                    $content["tong_cdhp"] += $che_do_chinh_sach_data["mghp"]["so_tien_1_thang_giam"] * 5;
                    $content["tong_hs_cdhp"] += 1;
                }
            }

            if (strpos($item->doi_tuong_chinh_sach, '1') !== false || strpos($item->doi_tuong_chinh_sach, '4') !== false) {
                if (strpos($item->doi_tuong_chinh_sach, '1') !== false) {
                    $doituong = 1;
                }
                if (strpos($item->doi_tuong_chinh_sach, '4') !== false) {
                    $doituong = 2;
                }
                $che_do_chinh_sach_data = json_decode($item->che_do_chinh_sach_data, true);

                if (strpos($item->doi_tuong_chinh_sach, '1') !== false) {
                    $content_DSCDKTX_1[] =  [
                        "ho_ten" => $item->full_name,
                        "ngay_sinh" => Carbon::createFromFormat('Y-m-d', $item->date_of_birth)->format('d/m/Y'),
                        "lop" => $item->lop_name,
                        "doi_tuong" => 'Đối tượng ' . $doituong,
                        "ngay_vao" => $che_do_chinh_sach_data["ktx"]["bat_dau"] ?? "",
                        "so_thang" => $che_do_chinh_sach_data["ktx"]["so_thang"] ?? 0,
                    ];
                    $content["tong_hs_cdktx_1"] += 1;
                } else {
                    $content_DSCDKTX_4[] =  [
                        "ho_ten" => $item->full_name,
                        "ngay_sinh" => Carbon::createFromFormat('Y-m-d', $item->date_of_birth)->format('d/m/Y'),
                        "lop" => $item->lop_name,
                        "doi_tuong" => 'Đối tượng ' . $doituong,
                        "diem_ht" => $che_do_chinh_sach_data["diem"]["diemtb"],
                        "diem_rl" => $che_do_chinh_sach_data["diem"]["diemrenluyen"],
                        "dia_chi" => $item->diachi ?? "",
                        "km" => $item->km ?? "",
                        "ngay_vao" => $che_do_chinh_sach_data["ktx"]["bat_dau"] ?? "",
                        "so_thang" => $che_do_chinh_sach_data["ktx"]["so_thang"] ?? 0,
                    ];
                    $content["tong_hs_cdktx_4"] += 1;
                }
            }
        }

        Phieu::updateOrCreate(
            [
                'key' => 'DSCDTA',
                'status' => 0
            ],
            [
                'name' => 'Danh sách sinh viên được hưởng hỗ trợ tiền ăn (Điều f,g, khoản 3, điều 1)',
                'content' => json_encode([$content, $content_DSCDTA], true)
            ]
        );
        Phieu::updateOrCreate(
            [
                'key' => 'QDCDTA',
                'status' => 0
            ],
            [
                'name' => 'Quyết định sinh viên được hưởng hỗ trợ tiền ăn (Điều f,g, khoản 3, điều 1)',
                'content' => json_encode([$content, 0], true)
            ]
        );

        Phieu::updateOrCreate(
            [
                'key' => 'DSCDHP',
                'status' => 0
            ],
            [
                'name' => 'Danh sách sinh viên được hưởng hỗ trợ học phí (Điều c, g, khoản 3, điều 1)',
                'content' => json_encode([$content, $content_DSCDHP], true)
            ]
        );
        Phieu::updateOrCreate(
            [
                'key' => 'QDCDHP',
                'status' => 0
            ],
            [
                'name' => 'Quyết định sinh viên được hưởng hỗ trợ học phí (Điều c, g, khoản 3, điều 1)',
                'content' => json_encode([$content, 0], true)
            ]
        );


        Phieu::updateOrCreate(
            [
                'key' => 'DSCDKTX1',
                'status' => 0
            ],
            [
                'name' => 'Danh sách sinh viên được hỗ trợ chỗ ở (Điểm g, khoản 3, điều 1)',
                'content' => json_encode([$content, $content_DSCDKTX_1], true)
            ]
        );

        Phieu::updateOrCreate(
            [
                'key' => 'DSCDKTX4',
                'status' => 0
            ],
            [
                'name' => 'Danh sách sinh viên được hỗ trợ chỗ ở  (Điểm e, khoản 3, điều 1)',
                'content' => json_encode([$content, $content_DSCDKTX_4], true)
            ]
        );


        Phieu::updateOrCreate(
            [
                'key' => 'QDCDKTX1',
                'status' => 0
            ],
            [
                'name' => 'Danh sách sinh viên được hỗ trợ chỗ ở (Điểm g, khoản 3, điều 1)',
                'content' => json_encode([$content, $content_DSCDKTX_1], true)
            ]
        );

        Phieu::updateOrCreate(
            [
                'key' => 'QDCDKTX4',
                'status' => 0
            ],
            [
                'name' => 'Danh sách sinh viên được hỗ trợ chỗ ở  (Điểm e, khoản 3, điều 1)',
                'content' => json_encode([$content, $content_DSCDKTX_4], true)
            ]
        );

        Phieu::updateOrCreate(
            [
                'key' => 'PTQT4',
                'status' => 0
            ],
            [
                'name' => 'Phiếu trình chế độ chính sách',
                'content' => json_encode([$content, $content_DSCDKTX_4], true)
            ]
        );
    }
    public function xepLoaiHocLuc($diemtb, $diemrenluyen)
    {
        if ($diemtb >= 90 && $diemtb <= 100) {
            $hocluc = 'Xuất sắc';
        } elseif ($diemtb >= 80 && $diemtb < 90) {
            $hocluc = 'Giỏi';
        } elseif ($diemtb >= 70 && $diemtb < 80) {
            $hocluc = 'Khá';
        } elseif ($diemtb >= 60 && $diemtb < 70) {
            $hocluc = 'Trung bình khá';
        } elseif ($diemtb >= 50 && $diemtb < 60) {
            $hocluc = 'Trung bình';
        } elseif ($diemtb >= 40 && $diemtb < 50) {
            $hocluc = 'Yếu';
        } else {
            $hocluc = 'Kém';
        }

        return $hocluc;
    }
    function xoaQuyetDinh()
    {
        Phieu::where('status', 0)
            ->whereIn('key', ['DSCDTA', 'QDCDTA', 'DSCDHP','QDCDHP','DSCDKTX1','DSCDKTX1','QDCDKTX1','QDCDKTX4','PTQT4'])
            ->delete();
        return redirect()->back();
    }

    function getQuyetDinh(Request $request)
    {
        $phieu =  Phieu::where('key', 'QDCDTA')->where('status', 0)->first();
        if ($phieu) {
            return json_decode($phieu->content, true)[0];
        }
        return [];
    }
}
