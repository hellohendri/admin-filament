<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    public $table = 'outlets';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        "outlet_name",
        "phone",
        "email",
        "address",
    ];
}
