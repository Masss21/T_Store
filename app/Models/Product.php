<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'specifications',
        'price', 'stock', 'main_image', 'gallery_images', 'status',
        'is_featured', 'views'
    ];

    protected $casts = [
        'specifications' => 'array',
        'gallery_images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}