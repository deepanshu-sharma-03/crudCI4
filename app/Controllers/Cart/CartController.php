<?php

namespace App\Controllers\Cart;

use App\Controllers\BaseController;
use App\Services\CartServices;


class CartController extends BaseController
{
    protected $cartService;
    function __construct()
    {
        $this->cartService = new CartServices();
    }

    // ADD PRODUCT INTO CART
    function addToCart()
    {
        return $this->cartService->addToCart($this->request);
    }

    // SHOW CART DATA
    function showCart()
    {
        return $this->cartService->showCart($this->request);
    }

    // UPDATE CART QUANTITY
    function updateCart()
    {
        return $this->cartService->updateCart($this->request);
    }

    // DELETE PRODUCT FROM CART
    function deleteFromCart()
    {
        return $this->cartService->deleteFromCart($this->request);
    }
}
