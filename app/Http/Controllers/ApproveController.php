<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApproveController extends Controller
{
    function index() {
        return view('approve.index');
    }
}
