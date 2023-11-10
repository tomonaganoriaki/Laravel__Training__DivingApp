<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'movies';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'path'
    ];
    
    public function product()
    {
    return $this->belongsTo(Product::class, 'product_id');
    }

}
