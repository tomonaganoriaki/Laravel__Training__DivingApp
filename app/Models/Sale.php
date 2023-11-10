<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

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
