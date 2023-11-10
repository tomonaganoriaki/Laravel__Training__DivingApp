<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'brands';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $fillable = ['name'];
    
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
