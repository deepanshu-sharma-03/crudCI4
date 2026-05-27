<?php

namespace App\Controllers\Payment;

use App\Controllers\BaseController;
use App\Services\PaymentServices;

class PaymentController extends BaseController
{
    protected $paymentService;

    function __construct()
    {
        $this->paymentService = new PaymentServices();
    }
    function placeOrder()
    {
        return $this->paymentService->placeOrder($this->request);
    }
    function startPayment()
    {
        return $this->paymentService->startPayment($this->request);
    }
    function successPayment()
    {
        return $this->paymentService->successPayment($this->request);
    }
    function failedPayment()
    {
        return $this->paymentService->failedPayment($this->request);
    }
}
