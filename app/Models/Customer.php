<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public $table = 'customers';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        "customer_name",
        "phone",
        "email",
        "address",
    ];
}
