<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function ranking(): BelongsTo
    {
        return $this->belongsTo(Ranking::class, 'ranking_id'); 
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'product_id');
    }

    public function movie(): HasMany
    {
        return $this->hasMany(Movie::class, 'movie_id'); 
    }

    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class);
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class);
    }

    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }
    
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class , 'product_category');
    }
}
