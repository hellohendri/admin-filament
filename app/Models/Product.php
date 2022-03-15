<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $table = 'products';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        "name",
        "product_category",
        "outle_name",
        "stocks",
        "cogs",
        "price",
        "production_date",
        "expired_date"
    ];

    public function product_category_id()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category');
    }
    public function outlet_name_id()
    {
        return $this->belongsTo(Outlet::class, 'outlet_name');
    }
}
