<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    
    public $timestamps = true;
    protected $table = 'shops';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name'
    ];
}
