<?php

namespace App\Services;

use App\Models\OrderItemsModel;
use App\Models\ProductModel;
use App\Models\OrderModel;

class OrderServices
{
    function showOrder($request = null)
    {
        $orderModel = new OrderModel();
        $userId = $request->user->id;
        $result = $orderModel->select('orders.payment_mode, orders.status, order_items.product_id,products.product_image,
        products.product_name,products.price')->join('order_items', 'order_items.order_id = orders.id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('orders.user_id', $userId)
            ->findAll();

        return view('orders', ['orders' => $result]);
    }
}
