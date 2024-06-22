<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;

class DocumentController extends Controller
{
    function index()
    {
        return view('document.index');
    }
}
