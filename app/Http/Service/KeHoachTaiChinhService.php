<?php

namespace App\Http\Service;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class KeHoachTaiChinhService  extends Controller
{
    function xacnhanRHS($stopStudy)
    {
        if ($stopStudy->status != 2 && $stopStudy->status != -3) {
            abort(404);
        }
        $stopStudy->update(["is_pay" => 1,"note_pay"=>""]);

        return true;
    }
    function khongxacnhanRHS($request, $stopStudy)
    {
        if ($stopStudy->status != 2 && $stopStudy->status != -3) {
            abort(404);
        }
        $stopStudy->update(["is_pay" => 2,"note_pay"=>$request->note]);

    }
}
