<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'mobile_number', 'password', 'role'];

    public function updateNotificationStatus(string $status, int $id)
    {
        $sql = "UPDATE users SET nf_status = '{$status}' WHERE id = '{$id}'";
        return $this->db->query($sql);
    }
    // RETURNING USERS ID 
    public function getUsersId(): array
    {
        $sql = "SELECT id FROM users";
        return $this->db->query($sql)->getResultArray();
    }
}
