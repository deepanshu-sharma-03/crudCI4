<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primarykey = 'id';
    protected $allowedFields = [
        'order_number',
        'user_id',
        'sub_total',
        'grand_total',
        'payment_mode',
        'status'
    ];
}
