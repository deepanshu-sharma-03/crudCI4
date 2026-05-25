<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemsModel extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'order_id',
        'product_id'
    ];
}
