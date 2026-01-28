<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoterPost extends Model
{
    protected $fillable = ['user_id', 'caption'];

    public function media()
    {
        return $this->hasMany(PromoterPostMedia::class);
    }
}
