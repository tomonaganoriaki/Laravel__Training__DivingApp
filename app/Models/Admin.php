<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory;
    
    public $timestamps = true;
    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'email',
        'password'
    ];

    public function chat()
    {
        return $this->hasMany(Chat::class, 'chat_id');
    }
}
