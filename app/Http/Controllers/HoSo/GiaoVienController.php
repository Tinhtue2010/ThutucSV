<?php

namespace App\Http\Controllers\HoSo;

use App\Http\Controllers\Controller;
use App\Http\Service\GiaoVienService;
use App\Models\HoSo;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Teacher;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GiaoVienController extends Controller
{
    private $giaovien;
    function __construct()
    {
        $this->giaovien = new GiaoVienService();
    }
    function index()
    {

        return view('giao_vien.index');
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $query = StopStudy::query()
            ->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->select('stop_studies.*', 'students.full_name', 'students.student_code as student_code', 'lops.name as lop_name');

        $ma_lop = Lop::where('teacher_id', $user->teacher_id)->pluck('ma_lop');
        $query = $query->whereIn('stop_studies.ma_lop', $ma_lop);

        $data = $this->queryPagination($request, $query, ['students.full_name', 'students.student_code']);
        return $data;
    }

    function KyDonPdf(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);

            if ($stopStudy->type == 0) {
                return $this->giaovien->KyDonPdfRHS($request, $stopStudy);
            }
        } catch (QueryException $e) {
            abort(404);
        }
    }
    function xacnhan(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);

            if ($stopStudy->type == 0) {
                return $this->giaovien->xacnhanRHS($request, $stopStudy);
            }
        } catch (QueryException $e) {
            abort(404);
        }
    }
    function khongxacnhan(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);
            if ($stopStudy->type == 0) {
                $this->giaovien->khongxacnhanRHS($request, $stopStudy);
            }
        } catch (QueryException $e) {
            abort(404);
        }
    }


    function getDataChild($id = null)
    {
        try {
            $don = StopStudy::where('id', $id)->first();
            $don_chill =  StopStudy::where('parent_id', $id)
                ->leftJoin('teachers', 'teachers.id', '=', 'stop_studies.teacher_id')
                ->select('stop_studies.*', 'teachers.full_name', 'teachers.chuc_danh')
                ->orderBy('created_at', 'desc')->get();
            $data[] = json_decode($don->files ?? '[]');
            $data[] = $don_chill;
            $data[] = $don->file_name;
            return $data;
        } catch (QueryException $e) {
            abort(404);
        }
    }
}
