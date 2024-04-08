<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
// use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
class TeacherManagerController extends Controller
{
    function index()
    {
        $teachers = Teacher::get();
        return view('student_manager.index', ['teachers' => $teachers]);
    }

    public function getData(Request $request)
    {
        $query = Teacher::query();
        if (isset($request->school_year)) {
            $query->where('school_year', $request->school_year);
        }
        if (isset($request->he_tuyen_sinh)) {
            $query->where('he_tuyen_sinh', $request->he_tuyen_sinh);
        } 
        if (isset($request->status_dk)) {
            $query->where('status_dk', $request->status_dk);
        }
        $data = $this->queryPagination($request, $query, ['full_name', 'student_code', 'student_id']);

        return $data;
    }

    public function getDataChild($id)
    {
        try {
            $error = Teacher::findOrFail($id);

            return $error;
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function detele($id)
    {
        try {
            return Teacher::findOrFail($id)->delete();
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function create(Request $request)
    {
        try {
            $teacher =  Teacher::create($request->only([
                'full_name',
                'khoa_id',
                'dia_chi',
                'sdt',
                'email',
                'chuc_danh',
            ]));

            $user = new User();
            $user->name = $request->full_name;
            $user->password = bcrypt($request->student_code);
            $user->teacher_id = $teacher->id;
            $user->save();
            return true;
        } catch (QueryException $e) {
            abort(404);
        }
    }


    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json(['message' => 'Bot not found'], 404);
        }

        return $teacher->update($request->only([
            'full_name',
            'khoa_id',
            'dia_chi',
            'sdt',
            'email',
            'chuc_danh',
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
}
