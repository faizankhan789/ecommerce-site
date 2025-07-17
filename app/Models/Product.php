<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'compare_price', 'quantity',
        'sku', 'image', 'gallery', 'category_id', 'is_featured', 'is_active',
        'views', 'rating', 'reviews_count'
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'rating' => 'decimal:1'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }

    public function isInStock()
    {
        return $this->quantity > 0;
    }

    public function decrementStock($quantity)
    {
        return $this->decrement('quantity', $quantity);
    }

    public function incrementStock($quantity)
    {
        return $this->increment('quantity', $quantity);
    }
    public function scopeSearch($query, $search)
{
    return $query->where(function ($q) use ($search) {
        $q->where('name', 'LIKE', "%{$search}%")
          ->orWhere('description', 'LIKE', "%{$search}%")
          ->orWhere('sku', 'LIKE', "%{$search}%");
    });
}
}