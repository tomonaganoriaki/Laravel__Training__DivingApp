<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
