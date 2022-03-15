<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransactionType;

class TransactionCategory extends Model
{
    use HasFactory;

    public $table = 'transaction_categories';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        "transaction_type",
        "transaction_category",
    ];

    public function type()
    {
        return $this->belongsTo(TransactionType::class);
    }
}
