<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'carts';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
