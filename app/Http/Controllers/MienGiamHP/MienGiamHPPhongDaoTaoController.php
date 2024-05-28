<?php

namespace App\Http\Controllers\MienGiamHP;

use App\Http\Controllers\Controller;
use App\Models\Lop;
use App\Models\StopStudy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MienGiamHPPhongDaoTaoController extends Controller
{
    function index()
    {
        $lop = Lop::get();
        return view('phong_dao_tao.create_ds_mien_giam_hp.index', ['lop' => $lop]);
    }

    function getData(Request $request)
    {
        $query = StopStudy::where('type', 1)
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
        $query = StopStudy::where('type', 1)
            ->studentActive()
            ->where(function ($query) {
                $query->where('stop_studies.status', 1)
                    ->orWhere('stop_studies.status', 2);
            })
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->update(['status' => 2]);
        $username = Auth::user()->name;
        $users = User::where('role', 6)->get();
        foreach ($users as $item) {
            $this->notification("Danh sách miễn giảm học phí đã được tạo bởi " . $username, null, "GHP", $item->id);
        }
        return redirect()->back();
    }
    function deleteList(Request $request)
    {
        $query = StopStudy::where('type', 1)
            ->studentActive()
            ->where(function ($query) {
                $query->where('stop_studies.status', 1)
                    ->orWhere('stop_studies.status', 2);
            })
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->whereNull('parent_id')
            ->update(['status' => 1]);

        return redirect()->back();
    }

    function guiTBSV()
    {
        $query = StopStudy::where('type', 1)
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
                $this->notification("Danh sách miễn giảm học phí đã bị từ chối bởi lãnh đạo phòng đào tạo ", null, "GHP", $item->id);
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

    function tinhSoLuong()
    {
        $miengiamHP = StopStudy::where('type', 1)->where('stop_studies.status', '>', 0)
            ->studentActive()
            ->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->selectRaw('ROUND(SUM(stop_studies.phantramgiam / 100 * lops.hocphi)) as hocphi, COUNT(*) as tong')
            ->get()->toArray();
        return $miengiamHP;
    }
}
