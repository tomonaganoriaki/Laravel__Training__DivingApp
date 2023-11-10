<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'rankings';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $fillable = ['rank'];
    
    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'product_id'); 
    }
}
