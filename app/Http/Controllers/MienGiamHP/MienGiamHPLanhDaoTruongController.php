<?php

namespace App\Http\Controllers\MienGiamHP;

use App\Http\Controllers\Controller;
use App\Models\HoSo;
use App\Models\Lop;
use App\Models\StopStudy;
use App\Models\Teacher;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MienGiamHPLanhDaoTruongController extends Controller
{
    function index()
    {
        $lop = Lop::get();
        return view('lanh_dao_truong.ds_mien_giam_hp.index', ['lop' => $lop]);
    }

    function getData(Request $request)
    {
        $query = StopStudy::where('type', 1)->studentActive()
            ->whereNull('parent_id')->whereNull('parent_id')
            ->whereNull('parent_id')
            ->leftJoin('students', 'stop_studies.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->select('stop_studies.*', 'students.full_name', 'students.date_of_birth', 'students.student_code', 'lops.name as lop_name');

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

    function xacnhanPDF(Request $request)
    {
        
        try {
            $user = Auth::user();
            $hoso = HoSo::where('type', 2)
                ->latest('created_at')
                ->first();
            if (!isset($hoso)) {
                return 0;
            }

            $teacher = Teacher::where('id',$user->teacher_id)->first();

            $info_signature = $this->getInfoSignature($user->cccd);
            if (!$info_signature) {
                return 0;
            }


            $oldFilename = $hoso->file_quyet_dinh;
            $pathInfo = pathinfo($oldFilename);
            $newFilename = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '-tmp.pdf';

            $this->mergeImageWithTextIntoPdf(
                storage_path('app/public/' . $oldFilename),
                storage_path('app/public/' . $teacher->chu_ky),
                storage_path('app/public/' . $newFilename),
                $teacher->full_name,
                1,
                120,
                260
            );

            return $this->craeteSignature(
                $info_signature,
                $this->convertPdfToBase64($newFilename),
                $user->cccd,
                'QUYET_DINH_MIEN_GIAM_HP_' . $hoso->file_quyet_dinh
            );
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function xacnhan(Request $request)
    {
        DB::beginTransaction();
        try {
            $hoso = HoSo::where('type', 2)
                ->latest('created_at')
                ->first();
            if (!isset($hoso)) {
                return 0;
            }
            $getPDF = $this->getPDF($request->fileId, $request->tranId, $request->transIDHash);
            if (!$getPDF) {
                return 0;
            }

            $file_name = $this->saveBase64AsPdf($getPDF, 'QUYET_DINH_MIEN_GIAM_HP');
            $this->deletePdfAndTmp($hoso->file_quyet_dinh);
            $hoso->update(["file_quyet_dinh" => $file_name]);


            $query = StopStudy::where('type', 1)
                ->whereNull('parent_id')->whereNull('parent_id')->where(function ($query) {
                    $query->where('status', 5)
                        ->orWhere('status', 6)
                        ->orWhere('status', -6);
                })->get();

            foreach ($query as $stopStudy) {
                $this->giaiQuyetCongViec($request->ykientiepnhan, $stopStudy, 4);
                $stopStudy->status = 6;
                $stopStudy->save();
                $newStopStudy = $stopStudy->replicate();
                $newStopStudy->status = 1;
                $newStopStudy->teacher_id = Auth::user()->teacher_id;
                $newStopStudy->file_name = null;
                $newStopStudy->parent_id = $stopStudy->id;
                $newStopStudy->note = "Lãnh đạo trường đã phê duyệt danh sách";
                $newStopStudy->save();
            }
            DB::commit();
            return redirect()->back();
        } catch (QueryException $e) {
            DB::rollBack();
            abort(404);
        }
    }

    function tuchoi()
    {
        $query = StopStudy::where('type', 1)
            ->whereNull('parent_id')->where(function ($query) {
                $query->where('status', 5)
                    ->orWhere('status', 6)
                    ->orWhere('status', -6);
            })->get();
        foreach ($query as $stopStudy) {
            $stopStudy->status = -6;
            $stopStudy->save();
            $newStopStudy = $stopStudy->replicate();
            $newStopStudy->status = 0;
            $newStopStudy->teacher_id = Auth::user()->teacher_id;
            $newStopStudy->phieu_id = null;
            $newStopStudy->parent_id = $stopStudy->id;
            $newStopStudy->note = "Lãnh đạo trường từ chối danh sách";
            $newStopStudy->save();
        }
        return redirect()->back();
    }
}
