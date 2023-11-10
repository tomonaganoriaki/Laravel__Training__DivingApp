<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Admin extends Authenticatable
{
    use HasFactory, SoftDeletes;
    
    public $timestamps = true;
    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function chat(): HasMany
    {
        return $this->hasMany(Chat::class, 'chat_id');
    }
}
