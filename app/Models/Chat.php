<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'chats';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $casts = [
        'sender_type' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->where('sender_type', true);
    }
    
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id')->where('sender_type', false);
    }
}
