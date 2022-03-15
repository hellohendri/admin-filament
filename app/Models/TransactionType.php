<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    use HasFactory;

    public $table = 'transaction_types';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        "transaction_type",
    ];
}
