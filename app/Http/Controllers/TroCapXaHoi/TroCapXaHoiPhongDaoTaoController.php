<?php

namespace App\Http\Controllers\TroCapXaHoi;

use App\Http\Controllers\Controller;
use App\Models\HoSo;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TroCapXaHoiPhongDaoTaoController extends Controller
{
    function index()
    {
        $lop = Lop::get();
        $hoso = HoSo::where('type', 3)->where('status', 0)
            ->latest('created_at')
            ->first();

        return view('phong_dao_tao.create_ds_tro_cap_xa_hoi.index', ['lop' => $lop, 'hoso' => $hoso]);
    }

    function getData(Request $request)
    {
        $query = StopStudy::where('type', 2)
            ->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'students.hocphi');

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

    function updateTroCap(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        $stopStudy->muctrocapxh =  $request->muctrocapxh;
        $stopStudy->save();
        return;
    }
    function createList(Request $request)
    {
        $query = StopStudy::where('type', 2)
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
            $this->notification("Danh sách miễn giảm học phí đã được tạo bởi " . $username, null, "TCXH", $item->id);
        }
        return redirect()->back();
    }
    function deleteList(Request $request)
    {
        $query = StopStudy::where('type', 2)
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
        $query = StopStudy::where('type', 2)
            ->studentActive()
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->whereNull('parent_id')
            ->where('stop_studies.status', 3)
            ->select('stop_studies.*')
            ->get();
        $hoso = HoSo::where('type', 3)->where('status', 0)
            ->latest('created_at')
            ->first();
        foreach ($query as $stopStudy) {
            $stopStudy->status = 4;
            $stopStudy->save();
            $user_id = User::where('student_id', $stopStudy->student_id)->first()->id;
            $this->notification("Danh sách trợ cấp xã hội dự kiến", null,$hoso->file_list, "TCXH", $user_id);

            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 1;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->parent_id = $stopStudy->id;
            $newStopStudy->note = "Gửi thông báo về danh sách";
            $newStopStudy->save();
        }

        $users = User::where(function ($query) {
            $query->where('role', 2)
                ->orWhere('role', 3);
        })->get();
        foreach ($users as $item) {
            $this->notification("Danh sách trợ cấp xã hội dự kiến",null, $hoso->file_list,  "TCXH", $item->id);
        }
        return redirect()->back();
    }
    function guiTBSALL()
    {

        $hoso = HoSo::where('type', 3)->where('status', 0)
            ->latest('created_at')
            ->first();

        $users = User::get();
        foreach ($users as $item) {
            $this->notification("Danh sách trợ cấp xã hội", null, $hoso->file_list,  "TCXH", $item->id);
        }


        $query = StopStudy::where('type', 2)
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
        $date = Carbon::createFromFormat('d/m/Y', $request->thoi_gian_tao,);

        $day = $date->day;
        $month = $date->month;
        $year = $date->year;
        $content = [
            "so_QD" => $request->so_QD,
            "thoi_gian_tao_ngay" => $day,
            "thoi_gian_tao_thang" => $month,
            "thoi_gian_tao_nam" => $year,
            "tom_tat" => $request->tom_tat ?? "",
            "nam" => $request->nam,
            "ky" => $request->ky,
            "tong" => 0,
        ];

        $content_DSTCXH = [];

        $query = StopStudy::where('type', 2)
            ->where('stop_studies.status', '>=', 1)
            ->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name', 'students.hocphi')
            ->get();

        $content["tong_hs"] = count($query);

        foreach ($query as $item) {
            $content_DSTCXH[] =  [
                "ho_ten" => $item->full_name,
                "ngay_sinh" => Carbon::createFromFormat('Y-m-d', $item->date_of_birth)->format('d/m/Y'),
                "lop" => $item->lop_name,
                "doi_tuong" => 'Đối tượng ' . $item->type_miengiamhp + 1,
                "muc_tro_cap_xh" => $item->muctrocapxh,
                "so_thang_tro_cap" => 6,
                "tro_cap_ky" => 6 * $item->muctrocapxh
            ];
            $content["tong"] += 6 * $item->muctrocapxh;
        }



        $phieu1 = new Phieu();
        $phieu1->key = 'DSTCXH';
        $phieu1->status = 0;
        $phieu1->name = 'Danh sách sinh viên được trợ cấp xã hội';
        $phieu1->content = json_encode([$content, $content_DSTCXH], true);

        $base64 = $this->createPDF($phieu1, 1);
        $file_list = $this->saveBase64AsPdf($base64, 'TRO_CAP_XH/' . $year . '/' . $month, 'ds_tro_cap_xh');


        $phieu2 = new Phieu();
        $phieu2->key = 'QDTCXH';
        $phieu2->status = 0;
        $phieu2->name = 'Quyết định trợ cấp xã hội';
        $phieu2->content = json_encode([$content, 0], true);

        $base64 = $this->createPDF($phieu2);
        $file_quyet_dinh = $this->saveBase64AsPdf($base64, 'TRO_CAP_XH/' . $year . '/' . $month, 'qd_tro_cap_xh');

        // Tìm hồ sơ hiện có
        $hoso = HoSo::where('ky_hoc', $request->ky)
            ->where('nam_hoc', $request->nam)
            ->where('type', 3)
            ->latest('created_at')
            ->first();

        if ($hoso) {
            if ($hoso->file_quyet_dinh != $file_quyet_dinh) {
                $this->deletePdf($hoso->file_quyet_dinh);
            }
            if ($hoso->file_list != $file_list) {
                $this->deletePdf($hoso->file_list);
            }

            $hoso->update([
                'name' => "Trợ cấp xã hội",
                'file_quyet_dinh' => $file_quyet_dinh,
                'file_list' => $file_list,
                'list_info' => json_encode($content_DSTCXH, true),
                'type' => 3
            ]);
        } else {
            $hoso = HoSo::create([
                'name' => "Trợ cấp xã hội",
                'file_quyet_dinh' => $file_quyet_dinh,
                'file_list' => $file_list,
                'ky_hoc' => $request->ky,
                'nam_hoc' => $request->nam,
                'list_info' => json_encode($content_DSTCXH, true),
                'type' => 3
            ]);
        }

        return true;
    }
    function xoaQuyetDinh()
    {
        Phieu::where('status', 0)
            ->whereIn('key', ['DSTCXH', 'QDTCXH', 'PTTCXH'])
            ->delete();
        return redirect()->back();
    }

    function getQuyetDinh(Request $request)
    {
        return [];
    }

    function tinhSoLuong()
    {
        $trocapxh = StopStudy::where('type', 2)->where('stop_studies.status', '>', 0)
            ->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->selectRaw('SUM(stop_studies.muctrocapxh) as hocphi, COUNT(*) as tong')
            ->get()->toArray();
        return $trocapxh;
    }
}
