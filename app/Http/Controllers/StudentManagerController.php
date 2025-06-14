<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
use App\Models\Lop;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentManagerController extends Controller
{
    function index()
    {
        $user = Auth::user();
        $lops = Lop::get();
        if ($user->role == 2 || $user->role == 3) {
            $lops = Lop::where('teacher_id', $user->teacher_id)->get();
        }
        $khoas = Khoa::get();
        return view('student_manager.index', ['lops' => $lops, 'khoas' => $khoas,]);
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('id', $user->teacher_id)->first();
        $lopIds = Lop::where('teacher_id', $user->teacher_id)->pluck('ma_lop');
        $query = Student::leftJoin('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->leftJoin('khoas', 'lops.ma_khoa', '=', 'khoas.ma_khoa')
            ->leftJoin("nganhs", "lops.nganh_id", "=", "nganhs.manganh")
            ->select('students.*', 'khoas.name as khoa_name', 'nganhs.hedaotao', 'lops.ma_khoa', 'lops.name as lop_name', 'khoas.name as khoa_name');
        if (Role(2) || Role(3)) {
            if (isset($request->khoa)) {
                $query = $query->where('lops.ma_khoa', $teacher->ma_khoa);
            } elseif (isset($request->gvcn)) {
                $query = $query->whereIn('students.ma_lop', $lopIds);
            } else {
                $query = $query->whereIn('students.ma_lop', $lopIds);
            }
        }
        if (isset($request->nien_khoa)) {
            $query->where('khoa_hoc', $request->nien_khoa);
        }
        if (isset($request->status)) {
            $query->where('status', $request->status);
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

    function importFile(Request $request)
    {
        set_time_limit(3600);
        if ($request->hasFile('csv_file')) {
            $data = $this->importCSV($request->file('csv_file'));
            $header = [
                "ma_hs" => "student_id",
                "ma_sinh_vien" => "student_code",
                "ho_ten" => "full_name",
                "ngay_sinh" => "date_of_birth",
                "so_dt_ca_nhan" => "phone",
                "email" => "email",
                "ten_lop" => "lop_id",
                "lien_khoa" => "nien_khoa",
                "ngay_nhap_hoc" => "ngay_nhap_hoc",
                "ghi_chu_ho_so" => "note",
                "can_cuoc" => "cmnd",
                "ngay_cap_can_cuoc" => "date_range_cmnd",
            ];
            DB::beginTransaction();
            try {
                foreach ($data['data'] as $index => $item) {
                    $student = new Student();
                    foreach ($data['header'] as $index_header => $item_header) {
                        if (!isset($header[$data['header'][$index_header]])) {
                            continue;
                        }
                        $columnName = $header[$data['header'][$index_header]];
                        if ($data['header'][$index_header] == 'ten_lop') {
                            $lop = Lop::where('name', 'like', '%' . $item[$index_header] . '%')->first();
                            if ($lop) {
                                $student->lop_id = $lop->id;
                            } else {
                                throw new \Exception("Không tìm thấy lớp với tên: " . $item[$index_header]);
                            }
                        } else {
                            if ($columnName == "phone") {
                                $student->$columnName = '0' . $item[$index_header];
                            } else if ($columnName == "date_of_birth" || $columnName == "ngay_nhap_hoc" || $columnName == "date_range_cmnd") {
                                $student->$columnName = $this->convertDate('m/d/Y', $item[$index_header]);
                            } else if ($columnName == "gioitinh") {
                                if ($this->convertVietnamese($item[$index_header]) == 'nu') {
                                    $student->$columnName = 0;
                                }
                                if ($this->convertVietnamese($item[$index_header]) == 'nam') {
                                    $student->$columnName = 1;
                                }
                            } else if ($columnName == "email" && filter_var($item[$index_header], FILTER_VALIDATE_EMAIL)) {
                                $student->email = $item[$index_header];
                            } else {
                                $student->$columnName = $item[$index_header];
                            }
                        }
                    }
                    $student->save();

                    $user = new User();

                    $user->name = $student->full_name;
                    $user->username = $student->student_code;
                    $user->password = bcrypt($student->student_code);
                    $user->student_id = $student->id;

                    $user->save();
                }
            } catch (\Exception $e) {
                DB::rollback();
                \Log::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
                // Show the error message to the user
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
    function khoaHocLop($id)
    {
        if (!isset($id)) {
            return abort(404);
        }
        $khoaHocs = Student::join('lops', 'students.ma_lop', '=', 'lops.ma_lop')
            ->when($id !== 'all', function ($query) use ($id) {
                return $query->where('lops.id', $id);
            })
            ->pluck('khoa_hoc')
            ->unique()
            ->values();

        return $khoaHocs;
    }

    function lopKhoaHoc($id)
    {
        if (!isset($id)) {
            return abort(404);
        }
        $lops = Lop::join('students', 'lops.ma_lop', '=', 'students.ma_lop')
            ->when($id !== "all", function ($query) use ($id) {
                return $query->where('students.khoa_hoc', $id);
            })
            ->select('lops.*')
            ->distinct()
            ->get();

        return $lops;
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
