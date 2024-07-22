<?php

namespace App\Http\Controllers;

use App\Models\Phieu;
use App\Models\StopStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HoSoChungTuPhongDaoTaoController extends Controller
{
    function index()
    {
        return view('phong_dao_tao.ho_so_chung_tu.index');
    }

    function getData(Request $request)
    {
        $query = Phieu::leftJoin('students', 'phieus.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->select('phieus.*', 'students.full_name', 'students.student_code', 'lops.name as lop_name');

        if (isset($request->group)) {
            $query->where('key',  $request->group);
        }

        if (isset($request->type)) {
        }
        if (isset($request->year)) {
            $query->whereYear('phieus.created_at', $request->year);
        }
        $data = $this->queryPagination($request, $query, ['phieus.name', 'students.student_code', 'lops.name']);
        return $data;
    }
    function saveAll(Request $request)
    {
        DB::beginTransaction();
        try {
            if (isset($request->RHS)) {
                $query = StopStudy::where('type', 0)
                    ->whereNull('parent_id')->get();

                foreach ($query as $item) {
                    $phieu = Phieu::find($item->phieu_id);
                    if ($phieu) {
                        $phieu->file = $item->files;

                        $phieu->status = 1;
                        $phieu->save();
                    }


                    // $data_phieu[0] = $item->type;
                    // $data_phieu[1]['tiepnhan'] = json_decode($item->tiepnhan);
                    // $data_phieu[1]['ykien'] = json_decode($item->ykien);
                    // $data_phieu[1]['lanhdaophong'] = json_decode($item->lanhdaophong);
                    // $data_phieu[1]['lanhdaotruong'] = json_decode($item->lanhdaotruong);

                    // $phieu = new Phieu();
                    // $phieu->key = "GQCVRHS";
                    // $phieu->name = "Phiếu giải quyết công việc đơn xin rút hồ sơ";
                    // $phieu->status = 1;
                    // $phieu->student_id = $item->student_id;
                    // $phieu->content = json_encode($data_phieu);
                    // $phieu->save();

                    $check_phieu_khac =  StopStudy::where('parent_id', $item->id)->whereNotNull('phieu_id')->first();
                    if ($check_phieu_khac) {
                        Phieu::find($check_phieu_khac->phieu_id)->delete();
                    }
                    $item->delete();
                }
                StopStudy::where('type', 0)->whereNotNull('phieu_id')->delete();
                StopStudy::where('type', 0)->whereNull('phieu_id')->delete();
            }
            if (isset($request->DHP)) {
                $query = StopStudy::where('type', 1)
                    ->whereNull('parent_id')->get();

                foreach ($query as $item) {
                    $phieu = Phieu::find($item->phieu_id);
                    if ($phieu) {
                        $phieu->file = $item->files;

                        $phieu->status = 1;
                        $phieu->save();
                    }

                    // $data_phieu[0] = $item->type;
                    // $data_phieu[1]['tiepnhan'] = json_decode($item->tiepnhan);
                    // $data_phieu[1]['ykien'] = json_decode($item->ykien);
                    // $data_phieu[1]['lanhdaophong'] = json_decode($item->lanhdaophong);
                    // $data_phieu[1]['lanhdaotruong'] = json_decode($item->lanhdaotruong);

                    // $phieu = new Phieu();
                    // $phieu->key = "GQCVDHP";
                    // $phieu->name = "Phiếu giải quyết công việc đơn xin rút hồ sơ";
                    // $phieu->status = 1;
                    // $phieu->student_id = $item->student_id;
                    // $phieu->content = json_encode($data_phieu);
                    // $phieu->save();

                    $check_phieu_khac =  StopStudy::where('parent_id', $item->id)->whereNotNull('phieu_id')->first();
                    if ($check_phieu_khac) {
                        Phieu::find($check_phieu_khac->phieu_id)->delete();
                    }
                    $item->delete();
                }
                StopStudy::where('type', 1)->whereNotNull('phieu_id')->delete();
                StopStudy::where('type', 1)->whereNull('phieu_id')->delete();
            }
            if (isset($request->TCXH)) {
                $query = StopStudy::whereIn('type', [2, 3])
                    ->whereNull('parent_id')->get();

                foreach ($query as $item) {
                    $phieu = Phieu::find($item->phieu_id);
                    if ($phieu) {
                        $phieu->file = $item->files;

                        $phieu->status = 1;
                        $phieu->save();
                    }

                    // $data_phieu[0] = $item->type;
                    // $data_phieu[1]['tiepnhan'] = json_decode($item->tiepnhan);
                    // $data_phieu[1]['ykien'] = json_decode($item->ykien);
                    // $data_phieu[1]['lanhdaophong'] = json_decode($item->lanhdaophong);
                    // $data_phieu[1]['lanhdaotruong'] = json_decode($item->lanhdaotruong);

                    // $phieu = new Phieu();
                    // $phieu->key = "GQCVDHP";
                    // $phieu->name = "Phiếu giải quyết công việc đơn xin rút hồ sơ";
                    // $phieu->status = 1;
                    // $phieu->student_id = $item->student_id;
                    // $phieu->content = json_encode($data_phieu);
                    // $phieu->save();

                    $check_phieu_khac =  StopStudy::where('parent_id', $item->id)->whereNotNull('phieu_id')->first();
                    if ($check_phieu_khac) {
                        Phieu::find($check_phieu_khac->phieu_id)->delete();
                    }
                    $item->delete();
                }
                StopStudy::whereIn('type', [2, 3])->whereNotNull('phieu_id')->delete();
                StopStudy::whereIn('type', [2, 3])->whereNull('phieu_id')->delete();
            }
            if (isset($request->CDCS)) {
                $query = StopStudy::where('type', 4)
                    ->whereNull('parent_id')->get();

                foreach ($query as $item) {
                    $phieu = Phieu::find($item->phieu_id);
                    if ($phieu) {
                        $phieu->file = $item->files;

                        $phieu->status = 1;
                        $phieu->save();
                    }

                    $check_phieu_khac =  StopStudy::where('parent_id', $item->id)->whereNotNull('phieu_id')->first();
                    if ($check_phieu_khac) {
                        Phieu::find($check_phieu_khac->phieu_id)->delete();
                    }
                    $item->delete();
                }
                StopStudy::where('type', 4)->whereNotNull('phieu_id')->delete();
                StopStudy::where('type', 4)->whereNull('phieu_id')->delete();
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => "Có lỗi khi thêm dữ liệu",
                'file' => $e->getFile(),
            ], 500);
        }
        DB::commit();
    }
}
