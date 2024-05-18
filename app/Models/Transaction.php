<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'trx_number',
        // 'payment_method',
        // 'payment_va_name',
        // 'payment_va_number',
        'shipping_cost',
        'total_price',
        'status',
    ];

    // setiap Transaction dimiliki oleh satu User.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // setiap Transaction dimiliki oleh satu Address.
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // setiap Transaction memiliki banyak TransactionItems.
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
