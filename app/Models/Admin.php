<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $fillable = ['id','name','email','password','created_at','updated_at','deleted_at'];
    use HasFactory;
}
