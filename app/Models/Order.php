<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Order extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $casts = [
        'order_date' => 'datetime',
        'shipped_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price');
    }
    
    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class);
    }
}
