<?php

namespace App\Services;

use App\Models\UserModel;
use Config\Services;

class UserServices extends Services
{
    public function registerUser($request)
    {
        $userModel = new UserModel();
        $name = $request->getPost('name');
        $email = $request->getPost('email');
        $mobile = $request->getPost('mobile_number');
        $password = $request->getPost('password');

        $userModel->insert([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'mobile_number' => $mobile
        ]);
        // EMAIL SERVICE

        $emailService = service('email');

        $emailService->setTo($email);

        $emailService->setSubject(
            'Registration Successful'
        );

        // LOAD EMAIL VIEW
        $message = view(
            'registration_mail',
            ['name' => $name]
        );

        $emailService->setMessage($message);

        if (!$emailService->send()) {
            echo $emailService->printDebugger();
            die;
        }
        return redirect()->to(base_url('/login'));
    }

    // GET USER PROFILE
    public function  getProfile($request)
    {
        // GET USER ID
        $id = $request->user->id;

        // FIND USER
        $model = new UserModel();
        $user = $model->find($id);

        // RETURN USER
        return response()->setJSON($user);
    }
    // UPDATE USER  PROFILE
    function updateProfile($request)
    {
        $id = $request->user->id;
        $model = new UserModel();
        $data = [
            'name' => $request->getPost('name'),
            'email' => $request->getPost('email'),
            'password' => $request->getPost('password'),
            'mobile_number' => $request->getPost('mobile_number')
        ];
        $model->update($id, $data);
        return response()->setJSON([
            'status' => 'success',
            'message' => 'profile updated succcessfully'
        ]);
    }
}
