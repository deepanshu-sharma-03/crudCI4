<?php

namespace App\Services;

use App\Models\ProductModel;

class ProductServices
{
    public static function saveProduct(object $product)
    {
        try {
            $productModel = new ProductModel();
            $image = $product->getFile('product_image');
            $imagePath = null;

            if ($image->isValid() && !$image->hasMoved()) {
                // get image name from randomly
                $imageName = $image->getRandomName();
                // move image to the folder 
                $image->move(FCPATH . 'uploads/', $imageName);
                //save image pathinfo
                $imagePath = 'uploads/' . $imageName;
            }
            $productName = $product->getPost('product_name');
            $price = $product->getPost('price');
            $qty = $product->getPost('product_qty');
            // save to db 
            $productModel->insert([
                'product_name' => $productName,
                'product_image' => $imagePath,
                'price' => $price,
                'product_qty' => $qty,
                'status' => 'active'
            ]);

            return response()->setJSON([
                'status' => true,
                'message' => 'Product added successfully'
            ]);
        } catch (\Throwable $e) {
            return response()->setJSON([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public static function editProduct($id)
    {
        $productModel = new ProductModel();
        $data['product'] = $productModel->find($id);
        return view('edit', $data);
    }

    public static function updateProduct($product, $id)
    {
        try {

            $productModel = new ProductModel();

            // OLD DATA
            $data = $productModel->find($id);

            // OLD IMAGE
            $dbImagePath = $data['product_image'];

            // DEFAULT IMAGE

            $imagePath = $dbImagePath;

            // FILE
            $image = $product->getFile('product_image');

            // IF NEW IMAGE UPLOADED

            if (
                $image &&
                $image->getName() != '' &&
                $image->isValid() &&
                !$image->hasMoved()
            ) {

                // RANDOM IMAGE NAME
                $imageName = $image->getRandomName();

                // MOVE IMAGE
                $image->move(
                    FCPATH . 'uploads/',
                    $imageName
                );

                // NEW PATH
                $imagePath = 'uploads/' . $imageName;

                // DELETE OLD IMAGE

                if (
                    !empty($dbImagePath) &&
                    file_exists(FCPATH . $dbImagePath)
                ) {
                    unlink(FCPATH . $dbImagePath);
                }
            }

            // UPDATE DATA
            $updateData = [
                'product_name' => $product->getPost('product_name'),
                'product_image' => $imagePath,
                'price' => $product->getPost('price'),
                'product_qty' => $product->getPost('quantity')
            ];

            // UPDATE
            $productModel->update($id, $updateData);

            return response()->setJSON([
                'status' => true,
                'message' => 'Product Updated Successfully'
            ]);
        } catch (\Throwable $e) {

            dd($e->getMessage());
        }
    }

    public static function deleteProduct($id)
    {
        $productModel = new ProductModel();
        $productModel->delete($id);
        return redirect()->to('/products');
    }

    public static function toggleStatus($data)
    {
        $productModel = new ProductModel();
        $id = $data->getPost('id');
        $currentStatus = $data->getPost('status');
        $newStatus = ($currentStatus == 'active') ? 'inactive' : 'active';
        $productModel->update($id, ['status' => $newStatus]);
        return response()->setJSON([
            'success' => true,
            'status' => $newStatus
        ]);
    }

    public static function viewProducts()
    {
        $productModel = new ProductModel();
        $data['products'] = $productModel->where('status', 'active')->findAll();
        return view('viewProducts', $data);
    }
}
