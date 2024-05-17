<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    function index(Request $request)
    {
        $user = Auth::user();
        $query = Notification::where('user_id', $user->id);
        $data = $this->queryPagination($request, $query, []);
        return view('notification.index', ['data' => $data]);
    }

    function readAll()
    {
        $user = Auth::user();
        $query = Notification::where('user_id', $user->id)->update(['status' => 1]);
        return redirect()->back();
    }

    function viewNotifi($type)
    {
        if (Auth::user()->role == 1) {
            switch ($type) {
                case 'RHS':
                    return redirect()->route('StopStudy.index');
                    break;
                case 'GHP':
                    return redirect()->route('MienGiamHp.index');
                    break;
                case 'TCXH':
                    return redirect()->route('TroCapXH.index');
                    break;
                case 'CDCS':
                    return redirect()->route('CheDoChinhSach.index');
                    break;
                default:
                    return redirect()->route('notification.index');
            }
        } else {
            switch (Auth::user()->role) {
                case 2:
                    return redirect()->route('GiaoVien.index');
                    break;
                case 3:
                    return redirect()->route('Khoa.index');
                    break;
                case 4:
                    return redirect()->route('PhongDaoTao.index');
                    break;
                case 5:
                    return redirect()->route('KeHoachTaiChinh.index');
                    break;
                case 6:
                    return redirect()->route('LanhDaoPhongDaoTao.index');
                    break;
                case 6:
                    return redirect()->route('LanhDaoPhongDaoTao.index');
                    break;
                case 7:
                    return redirect()->route('LanhDaoTruong.index');
                    break;
                default:
                    return redirect()->route('notification.index');
                    break;
            }
        }
    }
}
