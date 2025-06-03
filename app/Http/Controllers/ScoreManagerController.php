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

class ScoreManagerController extends Controller
{
    function index()
    {
        $user = Auth::user();
        $lops = Lop::get();
        if ($user->role == 2 || $user->role == 3) {
            $lops = Lop::where('teacher_id', $user->teacher_id)->get();
        }
        $khoas = Khoa::get();
        return view('score_manager.index', ['lops' => $lops, 'khoas' => $khoas,]);
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('id', $user->teacher_id)->first();
        $lopIds = Lop::where('teacher_id', $user->teacher_id)->pluck('ma_lop');
        $query = Score::leftJoin('students', 'scores.student_id', '=', 'students.id')
            ->leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->leftJoin('khoas', 'lops.ma_khoa', '=', 'khoas.ma_khoa')
            ->select('scores.*', 'students.full_name', 'students.student_code', 'lops.name as lop_name', 'khoas.name as khoa_name');

        if (Role(2) || Role(3)) {
            if (isset($request->khoa)) {
                $query = $query->where('lops.ma_khoa', $teacher->ma_khoa);
            } elseif (isset($request->gvcn)) {
                $query = $query->whereIn('students.ma_lop', $lopIds);
            } else {
                $query = $query->whereIn('students.ma_lop', $lopIds);
            }
        }

        if (isset($request->khoa_hoc)) {
            $query->where('khoa_hoc', $request->khoa_hoc);
        }
        if (isset($request->ky_hoc)) {
            $query->where('ky_hoc', $request->ky_hoc);
        }
        if (isset($request->nam_hoc)) {
            $query->where('nam_hoc', $request->nam_hoc);
        }
        if (isset($request->lop_id)) {
            $query->where('students.ma_lop', $request->lop_id);
        }
        $data = $this->queryPagination($request, $query, ['full_name', 'student_code']);

        return $data;
    }

    public function getDataChild($id)
    {
        try {
            $error = Student::leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
                ->leftJoin('khoas', 'lops.ma_khoa', '=', 'khoas.ma_khoa')
                ->select('students.*', 'lops.*', 'khoas.id as khoa_id', 'lops.id as lop_id')
                ->findOrFail($id);

            return $error;
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function detele($id)
    {
        try {
            User::where('student_id', $id)->delete();
            return Student::findOrFail($id)->delete();
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function create(Request $request)
    {
        try {
            $student =  Student::create($request->only([
                'full_name',
                'student_code',
                'date_of_birth',
                'phone',
                'lop_id',
                'nien_khoa',
                'ngay_nhap_hoc',
                'status',
                'note',
                'gioitinh'
            ]));

            $user = new User();

            $user->name = $request->full_name;
            $user->username = $request->student_code;
            $user->password = bcrypt($request->student_code);
            $user->student_id = $student->id;

            $user->save();
            return true;
        } catch (QueryException $e) {
            abort(404);
        }
    }


    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Bot not found'], 404);
        }

        return $student->update($request->only([
            'full_name',
            'student_code',
            'student_id',
            'date_of_birth',
            'phone',
            'email',
            'lop_id',
            'nien_khoa',
            'he_tuyen_sinh',
            'nganh_tuyen_sinh',
            'trinh_do',
            'ngay_nhap_hoc',
            'status',
            'note',
            'gioitinh'
        ]));
    }
    public function convertVietnamese($str)
    {
        $str = preg_replace([
            '/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/u',
            '/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/u',
            '/(ì|í|ị|ỉ|ĩ)/u',
            '/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/u',
            '/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/u',
            '/(ỳ|ý|ỵ|ỷ|ỹ)/u',
            '/(đ)/u',
            '/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/u',
            '/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/u',
            '/(Ì|Í|Ị|Ỉ|Ĩ)/u',
            '/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/u',
            '/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/u',
            '/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/u',
            '/(Đ)/u',
        ], [
            'a',
            'e',
            'i',
            'o',
            'u',
            'y',
            'd',
            'A',
            'E',
            'I',
            'O',
            'U',
            'Y',
            'D',
        ], $str);
        return $str;
    }
    public function importCSV2($file)
    {
        $path = $file->getRealPath();
        $file = fopen($path, 'r');
        $header = [];
        $datas = [];

        while (($row = fgetcsv($file, 9000000, ',')) !== false) {
            if (empty(array_filter($row))) continue; // skip empty rows

            if (empty($header)) {
                // Validate that every header item is a string
                $header = array_map(function ($value) {
                    if (is_array($value)) {
                        throw new \Exception("Header value is unexpectedly an array.");
                    }
                    return strtolower(trim($this->convertVietnamese($value)));
                }, $row);
            } else {
                $tmpData = array_filter($row, fn($value) => $value !== "");
                if (!empty($tmpData)) {
                    $datas[] = $row;
                } else {
                    break;
                }
            }
        }

        fclose($file);

        return ['header' => $header, 'data' => $datas];
    }
    function importFile(Request $request)
    {
        set_time_limit(36000);
        if ($request->hasFile('csv_file')) {
            $data = $this->importCSV2($request->file('csv_file'));

            $header = [
                "ma sinh vien" => "student_code",
                "diem ht" => "diem_ht",
                "xep loai ht" => "xep_loai_ht",
                "diem rl" => "diem_rl",
                "xep loai rl" => "xep_loai_rl",
                "xep loai" => "xep_loai",
                "so.tc ht" => "so_tc_ht",
            ];

            DB::beginTransaction();
            try {
                foreach ($data['data'] as $index => $item) {
                    $score = new Score();
                    $hasStudent = false;

                    foreach ($data['header'] as $index_header => $item_header) {
                        if (!isset($header[$data['header'][$index_header]])) {
                            continue;
                        }

                        $columnName = $header[$data['header'][$index_header]];

                        if ($columnName == 'student_code') {
                            $student = Student::where('student_code', $item[$index_header])->first();
                            if (!$student) {
                                $hasStudent = false;
                                break;
                            }
                            $score->student_id = $student->id;
                            $hasStudent = true;
                        } else {
                            $score->$columnName = $item[$index_header];
                        }
                    }

                    if ($hasStudent) {
                        $score->save();
                    }
                }
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
                return response()->json([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ], 500);
            }

            DB::commit();


            return true;
        }
        abort(404);
        return true;
    }
    function status(Request $request)
    {
        Student::whereIn('id', $request->student)->update(["status" => $request->status]);
        return 1;
    }

    function resetPass($id)
    {
        try {
            $student = Student::find($id);

            User::where('student_id', $id)->update(["password" => bcrypt($student->student_code)]);
            return true;
        } catch (QueryException $e) {
            abort(404);
        }
    }
}
