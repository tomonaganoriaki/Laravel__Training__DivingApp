<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'order_number',
        'status',
        'total_price',
        'payment_method',
        'order_date',
        'shipped_date',
    ];
    public function user()
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
