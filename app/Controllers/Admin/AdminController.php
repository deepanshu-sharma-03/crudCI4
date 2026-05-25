<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\AdminServices;
use App\Models\UserModel;

class AdminController extends BaseController
{
    protected $adminServices;
    function __construct()
    {
        $this->adminServices = new AdminServices();
    }
    // ADMIN DASHBOARD
    public function admin()
    {
        return view('users');
    }
    // REGISTER USER
    public function register()
    {
        return $this->adminServices->register($this->request);
    }
    // GET ALL USERS
    public function getUsers(int $pages)
    {
        return $this->adminServices->getUsers($this->request, $pages);
    }
    // ADD USER THROUGH ADMIN PANEL
    public function addUser()
    {
        return $this->adminServices->addUser($this->request);
    }
    // GET USER FOR EDIT
    public function getUser($id = null)
    {
        $model = new UserModel();
        $data = $model->find($id);
        return $this->response->setJSON($data);
    }

    // UPDATE USER 
    public function updateUser(int $id)
    {
        return $this->adminServices->updateUser($this->request, $id);
    }

    // DELETE USER FROM ADMIN PANEL

    public function delete(int $id)
    {
        try {

            $model = new UserModel();

            $model->delete($id);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Deleted'
            ]);
        } catch (\Exception $e) {

            return $this->response->setJSON([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
