<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    // setiap Product memiliki banyak ProductGallery.
    public function galleries()
    {
        return $this->hasMany(ProductGallery::class);
    }
}
