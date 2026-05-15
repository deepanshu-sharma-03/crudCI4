<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController extends Controller
{
    // main page
    public function admin()
    {
        return view('users');
    }
    public function user()
    {
        return view('user');
    }

    // get all users 
    public function getusers($page = 1)
    {
        $model = new UserModel();

        $limit = 5;

        $offset = ($page - 1) * $limit;

        // search text
        $search = $this->request->getGet('search');

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

        return $this->response->setJSON([
            'users' => $users,
            'status' => true,
            'totalpages' => $totalpages
        ]);
    }

    // store user (insert )

    public function store()
    {
        $model = new UserModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'mobile_number' => $this->request->getPost('mobile_number'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role')
        ];
        $model->insert($data);
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'User added successfully'
        ]);
    }

    // get user (edit )

    public function getUser($id = null)
    {
        $model = new UserModel();
        $data = $model->find($id);
        return $this->response->setJSON($data);
    }

    // update the user

    public function update($id = null)
    {
        $model = new UserModel();
        $id = $this->request->getPost('name');
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'mobile_number' => $this->request->getPost('mobile_number'),
            'role' => $this->request->getPost('role'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }
        dd($id);
        die;
        $model->update($id, $data);
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'user update successfully'
        ]);
    }

    // deleted user

    public function delete($id = null)
    {
        $model = new UserModel();
        $model->delete($id);
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'user deleted successfully'
        ]);
    }

    // get Profile


    public function getProfile()
    {
        // GET TOKEN FROM COOKIE
        $token =
            $this->request->getCookie(
                'token'
            );

        // DECODE TOKEN
        $decoded =
            JWT::decode(

                $token,

                new Key(

                    getenv(
                        'JWT_SECRET_KEY'
                    ),

                    'HS256'
                )
            );

        // GET USER ID
        $id =
            $decoded->data->id;

        // FIND USER
        $model =
            new UserModel();

        $user =
            $model->find($id);

        // RETURN USER
        return $this->response
            ->setJSON($user);
    }
    // Update Profile
    public function updateProfile()
    {
        $model = new UserModel();
        $id  = session()->get('id');
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'mobile_number' => $this->request->getPost('mobile_number')
        ];
        $model->update($id, $data);
        $updateduser = $model->find($id);
        session()->set([
            'name' => $updateduser['name'],
            'email' => $updateduser['email'],
            'password' => $updateduser['password'],
            'mobile_number' => $updateduser['mobile_number']
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'profile updated succcessfully'
        ]);
    }
}
