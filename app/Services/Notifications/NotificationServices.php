<?php

namespace App\Services\Notifications;

use App\Models\NotificationModel;

class NotificationServices
{
    protected  NotificationModel $nModel;
    public function __construct()
    {
        $this->nModel = new NotificationModel();
    }
    public function nfPage()
    {
        $result = $this->nModel->getNotification();
        return view('Notification/notification', ['notification' => $result]);
    }
    public function update(object $request)
    {
        // print_r($request);
        $status = (int)$request->getPost('status');
        $id = (int)$request->getPost('id');

        // $status = ($status == 1) ? 1 : 0;


        $result = $this->nModel->updateStatus($id, $status);

        return response()->setJSON([
            'result' => $result,
            'id' => $id,
            'status' => $status
        ]);
    }
    function save(object $request)
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
        if (!$result) {
            return redirect()->back()->with('error', 'Notification save failed');
        }
        return redirect()->to('/notification')->with('success', 'Saved Successfully');
    }
}
