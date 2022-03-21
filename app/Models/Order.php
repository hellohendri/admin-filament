<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $table = 'orders';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        "cashier",
        "no_order",
        "customer_name",
        "payment_method",
        "payment_status",
        "product_name",
        "quantity",
        "total_price",
        "date",
    ];
}
