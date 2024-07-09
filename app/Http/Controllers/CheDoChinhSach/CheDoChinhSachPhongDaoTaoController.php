<?php

namespace App\Http\Controllers\CheDoChinhSach;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\StopStudy;
use App\Models\Student;
use Hamcrest\Core\HasToString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheDoChinhSachPhongDaoTaoController extends Controller
{
    function index()
    {
        $lop = Lop::get();
        return view('phong_dao_tao.create_ds_che_do_chinh_sach.index', ['lop' => $lop]);
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
            $this->notification("Cần kiểm tra lại thông tin trong danh sách miễn giảm học phí", null, "GHP", $user_id);

            $users = User::where(function ($query) {
                $query->where('role', 2)
                    ->orWhere('role', 3);
            })->get();
            foreach ($users as $item) {
                $this->notification("Danh sách chế độ chính sách đã bị từ chối bởi lãnh đạo phòng đào tạo ", null, "GHP", $item->id);
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
            $header = [
                "ma_sinh_vien" => "student_code",
                "ho_ten" => "student_code",
                "ten_lop" => "student_code",
                "phong" => "student_code",
                "ngay_vao" => "student_code",
                "so_thang" => "student_code",
            ];
            $error_line = 0;
            DB::beginTransaction();
            try {
                $indexHeader = [];
                foreach ($data['header'] as $index => $value) {
                    $indexHeader[$value] = $index;
                }
                $tien_ktx = $data['data'][0][$indexHeader['tien_ktx_1_thang']];
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
                        ->where(function ($query) {
                            $query->where('doi_tuong_chinh_sach', 'like', '%1%')
                                ->orWhere('doi_tuong_chinh_sach', 'like', '%4%');
                        })->first();
                    if ($check_phieu) {
                        $che_do_chinh_sach_data = json_decode($check_phieu->che_do_chinh_sach_data,true);
                        $che_do_chinh_sach_data["ktx"] = [
                            "bat_dau" => $item[$indexHeader['ngay_vao']],
                            "so_tien" => $tien_ktx,
                            "so_thang" => $item[$indexHeader['so_thang']]
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

    function hoTroTienAn(Request $request)
    {
        set_time_limit(3600);
        if ($request->hasFile('csv_file')) {
            $data = $this->importCSV($request->file('csv_file'));
            $header = [
                "ma_sinh_vien" => "student_code",
                "ho_ten" => "student_code",
                "ten_lop" => "student_code",
                "phong" => "student_code",
                "ngay_vao" => "student_code",
                "so_thang" => "student_code",
            ];
            $error_line = 0;
            DB::beginTransaction();
            try {
                $check_phieu = StopStudy::where('type', 4)
                        ->where(function ($query) {
                            $query->where('doi_tuong_chinh_sach', 'like', '%1%')
                                ->orWhere('doi_tuong_chinh_sach', 'like', '%4%');
                        })->first();

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
}
