<?php

namespace App\Controllers;

use App\Services\UserService;
use CodeIgniter\Controller;

class AuthController extends Controller
{

        protected $userService;

        public function __construct()
        {
            $this->userService =  new UserService();
        }

    public function login()
    {
        return view('login');
    }

    public function logout()
{
    session()->destroy();

    return redirect()

        ->to(base_url('login'))

        ->deleteCookie('token');
}
}