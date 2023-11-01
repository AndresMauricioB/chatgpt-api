<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chat extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];

    function messages() {
        return $this->hasMany(message::class)->orderBy("created_at", "asc");
    }

    function user () {
        return $this->belongsToMany(User::class);
    }
}
