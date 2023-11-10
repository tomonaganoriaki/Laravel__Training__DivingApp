<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Shop extends Model
{
    use HasFactory, SoftDeletes;
    
    public $timestamps = true;
    protected $table = 'shops';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
