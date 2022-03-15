<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    public $table = 'payment_methods';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        "payment_method",
    ];
}
