<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentManagerController extends Controller
{
    function index() {
        return view('student_manager.index');
    }

    public function getData(Request $request)
    {
        $query = Student::query();

        // if (isset($request->status_error)) {
        //     if ($request->status_error != 'all') {
        //         if ($request->status_error == 0) {
        //             $query->whereNull('return_type');
        //         } else {
        //             $query->where('return_type', $request->status_error);
        //         }
        //     }
        // }

        $data = $this->queryPagination($request, $query, []);

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

    function detele($id) {
        try {
            return Student::findOrFail($id)->delete();
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function create(Request $request) {
        
    }
}
