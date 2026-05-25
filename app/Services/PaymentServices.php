<?php

namespace App\Services;

use App\Models\CartModel;
use App\Models\OrderItemsModel;
use App\Models\OrderModel;

class PaymentServices
{
    function placeOrder($request)
    {
        $userId = $request->user->id;
        $address = $request->getPost('address');
        $subTotal = $request->getPost('subtotal');
        $totalAm = $request->getPost('total_amount');
        $cartIds = $request->getPost('cart_ids');
        $paymentMode = $request->getPost('payment_mode');
        $orderModel = new OrderModel();
        $orderNumber = "ORN" . date('YmdHis');
        $data1 = [

            'order_number' => $orderNumber,
            'user_id' => $userId,
            'address' => $address,
            'sub_total'   => (int)str_replace(['₹', ','], '', $subTotal),
            'grand_total' => (int)str_replace(['₹', ','], '', $totalAm),
            'payment_mode' => $paymentMode,
            'status' => 'success'
        ];
        // var_dump($data1);

        $orderId = $orderModel->insert($data1);

        // insert data into orders Table
        $cartModel = new CartModel();
        $ordersItem = new OrderItemsModel();
        foreach ($cartIds as $cartId) {
            $productId = $cartModel->where('id', $cartId)->first();
            $data = [
                'user_id' => $userId,
                'order_id' => $orderId,
                'product_id' => $productId['product_id']
            ];
            $ordersItem->insert($data);
            $cartModel->delete($cartId);
        }

        return response()->setJSON([
            'status' => true,
            'data' => $data1
        ]);
    }
}
