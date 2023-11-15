<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

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

    public function sales()
    {
        return $this->belongsToMany(Sale::class);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }
    
    public function categories()
    {
        return $this->belongsToMany(Category::class , 'product_category');
    }
}
