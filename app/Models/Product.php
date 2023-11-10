<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock'
    ];

    public function ranking()
    {
        return $this->belongsTo(Ranking::class, 'ranking_id'); 
    }

    public function image()
    {
        return $this->hasMany(Image::class, 'image_id'); 
    }

    public function movie()
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
        return $this->belongsToMany(Tag::class);
    }
    
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
