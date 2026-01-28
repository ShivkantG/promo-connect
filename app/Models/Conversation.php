<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'promoter_id',
    ];

    // Optional relations
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
