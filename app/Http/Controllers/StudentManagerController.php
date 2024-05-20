<?php

namespace App\Http\Controllers;

use App\Models\Lop;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                'sum_point',
                'he_tuyen_sinh',
                'nganh_tuyen_sinh',
                'trinh_do',
                'ngay_nhap_hoc',
                'gv_tiep_nhan',
                'gv_thu_tien',
                'so_tien',
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
            'sum_point',
            'he_tuyen_sinh',
            'nganh_tuyen_sinh',
            'trinh_do',
            'ngay_nhap_hoc',
            'gv_tiep_nhan',
            'gv_thu_tien',
            'so_tien',
            'status',
            'note',
            'gioitinh'
        ]));
    }

    function importFile(Request $request)
    {
        if ($request->hasFile('csv_file')) {
            $data = $this->importCSV($request->file('csv_file'));
            foreach ($data['data'] as $item) {
            }
        }
        return true;
    }
    function status(Request $request) {
        Student::whereIn('id',$request->student)->update(["status"=>$request->status]);
        return 1;
    }
}
