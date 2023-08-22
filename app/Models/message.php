<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    use HasFactory;

    protected $fillable = ["chat_id", "role", "content"];

    function chat () {
        return $this->belongsToMany(chat::class);
    }

}
