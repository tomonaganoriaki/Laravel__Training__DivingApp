<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'addresses';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $fillable = [
        'postal_code',
        'prefecture',
        'city',
        'street'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }
}
