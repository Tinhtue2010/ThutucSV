<?php

namespace App\Http\Controllers\HoSo;

use App\Http\Controllers\Controller;
use App\Http\Service\PhongDaoTaoService;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhongDaoTaoController extends Controller
{
    private $phongdaotao;
    function __construct()
    {
        $this->phongdaotao = new PhongDaoTaoService();
    }
    function index()
    {
        $tb_miengiamhp = StopStudy::where('type', 1)->where('status', 3)->count();
        $tb_trocapxahoi = StopStudy::where('type', 2)->where('status', 3)->count();
        $lop = Lop::get();
        return view('phong_dao_tao.index', ['tb_miengiamhp' => $tb_miengiamhp, 'tb_trocapxahoi' => $tb_trocapxahoi, 'lop' => $lop]);
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

    function bosunghs(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);
            if ($stopStudy->type == 0) {
                return $this->phongdaotao->bosunghsRHS($request, $stopStudy);
            }
            if ($stopStudy->type == 1) {
                return $this->phongdaotao->bosunghsGHP($request, $stopStudy);
            }
            if ($stopStudy->type == 2) {
                return $this->phongdaotao->bosunghsTCXH($request, $stopStudy);
            }
        } catch (\Throwable $th) {
            abort(404);
        }
    }
    function getbosunghs($id = null)
    {
        if ($id == null) {
            abort(404);
        }
        $stopStudy =  StopStudy::find($id);
        if (!$stopStudy) {
            abort(404);
        }
        if ($stopStudy->status != 2 && $stopStudy->status != -3  && $stopStudy->status != 3) {
            abort(404);
        }
        $newStopStudy = $stopStudy->where('parent_id', $stopStudy->id)->orderBy('created_at', 'desc')->first();
        $phieu = Phieu::find($newStopStudy->phieu_id);
        if (!$phieu) {

            return ['huongdankhac' => $stopStudy->note_pay];
        }
        return json_decode($phieu->content);
    }

    function tiepnhanhs(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);
            if ($stopStudy->type == 0) {
                return $this->phongdaotao->tiepnhanhsRHS($request, $stopStudy);
            }
            if ($stopStudy->type == 1) {
                return $this->phongdaotao->tiepnhanhsGHP($request, $stopStudy);
            }
            if ($stopStudy->type == 2) {
                return $this->phongdaotao->tiepnhanhsTCXH($request, $stopStudy);
            }
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    function gettiepnhanhs($id = null)
    {
        if ($id == null) {
            abort(404);
        }
        $stopStudy =  StopStudy::find($id);
        if (!$stopStudy) {
            abort(404);
        }
        if ($stopStudy->status != 2 && $stopStudy->status != -3) {
            abort(404);
        }
        $newStopStudy = $stopStudy->where('parent_id', $stopStudy->id)->orderBy('created_at', 'desc')->first();
        $phieu = Phieu::find($newStopStudy->phieu_id);
        if (!$phieu) {
            abort(404);
        }
        return json_decode($phieu->content);
    }

    function tuchoihs(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        if ($stopStudy->type == 0) {
            return $this->phongdaotao->tuchoihsRHS($request, $stopStudy);
        }
        if ($stopStudy->type == 1) {
            return $this->phongdaotao->tuchoihsGHP($request, $stopStudy);
        }
        if ($stopStudy->type == 2) {
            return $this->phongdaotao->tuchoihsTCXH($request, $stopStudy);
        }
    }



    function duyeths(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        if ($stopStudy->type == 0) {
            return $this->phongdaotao->duyethsRHS($request, $stopStudy);
        }
    }
}
