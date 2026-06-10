<?php

namespace App\Services;

use App\Models\NotificationModel;
use App\Models\UserModel;
use Config\Services;

class UserServices extends Services
{
    protected UserModel $userModel;
    protected NotificationModel $nfModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->nfModel = new NotificationModel();
    }
    public function registerUser(object $request)
    {

        $name = $request->getPost('name');
        $email = $request->getPost('email');
        $mobile = $request->getPost('mobile_number');
        $password = $request->getPost('password');

        $this->userModel->insert([
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
    public function  getProfile(object $request)
    {
        // GET USER ID
        $id = $request->user->id;

        // FIND USER
        $user = $this->userModel->find($id);

        // FETCH NOTIFICATION
        $nfData = $this->nfModel->fetchNotifications($id);

        // RETURN USER
        return response()->setJSON([
            'userData' => $user,
            'nfData' => $nfData,
        ]);
    }
    // UPDATE USER  PROFILE
    function updateProfile(object $request)
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

    // UPDATE NOTIFICATION STATUS
    function updateNotificationStatus(object $request)
    {
        $usernfStatus = $request->getPost('nfstatus');
        $id = $request->user->id;
        if ($usernfStatus === 'true') {
            $usernfStatus = '1';
        } else {
            $usernfStatus = '0';
        }
        $result = $this->userModel->updateNotificationStatus($usernfStatus, $id);
        return response()->setJSON([
            'result' => $result
        ]);
    }
}
