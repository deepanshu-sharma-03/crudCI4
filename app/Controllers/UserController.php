<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UserModel;

class UserController extends Controller
{
    // main page
    public function admin()
    {
        return view('users');
    }
    public function user(){
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
        'totalpages' => $totalpages
    ]);
}

    // store user (insert )

public function store()
{
    $model = new UserModel();
    $data = [
        'name'=>$this->request->getPost('name'),
        'email'=>$this->request->getPost('email'),
        'mobile_number'=>$this->request->getPost('mobile_number'),
        'password'=>$this->request->getPost('password'),
        'role'=>$this->request->getPost('role')
    ];
    $model->insert($data);
    return $this->response->setJSON([
        'status'=>'success',
        'message'=>'User added successfully'
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
    $data = [
        'name'=> $this->request->getPost('name'),
        'email'=>$this->request->getPost('email'),
        'mobile_number'=>$this->request->getPost('mobile_number'),
        'role'=>$this->request->getPost('role'),
    ];

    if($this->request->getPost('password'))
        {
            $data['password'] = $this->request->getPost('password');
        }
        $model->update($id,$data);
        return $this->response->setJSON([
            'status'=>'success',
            'message'=>'user update successfully'
        ]);
}

// deleted user

public function delete($id = null)
{
    $model = new UserModel();
    $model->delete($id);
    return $this->response->setJSON([
        'status'=>'success',
        'message'=>'user deleted successfully'
    ]);
}
}
?>