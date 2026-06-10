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
        $this->db->query($sql);
        return $this->db->insertId();
    }
    // FETCH ALL NOTIFICATIONS ON USER DASHBOARD
    public function fetchNotifications(int $userId)
    {
        $sql = "SELECT notifications.id, notifications.title, notifications.description
            FROM notifications
            JOIN user_notifications
            ON notifications.id = user_notifications.notifications_id
            WHERE status = '1'
            AND user_notifications.hidden = '0'
            AND user_notifications.user_id = {$userId}
            ORDER BY id DESC";

        return $this->db->query($sql)->getResultArray();
    }
}
