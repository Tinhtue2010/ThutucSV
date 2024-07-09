<?php

namespace App\Http\Controllers\HoSo;

use App\Http\Controllers\Controller;
use App\Http\Service\KhoaService;
use App\Models\Lop;
use App\Models\Phieu;
use App\Models\StopStudy;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KhoaController extends Controller
{
    private $khoa;
    function __construct()
    {
        $this->khoa = new KhoaService();
    }
    function index()
    {

        return view('khoa.index');
    }

    public function getData(Request $request)
    {
        $user = Auth::user();

        $query = StopStudy::query()
            ->studentActive()
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->select('stop_studies.*', 'students.full_name', 'students.student_code', 'lops.name as lop_name')
            ->where(function ($query) {
                $query
                    ->where(function ($query) {
                        $query->where('doi_tuong_chinh_sach', 'like', '%1%')
                            ->orWhere('doi_tuong_chinh_sach', 'like', '%4%')
                            ->orWhereNull('doi_tuong_chinh_sach');
                    });
            });

        $teacher = Teacher::find($user->teacher_id);
        $lopIds = Lop::where('khoa_id', $teacher->khoa_id ?? 0)->pluck('id') ?? [];

        $query = $query->whereIn('stop_studies.lop_id', $lopIds);

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

    function xacnhan(Request $request)
    {
        try {
            $stopStudy =  StopStudy::find($request->id);
            if ($stopStudy->type == 0) {
                $this->khoa->xacnhanRHS($request, $stopStudy);
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
                $this->khoa->khongxacnhanRHS($request, $stopStudy);
            }
        } catch (QueryException $e) {
            abort(404);
        }
    }
}
