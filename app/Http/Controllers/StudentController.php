<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    function index() {
        $user = Auth::user();
        $student = Student::leftJoin('lops','students.lop_id','=','lops.id')
        ->leftJoin('khoas','lops.khoa_id','=','khoas.id')
        ->select('students.*','lops.name as lop_name','khoas.name as khoa_name')
        ->where('students.id',$user->student_id)->first();
        $studentArray = $student->toArray();
        $totalFields = count($studentArray);
        $nonNullFields = count(array_filter($studentArray));
        
        $percentage = intval(($nonNullFields / $totalFields) * 100);      
        
        return view('student.index', ['percent'=>$percentage,'student'=>$student]);
    }

    public function getDataInfo()
    {
        $user = Auth::user();
        try {
            $error = Student::findOrFail($user->student_id);

            return $error;
        } catch (QueryException $e) {
            abort(404);
        }
    }

    public function update(Request $request) {
        $user = Auth::user();
        $student = Student::find($user->student_id);

        return $student->update($request->only([
            'full_name',
            'date_of_birth',
            'phone',
            'email',
            'cmnd',
            'date_range_cmnd'
        ]));
    }
}
