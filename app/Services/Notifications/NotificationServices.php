<?php

namespace App\Services\Notifications;

use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Models\UserNfModel\UserNfModel;

class NotificationServices
{
    protected  NotificationModel $nModel;
    protected UserModel $userModel;
    protected UserNfModel $userNfModel;

    public function __construct()
    {
        $this->nModel = new NotificationModel();
        $this->userModel = new UserModel();
        $this->userNfModel = new UserNfModel();
    }
    public function nfPage()
    {
        $result = $this->nModel->getNotification();
        return view('Notification/notification', ['notification' => $result]);
    }
    public function update(object $request): object
    {
        $status = (int)$request->getPost('status');
        $id = (int)$request->getPost('id');

        $result = $this->nModel->updateStatus($id, $status);

        return response()->setJSON([
            'result' => $result,
            'id' => $id,
            'status' => $status
        ]);
    }
    public function save(object $request): object
    {
        $title = $request->getPost('title');
        $description = $request->getPost('description');
        $status = $request->getPost('status');
        if ($status === "Active") {
            $status = '1';
        } else {
            $status = '0';
        }
        $result = $this->nModel->saveNotification($title, $description, $status);


        $users = $this->userModel->getUsersId();

        $batchsize = 100;
        $values = [];


        foreach ($users as $user) {
            $values[] = [
                'user_id' => $user['id'],
                'notifications_id' => $result,
            ];
        }

        // Save all notifications in batches
        for ($i = 0; $i < count($values); $i += $batchsize) {
            $batch = array_slice($values, $i, $batchsize);
            $this->userNfModel->saveNotifications($batch);
        }
        return redirect()->to('/notification')->with('success', 'Saved Successfully');
    }
    // remove notification from user side
    public function removeNotification(object $request): object
    {
        $notificationId = (int)$request->getPost('notificationId');
        $userId = $request->user->id;
        $result = $this->userNfModel->removeNotification($userId, $notificationId);
        return response()->setJSON([
            'result' => $result
        ]);
    }
}
