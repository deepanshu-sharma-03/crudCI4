<?php

namespace App\Controllers\Orders;

use App\Controllers\BaseController;
use App\Services\OrderServices;

class OrderController extends BaseController
{
    protected $orderService;
    function __construct()
    {
        $this->orderService = new OrderServices();
    }
    function showOrder()
    {
        return $this->orderService->showOrder($this->request);
    }
}
