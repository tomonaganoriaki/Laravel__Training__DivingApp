<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'brands';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
