<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationCenterController extends Controller
{
    function index()  {
        return view('notificationcenter.index');
    }
}
