<?php

namespace App\Services;

use App\Models\UserModel;


class AdminServices
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    // REGISTER USER
    public function register($data)
    {
        $email = $data->getPost('email');
        $existingUser = $this->userModel->where('email', $email)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'Email already exists');
        }
        $data = [
            'name' => $data->getPost('name'),
            'email' => $email,
            'mobile_number' => $data->getPost('mobile_number'),
            'password' =>  $data->getPost('password'),
            'role' => 'user'
        ];
        // email services
        $emailService = \config\Services::email();
        //load email view
        $message = view('registration_mail', ['name' => $data['name']]);

        // email details
        $emailService->setTo($email);
        $emailService->setSubject('Registration Successful');
        $emailService->setMessage($message);
        $emailService->send();
        $this->userModel->insert($data);
        return redirect()->to('/login');
    }
    // GET ALL USERS 
    public function getusers($request, int $page = 1)
    {
        $model = new UserModel();
        $limit = 7;
        $offset = ($page - 1) * $limit;

        // search text
        $search = $request->getGet('search');

        // create builder
        $builder = $model;

        // apply search if exists
        if (!empty($search)) {
            $builder = $builder->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('mobile_number', $search)
                ->orLike('role', $search)
                ->groupEnd();
        }

        // total users
        $totalusers = $builder->countAllResults(false);

        // fetch users
        $users = $builder->findAll($limit, $offset);

        // total pages
        $totalpages = ceil($totalusers / $limit);

        return response()->setJSON([
            'users' => $users,
            'status' => true,
            'totalpages' => $totalpages
        ]);
    }
    // ADD USER
    public function addUser($request)
    {
        $model = new UserModel();
        $data = [
            'name' => $request->getPost('name'),
            'email' => $request->getPost('email'),
            'mobile_number' => $request->getPost('mobile_number'),
            'password' => $request->getPost('password'),
            'role' => $request->getPost('role')
        ];
        $model->insert($data);
        return response()->setJSON([
            'status' => 'success',
            'message' => 'User added successfully'
        ]);
    }

    //UPDATE USER PROFILE
    public function UpdateUser($request, $id)
    {
        $model = new UserModel();
        $data = [
            'name' => $request->getPost('name'),
            'email' => $request->getPost('email'),
            'mobile_number' => $request->getPost('mobile_number'),
            'role' => $request->getPost('role'),
        ];

        if ($request->getPost('password')) {
            $data['password'] = $request->getPost('password');
        }
        $model->update($id, $data);
        return response()->setJSON([
            'status' => 'success',
            'message' => 'user update successfully'
        ]);
    }
}
