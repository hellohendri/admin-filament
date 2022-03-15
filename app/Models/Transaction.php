<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $table = 'transactions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        "date",
        "payment_method",
        "transaction_type",
        "transaction_category",
        "total",
        "description",
    ];

    public function payment_method_id()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }
    public function transaction_type_id()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type');
    }
    public function transaction_category_id()
    {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category');
    }
}
