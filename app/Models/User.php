<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    public $timestamps = true;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'point',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function order(): HasMany
    {
        return $this->hasMany(Order::class, 'order_id');
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class, 'cart_id');
    }

    public function address(): HasMany
    {
        return $this->hasMany(Address::class, 'address_id');
    }

    public function chat(): HasMany
    {
        return $this->hasMany(Chat::class, 'chat_id');
    }
}
