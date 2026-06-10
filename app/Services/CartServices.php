<?php

namespace App\Services;

use App\Models\ProductModel;
use App\Models\CartModel;

class CartServices
{
    protected CartModel $cartModel;
    protected ProductModel $productModel;
    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->productModel = new ProductModel();
    }

    public  function addToCart(object $request): object
    {
        $data = $request->getJSON(true);
        $userId = $request->user->id;
        $productData = $this->productModel->where('id', $data['product_id'])->first();
        $existingUser = $this->cartModel->where('user_id', $userId)->where('product_id', $data['product_id'])->first();
        if ($productData['product_qty'] < $data['quantity']) {
            return response()->setJSON([
                'status' => false,
                'message' => 'Quantity Exceeds'
            ]);
        }
        if (!$existingUser) {
            $this->cartModel->save([
                'user_id' => $userId,
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'],
                'price' => (int)str_replace(['₹', ','], '', $data['price']),
            ]);
            return response()->setJSON([
                'status' => true,
                'message' => 'add to cart successfully'
            ]);
        }
        return response()->setJSON([
            'status' => false,
            'message' => 'already in  cart'
        ]);
    }
    function showCart(object $request): string
    {
        $userId = $request->user->id;
        $data = $this->cartModel->select('cart.id,cart.quantity,products.product_name,products.product_image,products.price,products.product_qty')
            ->join('products', 'products.id=cart.product_id')->where('cart.user_id', $userId)->findAll();
        return view('cartPage', ['cart' => $data]);
    }

    //DELETE FROM CART
    function deleteFromCart(object $request): object
    {
        $id = $request->getPost('id');
        $data = $this->cartModel->delete($id);
        return response()->setJSON([
            'status' => $data
        ]);
    }

    // UPDATE THE QUANTITY OF PRODUCT
    function UpdateCart(object $request): object
    {
        $cartId = $request->getPost('id');
        $quantity = $request->getPost('quantity');
        $data = $this->cartModel->where('id', $cartId)->first();
        $productData = $this->productModel->where('id', $data['product_id'])->first();
        if ($productData['product_qty'] < $quantity) {
            return response()->setJSON([
                'status' => false,
                'message' => 'Quantity exceeds'
            ]);
        } else {
            $status = $this->cartModel->update($cartId, ['quantity' => $quantity, 'updated_at' => date('Y-m-d H:i:s')]);
            return response()->setJSON([
                'status' => $status,
                'message' => 'update Successfully'
            ]);
        }
    }
}
