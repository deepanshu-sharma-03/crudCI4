<?php

namespace App\Controllers\Notification;

use App\Controllers\BaseController;
use App\Services\Notifications\NotificationServices;

class NotificationController extends BaseController
{
    protected NotificationServices $notificationService;
    function __construct()
    {
        $this->notificationService = new NotificationServices();
    }
    function nfPage()
    {
        return $this->notificationService->nfPage();
    }
    function update()
    {
        return $this->notificationService->update($this->request);
    }
    function save()
    {
        return $this->notificationService->save($this->request);
    }
}
