<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'carts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
