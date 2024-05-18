<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'full_address',
    ];

    // setiap Address dimiliki satu User.
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
