<?php

namespace App\Controllers\Checkout;

use App\Controllers\BaseController;
use App\Services\CheckoutServices;

class CheckoutController extends BaseController
{
    protected $checkoutService;
    public function __construct()
    {
        $this->checkoutService = new CheckoutServices();
    }

    // VALIDATE  CHECKOUT FOR QUANTITY IN STOCK OR NOT
    function validateCheckout()
    {
        return $this->checkoutService->validateCheckout($this->request);
    }
    // CHECKOUT PAGE
    function checkout()
    {
        return $this->checkoutService->checkout($this->request);
    }
}
