<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Services\LoginService;

class OtpController extends Controller
{
    protected $loginServices;

    public function __construct()
    {
        $this->loginServices = new LoginService();
    }

    // SEND OTP
    public function sendOtp()
    {
        return $this->loginServices->sendOtp($this->request);
    }

    // VERIFY OTP
    public function verifyOtp()
    {
        return $this->loginServices->verifyOtp($this->request);
    }
}
