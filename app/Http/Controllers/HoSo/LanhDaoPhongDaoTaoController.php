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
        $tb_trocapxahoi = StopStudy::where('type', 2)->whereNull('parent_id')->where('status', 2)->count();
        $tb_chedochinhsach = StopStudy::where('type', 2)->whereNull('parent_id')->where('status', 2)->count();
        return view('lanh_dao_phong_dao_tao.index', ['tb_trocapxahoi' => $tb_trocapxahoi, 'tb_miengiamhp' => $tb_miengiamhp,"tb_chedochinhsach"=>$tb_chedochinhsach]);
    }

    public function getData(Request $request)
    {
        $user = Auth::user();

        $query = StopStudy::query()
            ->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->select('stop_studies.*', 'students.full_name', 'students.student_code', 'lops.name as lop_name')
            ->where(function ($query) {
                $query
                    ->where(function ($query) {
                        $query->where('doi_tuong_chinh_sach', 'like', '%1%')
                            ->orWhere('doi_tuong_chinh_sach', 'like', '%4%')
                            ->orWhereNull('doi_tuong_chinh_sach');
                    });
            });
        if (isset($request->year)) {
            $query->whereYear('stop_studies.created_at', $request->year);
        }
        if (isset($request->status)) {
            $query->when($request->status == 0, function ($query) {
                $query->where('stop_studies.status', 0);
            })
                ->when($request->status == 1, function ($query) {
                    $query
                        ->where('stop_studies.status', '>', 0)
                        ->where('stop_studies.status', '<', 6);
                })
                ->when($request->status == 2, function ($query) {
                    $query->whereIn('stop_studies.status', [6, -99]);
                });
        }
        if (isset($request->type)) {
            $query->where('stop_studies.type', $request->type);
        }
        $data = $this->queryPagination($request, $query, ['students.full_name', 'students.student_code']);

        return $data;
    }
    function xacnhanRHS(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        $this->giaiQuyetCongViec($request->ykientiepnhan ?? '', $stopStudy, 3);
        if ($stopStudy->type == 0) {
            return $this->lanhdaophongdaotao->xacnhanRHSPDF($request, $stopStudy);
        }
    }
    function xacnhanPDF(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        $this->giaiQuyetCongViec($request->ykientiepnhan ?? '', $stopStudy, 3);
        if ($stopStudy->type == 0) {
            return $this->lanhdaophongdaotao->xacnhanRHSPDF($request, $stopStudy);
        }
    }
    function xacnhan(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        $this->giaiQuyetCongViec($request->ykientiepnhan ?? '', $stopStudy, 3);
        if ($stopStudy->type == 0) {
            return $this->lanhdaophongdaotao->xacnhanRHS($request, $stopStudy);
        }
    }
    function tuchoihsPDF(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        if ($stopStudy->type == 0) {
            return $this->lanhdaophongdaotao->tuchoihsRHSPDF($request, $stopStudy);
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
