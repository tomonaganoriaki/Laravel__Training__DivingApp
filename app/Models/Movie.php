<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Movie extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'movies';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public function product(): BelongsTo
    {
    return $this->belongsTo(Product::class, 'product_id');
    }

}
