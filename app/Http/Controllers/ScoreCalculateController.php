<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
use App\Models\Lop;
use App\Models\Score;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DanhSachDiemHTCaoNhat;
use App\Models\Nganhs;
use App\Models\StopStudy;

class ScoreCalculateController extends Controller
{
    function index(Request $request)
    {
        $user = Auth::user();
        $lops = Lop::get();
        if ($user->role == 2 || $user->role == 3) {
            $lops = Lop::where('teacher_id', $user->teacher_id)->get();
        }
        $nganhs = Nganhs::whereIn('manganh', [
            "7810103",
            "7810201",
            "7810202",
            "7220209",
            "7220210",
            "7220204",
            "7620301",
        ])->get();
        $khoas = Khoa::get();
        $ky_hoc = $request->ky;
        $nam_hoc = $request->nam;
        $topPercent = $request->topPercent;
        return view('score_calculate.index', [
            'lops' => $lops,
            'khoas' => $khoas,
            'ky_hoc_goc' => $ky_hoc,
            'nam_hoc_goc' => $nam_hoc,
            'topPercent' => $topPercent,
            'nganhs' => $nganhs,
        ]);
    }

    public function getData(Request $request)
    {

        $topScoresQuery = $this->danhSachDiemHTCao($request->nam_hoc_goc, $request->ky_hoc_goc, $request->topPercent, $request->so_luong_sv_them ?? 0);
        if (isset($request->id_da_xoa)) {
            $topScoresQuery->whereNotIn('student_id', explode(',', $request->id_da_xoa));
        }
        if (isset($request->khoa_hoc)) {
            $topScoresQuery->where('khoa_hoc', $request->khoa_hoc);
        }
        if (isset($request->nganh_id)) {
            $topScoresQuery->where('nganh_id', $request->nganh_id);
        }

        $data = $this->queryPagination($request, $topScoresQuery, ['full_name', 'student_code']);

        return $data;
    }
    public function downloadDanhSach(Request $request)
    {
        $data = $this->danhSachDiemHTCao($request->nam_hoc, $request->ky_hoc, $request->topPercent, $request->so_luong_sv_them ?? 0);
        $data->whereNotIn('student_id', explode(',', $request->id_da_xoa));
        $data = $data->orderBy('diem_ht', 'desc')->get();
        $result = [];
        foreach ($data as $item) {
            $result[] = [
                $item->student_code,
                $item->full_name,
                $item->lop_name,
                $item->khoa_name,
                $item->ky_hoc,
                $item->nam_hoc,
                $item->diem_ht,
                $item->xep_loai_ht,
                $item->diem_rl,
                $item->xep_loai_rl,
                $item->xep_loai,
                $item->so_tc_ht,
            ];
        }
        $fileName = $this->sanitizeFileName('Danh sách ' . $request->topPercent . '% các sinh viên có điểm học tập cao nhất.xlsx');
        return Excel::download(new DanhSachDiemHTCaoNhat($result, $request->topPercent), $fileName);
    }
    public function updateDT3(Request $request)
    {

        $sinhViens = StopStudy::where('type', 4)
            ->where('nam_hoc', $request->nam_hoc)
            ->where('ky_hoc', $request->ky_hoc)
            ->where('doi_tuong_chinh_sach', '["3"]')
            ->where('status', '>', 0)
            ->pluck('student_id');

        StopStudy::where('type', 4)
            ->where('nam_hoc', $request->nam_hoc)
            ->where('ky_hoc', $request->ky_hoc)
            ->where('doi_tuong_chinh_sach', '["3"]')
            ->where('status', 0)
            ->delete();

        $data = $this->danhSachDiemHTCao($request->nam_hoc, $request->ky_hoc, $request->topPercent, $request->so_luong_sv_them ?? 0);
        $data->whereNotIn('student_id', explode(',', $request->id_da_xoa));
        $data->whereNotIn('student_id', $sinhViens);
        $data = $data->get();

        foreach ($data as $item) {
            $json = [
                "diem" => [
                    "diemtb" => $item->diem_ht,
                    "diemrenluyen" => $item->diem_rl,
                ]
            ];

            $jsonData = json_encode($json);

            StopStudy::create([
                'student_id' => $item->student_id,
                'type' => 4,
                'doi_tuong_chinh_sach' => '["3"]',
                'nam_hoc' => $request->nam_hoc,
                'ky_hoc' => $request->ky_hoc,
                'name' => 'Hồ sơ chế độ chính sách theo NQ 35',
                'round' => 1,
                'ma_lop' => $item->ma_lop,
                'phan_tram_giam' => 100,
                'status' => 0,
                'che_do_chinh_sach_data' => $jsonData,
            ]);
        }
        return redirect()->back()->with('success', 'Cập nhật thành công');
    }

