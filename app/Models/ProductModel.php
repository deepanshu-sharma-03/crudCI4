<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{

    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_name', 'product_image', 'price', 'product_qty', 'status'];
}
