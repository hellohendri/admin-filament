<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    public $table = 'product_categories';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        "product_category",
        "description"
    ];
}
