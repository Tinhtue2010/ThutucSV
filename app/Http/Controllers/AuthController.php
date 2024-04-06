<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function checkLogin(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            return true;
        }

        $student = Student::where('student_code', $credentials['username'])->first();

        if ($student === null) {
            abort(404);
        }

        $dataLogin = ['student_id' => $student->id, 'password' => $credentials['password']];
        if (Auth::attempt($dataLogin)) {
            return true;
        }
        abort(404);
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login'); 
    }
}
