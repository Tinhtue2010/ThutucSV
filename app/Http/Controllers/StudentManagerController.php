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
        $lops = Lop::get();
        return view('student_manager.index', ['lops' => $lops]);
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('id', $user->teacher_id)->first();
        $lopIds = Lop::where('teacher_id', $user->teacher_id)->pluck('id');
        $query = Student::leftJoin('lops', 'students.lop_id', '=', 'lops.id')
            ->leftJoin('khoas', 'lops.khoa_id', '=', 'khoas.id')
            ->select('students.*', 'lops.khoa_id', 'lops.name as lop_name', 'khoas.name as khoa_name');
        if (Role(2) || Role(3)) {
            if (isset($request->khoa)) {
                $query = $query->where('lops.khoa_id', $teacher->khoa_id);
            } elseif (isset($request->gvcn)) {
                $query = $query->whereIn('lop_id', $lopIds);
            } else {
                $query = $query->whereIn('lop_id', $lopIds);
            }
        }
        if (isset($request->school_year)) {
            $query->where('school_year', $request->school_year);
        }
        if (isset($request->he_tuyen_sinh)) {
            $query->where('he_tuyen_sinh', $request->he_tuyen_sinh);
        }
        if (isset($request->status_dk)) {
            $query->where('status_dk', $request->status_dk);
        }
        if (isset($request->lop_id)) {
            $query->where('lop_id', $request->lop_id);
        }
        $data = $this->queryPagination($request, $query, ['full_name', 'student_code', 'student_id']);

        return $data;
    }

    public function getDataChild($id)
    {
        try {
            $error = Student::findOrFail($id);

            return $error;
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function detele($id)
    {
        try {
            User::where('student_id',$id)->delete();
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
                'student_id',
                'date_of_birth',
                'phone',
                'email',
                'lop_id',
                'school_year',
                'he_tuyen_sinh',
                'nganh_tuyen_sinh',
                'trinh_do',
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
            'school_year',
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
        set_time_limit(900);
        if ($request->hasFile('csv_file')) {
            $data = $this->importCSV($request->file('csv_file'));
            $header = [
                "ma_hs" => "student_id",
                "ma_sinh_vien" => "student_code",
                "ho_ten" => "full_name",
                "ngay_sinh" => "date_of_birth",
                "gioi_tinh" => "gioitinh",
                "so_dt_ca_nhan" => "phone",
                "email" => "email",
                "ten_lop" => "lop_id",
                "he_tuyen_sinh" => "he_tuyen_sinh",
                "nganh_tuyen_sinh" => "nganh_tuyen_sinh",
                "trinh_do_van_hoa" => "trinh_do",
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
                        }
                        else if($columnName == "date_of_birth" || $columnName == "ngay_nhap_hoc" || $columnName == "date_range_cmnd")
                        {
                            $student->$columnName = $this->convertDate('m/d/Y',$item[$index_header]);
                        }
                        else if($columnName == "gioitinh")
                        {
                            if($this->convertVietnamese($item[$index_header]) == 'nu'){
                                $student->$columnName = 0;
                            }
                            if($this->convertVietnamese($item[$index_header]) == 'nam'){
                                $student->$columnName = 1;
                            }
                        }
                        else if($columnName == "email" && filter_var($item[$index_header], FILTER_VALIDATE_EMAIL))
                        {                                
                            $student->email = $item[$index_header];
                        }
                        else {
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
            } catch (\Throwable $th) {
                DB::rollback();
                abort(404);
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
            
            User::where('student_id',$id)->update(["password"=>bcrypt($student->student_code)]);
            return true;
        } catch (QueryException $e) {
            abort(404);
        }
    }
}
