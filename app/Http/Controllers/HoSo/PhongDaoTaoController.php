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
        $tb_miengiamhp = StopStudy::where('type', 1)->whereIn('status', [3, 6])->count();
        $tb_trocapxahoi = StopStudy::where('type', 2)->where('status', 3)->count();
        $tb_trocaphocphi = StopStudy::where('type', 3)->where('status', 3)->count();
        $tb_chedochinhsach = StopStudy::where('type', 4)->where('status', 3)->count();
        $lop = Lop::get();
        return view('phong_dao_tao.index', ['tb_miengiamhp' => $tb_miengiamhp, 'tb_trocapxahoi' => $tb_trocapxahoi, 'lop' => $lop, 'tb_trocaphocphi' => $tb_trocaphocphi, "tb_chedochinhsach" => $tb_chedochinhsach]);
    }

    public function getData(Request $request)
    {
        $user = Auth::user();

        $query = StopStudy::query()
            ->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->select('stop_studies.*', 'students.full_name', 'students.student_code', 'lops.name as lop_name');
            // ->where(function ($query) {
            //     $query
            //         ->where(function ($query) {
            //             $query->where('doi_tuong_chinh_sach', 'like', '%1%')
            //                 ->orWhere('doi_tuong_chinh_sach', 'like', '%4%')
            //                 ->orWhereNull('doi_tuong_chinh_sach');
            //         });
            // });
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
    function bosunghsPDF(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);

            if ($stopStudy->type == 0) {
                return $this->phongdaotao->bosunghsRHSPDF($request, $stopStudy);
            }
            if ($stopStudy->type == 1) {
                return $this->phongdaotao->bosunghsGHPPDF($request, $stopStudy);
            }
            if ($stopStudy->type == 2) {
                return $this->phongdaotao->bosunghsTCXHPDF($request, $stopStudy);
            }
            if ($stopStudy->type == 3) {
                return $this->phongdaotao->bosunghsTCHPPDF($request, $stopStudy);
            }
            if ($stopStudy->type == 4) {
                return $this->phongdaotao->bosunghsCDCSPDF($request, $stopStudy);
            }
        } catch (\Throwable $th) {
            abort(404);
        }
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
            if ($stopStudy->type == 3) {
                return $this->phongdaotao->bosunghsTCHP($request, $stopStudy);
            }
            if ($stopStudy->type == 4) {
                return $this->phongdaotao->bosunghsCDCS($request, $stopStudy);
            }
        } catch (\Throwable $th) {
            abort(404);
        }
    }
    function getbosunghs($id = null)
    {
        return;
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

        return;
    }
    function tiepnhanhsPDF(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);

            $this->giaiQuyetCongViec($request->ykientiepnhan ?? '', $stopStudy, 1);
            if ($stopStudy->type == 0) {
                return $this->phongdaotao->tiepnhanhsRHSPDF($request, $stopStudy);
            }
            if ($stopStudy->type == 1) {
                return $this->phongdaotao->tiepnhanhsGHPPDF($request, $stopStudy);
            }
            if ($stopStudy->type == 2) {
                return $this->phongdaotao->tiepnhanhsTCXHPDF($request, $stopStudy);
            }
            if ($stopStudy->type == 3) {
                return $this->phongdaotao->tiepnhanhsTCHPPDF($request, $stopStudy);
            }
            if ($stopStudy->type == 4) {
                return $this->phongdaotao->tiepnhanhsCDCSPDF($request, $stopStudy);
            }
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    function tiepnhanhs(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);

            $this->giaiQuyetCongViec($request->ykientiepnhan ?? '', $stopStudy, 1);

            if ($stopStudy->type == 0) {
                return $this->phongdaotao->tiepnhanhsRHS($request, $stopStudy);
            }
            if ($stopStudy->type == 1) {
                return $this->phongdaotao->tiepnhanhsGHP($request, $stopStudy);
            }
            if ($stopStudy->type == 2) {
                return $this->phongdaotao->tiepnhanhsTCXH($request, $stopStudy);
            }
            if ($stopStudy->type == 3) {
                return $this->phongdaotao->tiepnhanhsTCHP($request, $stopStudy);
            }
            if ($stopStudy->type == 4) {
                return $this->phongdaotao->tiepnhanhsCDCS($request, $stopStudy);
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

        return;
    }
    function tuchoihsPDF(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        if ($stopStudy->type == 0) {
            return $this->phongdaotao->tuchoihsRHSPDF($request, $stopStudy);
        }
        if ($stopStudy->type == 1) {
            return $this->phongdaotao->tuchoihsGHPPDF($request, $stopStudy);
        }
        if ($stopStudy->type == 2) {
            return $this->phongdaotao->tuchoihsTCXHPDF($request, $stopStudy);
        }
        if ($stopStudy->type == 3) {
            return $this->phongdaotao->tuchoihsTCHPPDF($request, $stopStudy);
        }
        if ($stopStudy->type == 4) {
            return $this->phongdaotao->tuchoihsCDCSPDF($request, $stopStudy);
        }
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
        if ($stopStudy->type == 3) {
            return $this->phongdaotao->tuchoihsTCHP($request, $stopStudy);
        }
        if ($stopStudy->type == 4) {
            return $this->phongdaotao->tuchoihsCDCS($request, $stopStudy);
        }
    }

    function duyeths(Request $request)
    {
        $stopStudy =  StopStudy::find($request->id);
        $this->giaiQuyetCongViec($request->ykientiepnhan ?? '', $stopStudy, 2);
        if ($stopStudy->type == 0) {
            return $this->phongdaotao->duyethsRHS($request, $stopStudy);
        }
        if ($stopStudy->type == 1) {
            return $this->phongdaotao->duyethsGHP($request, $stopStudy);
        }
        if ($stopStudy->type == 2) {
            return $this->phongdaotao->duyethsTCXH($request, $stopStudy);
        }
        if ($stopStudy->type == 3) {
            return $this->phongdaotao->duyethsTCXH($request, $stopStudy);
        }
        if ($stopStudy->type == 4) {
            return $this->phongdaotao->duyethsTCXH($request, $stopStudy);
        }
    }
}
