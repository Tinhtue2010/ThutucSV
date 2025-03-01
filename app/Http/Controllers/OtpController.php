<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtpController extends Controller
{
    function createOtpChuKy() {
        $this->sendOTP();
    }
    function checkOtpChuKy($otp) {
        try {
            return $this->checkOtp($otp) ? "true" : "false";
        } catch (\Throwable $th) {
            return "false";
        }
        
    }
    function checkSignature() {
        $this->getInfoSignature('022201001759');
    }
}
