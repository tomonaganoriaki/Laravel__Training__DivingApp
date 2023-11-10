<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Ranking extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'rankings';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'product_id'); 
    }
}
