<?php

namespace App\Controllers;

use App\Services\UserServices;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected $userServices;
    public function __construct()
    {
        $this->userServices =  new UserServices();
    }

    public function login()
    {
        return view('login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->deleteCookie('token');
    }
}
