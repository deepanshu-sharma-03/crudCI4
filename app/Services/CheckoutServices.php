<?php

namespace App\Services;

use App\Models\CartModel;
use App\Models\ProductModel;

class CheckoutServices
{
    protected $cartModel;
    protected $productModel;
    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->productModel = new ProductModel();
    }
    function validateCheckout($request)
    {
        $userId = $request->user->id;
        $data = $this->cartModel->select('cart.id,cart.quantity,products.price,products.product_qty,
        products.product_name,product_image')->join('products', 'products.id=cart.product_id')
            ->where('cart.user_id', $userId)->findAll();
        foreach ($data as $element) {
            if ($element['quantity'] > $element['product_qty']) {
                return response()->setJSON([
                    'status' => false
                ]);
            }
        }

        return response()->setJSON([
            'status' => true,
            'data' => $data,
        ]);
    }
    // CHECKOUT
    function checkout($request)
    {
        $data = $request->getJSON(true);
        return view('checkoutPage', ['data' => $data]);
    }
}
