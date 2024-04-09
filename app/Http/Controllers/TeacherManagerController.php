<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
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
        $khoas = Khoa::get();
        return view('teacher_manager.index', ['khoas' => $khoas]);
    }

    public function getData(Request $request)
    {
        $query = Teacher::query()
                ->leftJoin("khoas", "teachers.khoa_id", "=", "khoas.id")
                ->select("teachers.*", "khoas.name as khoa_name");

            $query->when(
                $request->has('status_error')
                && $request->status_error !== 'all',
                function ($query) use ($request) {
                    $query->where(
                        $request->status_error === 0 ? 'return_type' : null,
                        $request->status_error === 0 ? null
                            : $request->status_error
                    );
                }
            );
        // if (isset($request->school_year)) {
        //     $query->where('school_year', $request->school_year);
        // }
        // if (isset($request->he_tuyen_sinh)) {
        //     $query->where('he_tuyen_sinh', $request->he_tuyen_sinh);
        // } 
        // if (isset($request->status_dk)) {
        //     $query->where('status_dk', $request->status_dk);
        // }

        $data = $this->queryPagination($request, $query);

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
            $user->username = explode('@', $request->email)[0]; // get the part before @ as username
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
