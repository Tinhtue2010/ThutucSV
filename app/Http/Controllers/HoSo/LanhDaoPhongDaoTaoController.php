<?php

namespace App\Http\Controllers\HoSo;

use App\Http\Controllers\Controller;
use App\Http\Service\LanhDaoPhongDaoTaoService;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanhDaoPhongDaoTaoController extends Controller
{
    private $lanhdaophongdaotao;
    function __construct()
    {
        $this->lanhdaophongdaotao = new LanhDaoPhongDaoTaoService();
    }
    function index()
    {
        $tb_miengiamhp = StopStudy::where('type', 1)->whereNull('parent_id')->where('status', 2)->count();
        $miengiamhp = StopStudy::where('type', 1)->whereNotNull('parent_id')->where(function ($query) {
            $query->where('status', 2)
                ->orWhere('status', 1)
                ->orWhere('status', 3)
                ->orWhere('status', -3);
        })->count();
        return view('lanh_dao_phong_dao_tao.index',['miengiamhp'=>$miengiamhp,'tb_miengiamhp'=>$tb_miengiamhp]);
    }

    public function getData(Request $request)
    {
        $user = Auth::user();

        $query = StopStudy::query()
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
        $stopStudy =  StopStudy::find($request->id);
        if ($stopStudy->type == 0) {
            return $this->lanhdaophongdaotao->xacnhanRHS($request, $stopStudy);
        }
    }

    function tuchoihs(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        if ($stopStudy->type == 0) {
            return $this->lanhdaophongdaotao->tuchoihsRHS($request, $stopStudy);
        }
    }
}
