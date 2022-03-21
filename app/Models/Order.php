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

    public function cashier_id()
    {
        return $this->belongsTo(User::class, 'name');
    }

    public function customer_name_id()
    {
        return $this->belongsTo(Customer::class, 'name');
    }

    public function payment_method_id()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }

    public function payment_status_id()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status');
    }

    public function product_name_id()
    {
        return $this->belongsTo(Product::class, 'name');
    }
}
