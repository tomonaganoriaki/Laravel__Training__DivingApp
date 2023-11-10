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
    protected $fillable = [
        'id',
        'message',
        'sender_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
