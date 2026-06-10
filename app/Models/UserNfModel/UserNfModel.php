<?php

namespace App\Models\UserNfModel;

use CodeIgniter\Model;

class UserNfModel extends Model
{
    // SAVE NOTIFICATION IN ALL USERS 
    public function saveNotifications(array $data)
    {
        $values = [];
        foreach ($data as $item) {
            $values[] = "('{$item['user_id']}','{$item['notifications_id']}')";
        };
        $sql = "INSERT INTO user_notifications (user_id,notifications_id)
        VALUES " . implode(',', $values);
        return $this->db->query($sql);
    }
    // REMOVE NOTIFICATION FROM USER SIDE
    public function removeNotification(int $userId, int $notificationId)
    {
        $sql = "Update user_notifications SET hidden = '1' WHERE user_id = {$userId} AND notifications_id = {$notificationId}";
        return $this->db->query($sql);
    }
}
