<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Services\UserServices;

class UserController extends Controller
{
    protected $userService;
    //CONSTRUCTOR 
    function __construct()
    {
        $this->userService = new UserServices();
    }

    // USER DASHBOARD
    public function user()
    {
        return view('user');
    }
    // GET USER PROFILE 
    public function getProfile()
    {
        return $this->userService->getProfile($this->request);
    }
    // UPDATE USER PROFILE
    public function updateProfile()
    {
        return $this->userService->updateProfile($this->request);
    }
}
