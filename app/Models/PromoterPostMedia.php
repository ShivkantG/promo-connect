<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoterPostMedia extends Model
{
    protected $fillable = ['promoter_post_id', 'type', 'path'];

    public function post()
    {
        return $this->belongsTo(PromoterPost::class);
    }
}
