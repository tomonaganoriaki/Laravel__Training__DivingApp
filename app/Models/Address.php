<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Address extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'addresses';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }
}
