<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
    ];

    // setiap TransactionItem memiliki satu Product.
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
