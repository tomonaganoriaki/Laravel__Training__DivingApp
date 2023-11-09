<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'news';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'title',
        'content'
    ];
}
