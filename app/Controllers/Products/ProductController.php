<?php

namespace App\Controllers\Products;

use CodeIgniter\Controller;
use App\Models\ProductModel;
use App\Services\ProductServices;

class ProductController extends Controller
{
    function getProducts()
    {
        $productModel = new ProductModel();
        $data['products'] = $productModel->findAll();
        return view('products', $data);
    }

    function addProduct()
    {
        return view('addProduct');
    }
    function saveProduct()
    {
        return ProductServices::saveProduct($this->request);
    }
    function editProduct($id = null)
    {
        return ProductServices::editProduct($id);
    }
    function updateProduct($id = null)
    {
        return ProductServices::updateProduct($this->request, $id);
    }
    function deleteProduct($id = null)
    {
        return ProductServices::deleteProduct($id);
    }
    function toggleStatus()
    {
        return ProductServices::toggleStatus($this->request);
    }
    function viewProducts()
    {
        return ProductServices::viewProducts();
    }
}
