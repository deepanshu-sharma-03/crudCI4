<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Services\UserService;

class RegisterController extends Controller
{
    protected $userService;

    public function __construct()
    {
        $this->userService =
            new UserService();
    }

    public function register()
    {
        // echo "heelo inside php";
        return view('register');
    }

    public function registerUser()
    {
        return $this->userService
            ->register(
                $this->request
            );
    }
}