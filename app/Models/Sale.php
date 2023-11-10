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
    protected $fillable = [
        'title',
        'content',
        'start_at',
        'end_at',
    ];
    
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