    function findNewAddedStudent(Request $request)
    {

        $nam_hoc = $request->nam_hoc;
        $ky_hoc = $request->ky_hoc;
        $topPercent = $request->topPercent;
        $so_luong_sv_them = $request->so_luong_sv_them ?? 0;

        $topCount = $this->getTopCont($nam_hoc, $ky_hoc, $topPercent, $so_luong_sv_them) - 1;

        $originalTopScoreIds = Score::where('nam_hoc', $nam_hoc)
            ->where('ky_hoc', $ky_hoc)
            ->where('diem_ht', '>=', 7)
            ->orderBy('diem_ht', 'desc')
            ->take($topCount)
            ->pluck('id');

        $topCount = $topCount + 1;

        $newTopScoreIds = Score::where('nam_hoc', $nam_hoc)
            ->where('ky_hoc', $ky_hoc)
            ->where('diem_ht', '>=', 7)
            ->orderBy('diem_ht', 'desc')
            ->take($topCount)
            ->pluck('id');

        $newlyAddedId = $newTopScoreIds->diff($originalTopScoreIds)->first();
        $score = Score::join('students', 'scores.student_id', '=', 'students.id')
            ->where('scores.id', $newlyAddedId)
            ->first();
        return response()->json([
            'student' => [
                'full_name' => '' . $score->full_name,
                'diem_ht' => '' . $score->diem_ht,
                'diem_rl' => '' . $score->diem_rl,
                'student_code' => '' . $score->student_code,
            ]
        ]);
    }
    function danhSachDiemHTCao($nam_hoc_goc, $ky_hoc_goc, $topPercent, $so_luong_sv_them = 0)
    {
        $list_nganh = [
            "7810103",
            "7810201",
            "7810202",
            "7220209",
            "7220210",
            "7220204",
            "7620301",
        ];
        $scoresQuery = Score::leftJoin('students', 'scores.student_id', '=', 'students.id')
            ->where('scores.nam_hoc', $nam_hoc_goc)
            ->where('scores.ky_hoc', $ky_hoc_goc)
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->leftJoin('khoas', 'lops.ma_khoa', '=', 'khoas.ma_khoa')
            ->whereIn('lops.nganh_id', $list_nganh)
            ->select('scores.*', 'students.full_name', 'students.student_code', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->orderBy('scores.diem_ht', 'desc')
            ->get();

        $topPercent = $topPercent * 0.01;
        $topCount = ceil($scoresQuery->count() * $topPercent) + $so_luong_sv_them;

        $topScoreIds = Score::where('nam_hoc', $nam_hoc_goc)
            ->where('ky_hoc', $ky_hoc_goc)
            ->where('diem_ht', '>=', 7)
            ->orderBy('diem_ht', 'desc')
            ->take($topCount)
            ->pluck('id');

        $topScoresQueryWithSubquery = Score::leftJoin('students', 'scores.student_id', '=', 'students.id')
            ->whereIn('scores.id', $topScoreIds)
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->leftJoin('khoas', 'lops.ma_khoa', '=', 'khoas.ma_khoa')
            ->select(
                'scores.*',
                'students.full_name',
                'students.student_code',
                'lops.name as lop_name',
                'khoas.name as khoa_name',
                'lops.nganh_id',
                'students.id as student_id',
                DB::raw("(SELECT GROUP_CONCAT(type) FROM stop_studies
           WHERE stop_studies.student_id = scores.student_id
           AND stop_studies.nam_hoc = '{$nam_hoc_goc}'
           AND stop_studies.ky_hoc = {$ky_hoc_goc}) as stop_study_types")
            )
            ->orderBy('scores.diem_ht', 'desc');
        return $topScoresQueryWithSubquery;
    }

    function getTopCont($nam_hoc_goc, $ky_hoc_goc, $topPercent, $so_luong_sv_them = 0)
    {
        $list_nganh = [
            "7810103",
            "7810201",
            "7810202",
            "7220209",
            "7220210",
            "7220204",
            "7620301",
        ];
        $scoresQuery = Score::leftJoin('students', 'scores.student_id', '=', 'students.id')
            ->where('scores.nam_hoc', $nam_hoc_goc)
            ->where('scores.ky_hoc', $ky_hoc_goc)
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->leftJoin('khoas', 'lops.ma_khoa', '=', 'khoas.ma_khoa')
            ->whereIn('lops.nganh_id', $list_nganh)
            ->select('scores.*', 'students.full_name', 'students.student_code', 'lops.name as lop_name', 'khoas.name as khoa_name')
            ->orderBy('scores.diem_ht', 'desc')
            ->get();

        $topPercent = $topPercent * 0.01;
        $topCount = ceil($scoresQuery->count() * $topPercent) + $so_luong_sv_them;
        return $topCount;
    }

    function status(Request $request)
    {
        Student::whereIn('id', $request->student)->update(["status" => $request->status]);
        return 1;
    }
}
