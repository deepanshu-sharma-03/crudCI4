<?php

namespace App\Services;

use App\Models\CartModel;
use App\Models\OrderItemsModel;
use App\Models\OrderModel;
use Razorpay\Api\Api;

class PaymentServices
{
    function placeOrder(object $request)
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

    // RAZORPAY STARTPAYMENT FIRST STEP
    function startPayment(Object $request)
    {
        try {
            $totalAm = $request->getPost('total_amount');
            $paymentMode = $request->getPost('payment_mode');
            $amount = (int)str_replace(['₹', ','], '', $totalAm);

            // razorpay object
            $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_SECRET'));
            // create order on server
            $order = $api->order->create([
                'receipt' => "ORD" . time(),
                'amount' => $amount * 100,
                'currency' => 'INR'
            ]);

            return response()->setJSON([
                'status' => true,
                'order_id' => $order['id'],
                'amount' => $amount * 100,
            ]);
        } catch (\Exception $e) {
            return response()->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    function successPayment(object $request)
    {
        $payment = $request->getPost('payment-data');
        $payload = $request->getPost('payload');
        $paymentId = $payment['razorpay_payment_id'];
        $orderId = $payment['razorpay_order_id'];
        $userId = $request->user->id;
        $address = $payload['address'];
        $subTotal = $payload['subtotal'];
        $totalAm = $payload['total_amount'];
        $cartIds = $payload['cart_ids'];
        $paymentMode = $payload['payment_mode'];
        $data1 = [

            'order_number' => $orderId,
            'user_id' => $userId,
            'address' => $address,
            'sub_total'   => (int)str_replace(['₹', ','], '', $subTotal),
            'grand_total' => (int)str_replace(['₹', ','], '', $totalAm),
            'payment_mode' => $paymentMode,
            'status' => 'success',
            'payment_id' => $paymentId,
            'gateway' => 'Razorpay'
        ];
        $orderModel = new OrderModel();
        $orderId = $orderModel->insert($data1);
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
            'status' => true
        ]);
    }

    function failedPayment(object $request)
    {
        $paymentData = $request->getPost('payment-data');
        $orderId = $paymentData['metadata']['order_id'];
        $paymentId = $paymentData['metadata']['payment_id'];
        $payload = $request->getPost('payload');
        $userId = $request->user->id;
        $address = $payload['address'];
        $subTotal = $payload['subtotal'];
        $totalAm = $payload['total_amount'];
        $cartIds = $payload['cart_ids'];
        $paymentMode = $payload['payment_mode'];
        $data1 = [

            'order_number' => $orderId,
            'user_id' => $userId,
            'address' => $address,
            'sub_total'   => (int)str_replace(['₹', ','], '', $subTotal),
            'grand_total' => (int)str_replace(['₹', ','], '', $totalAm),
            'payment_mode' => $paymentMode,
            'status' => 'failed',
            'payment_id' => $paymentId,
            'gateway' => 'Razorpay'
        ];
        $orderModel = new OrderModel();
        $orderId = $orderModel->insert($data1);
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
        }
        return response()->setJSON([
            'status' => true
        ]);
    }
}
