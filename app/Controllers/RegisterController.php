<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Services\UserServices;

class RegisterController extends Controller
{
    protected $userServices;

    public function __construct()
    {
        $this->userServices = new UserServices();
    }

    public function register()
    {
        return view('register');
    }

    public function registerUser()
    {
        return $this->userServices->registerUser($this->request);
    }
}
