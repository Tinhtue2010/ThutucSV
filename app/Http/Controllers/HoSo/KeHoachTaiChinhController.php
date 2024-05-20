<?php

namespace App\Http\Controllers\HoSo;

use App\Http\Controllers\Controller;
use App\Http\Service\KeHoachTaiChinhService;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeHoachTaiChinhController extends Controller
{
    private $khtc;
    function __construct()
    {
        $this->khtc = new KeHoachTaiChinhService();
    }
    function index()
    {
        $tb_miengiamhp = StopStudy::where('type', 1)->whereNull('parent_id')->where('status', 4)->count();
        $tb_trocapxahoi = StopStudy::where('type', 2)->whereNull('parent_id')->where('status', 4)->count();
        return view('ke_hoach_tai_chinh.index', ['tb_trocapxahoi' => $tb_trocapxahoi, 'tb_miengiamhp' => $tb_miengiamhp]);
    }

    public function getData(Request $request)
    {
        $user = Auth::user();

        $query = StopStudy::query()
->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->select('stop_studies.*', 'students.full_name', 'students.student_code', 'lops.name as lop_name');

        if (isset($request->year)) {
            $query->whereYear('stop_studies.created_at', $request->year);
        }
        if (isset($request->status)) {
            $query->where('status', $request->status);
        }
        if (isset($request->type)) {
            $query->where('type', $request->type);
        }
        $data = $this->queryPagination($request, $query, ['students.full_name', 'students.student_code']);

        return $data;
    }

    function xacnhan(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);
            if ($stopStudy->type == 0) {
                $this->khtc->xacnhanRHS($stopStudy);
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
                $this->khtc->khongxacnhanRHS($request, $stopStudy);
            }
        } catch (QueryException $e) {
            abort(404);
        }
    }
}
