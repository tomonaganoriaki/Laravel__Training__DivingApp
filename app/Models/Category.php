<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
