<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    // FETCH NOTIFICATIONS ON ADMIN DASHBOARD
    public function getNotification()
    {
        $data = "SELECT * FROM notifications";
        return $this->db->query($data)->getResultArray();
    }

    // UPDATE NOTIFICATION STATUS IN ADMIN DASHBOARD
    public function updateStatus(int $id, int $status)
    {
        // $sql = "UPDATE `notifications` SET `status` = '{$status}' WHERE `notifications`.`id` = {$id};";
        $sql = "UPDATE notifications SET status = '{$status}' WHERE id={$id}";
        return $this->db->query($sql);
    }
    // SAVE NOTIFICATION
    public function saveNotification(String $title, String $description, String $status)
    {
        $sql = "INSERT INTO `notifications` ( `title`, `description`, `status`, `created_at`, `updated_at`) VALUES ( '$title', '$description', '$status', CURRENT_TIME(), CURRENT_TIME())";
        return $this->db->query($sql);
    }
    // FETCH ALL NOTIFICATIONS ON USER DASHBOARD
    public function fetchNotifications()
    {
        $sql = "SELECT * FROM notifications WHERE status = '1' ORDER BY id DESC";
        return $this->db->query($sql)->getResultArray();
    }
}
