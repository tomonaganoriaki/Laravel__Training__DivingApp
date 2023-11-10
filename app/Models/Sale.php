<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'sales';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];
    
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
