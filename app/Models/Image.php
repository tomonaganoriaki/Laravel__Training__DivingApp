<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'images';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public function product(): BelongsTo
    {
    return $this->belongsTo(Product::class, 'product_id');
    }
}
