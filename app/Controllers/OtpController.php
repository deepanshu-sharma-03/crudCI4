<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Services\UserService;

class OtpController extends Controller
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    // SEND OTP
    public function sendOtp()
    {
        return $this->userService->sendOtp($this->request);
    }

    // VERIFY OTP
    public function verifyOtp()
    {
        return $this->userService->verifyOtp($this->request);
    }
}