<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_type',   // 'brand' or 'promoter'
        'sender_id',
        'message',
    ];

    /* =======================
       RELATIONSHIPS
    ======================= */

    // Each message belongs to a conversation
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    // Optional: sender (Brand or Promoter - polymorphic style)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
