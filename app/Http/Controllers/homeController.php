<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class homeController extends Controller
{
    public function index()
    {
        dd(Role('student'));
        dd(Auth::user());
    }
}
