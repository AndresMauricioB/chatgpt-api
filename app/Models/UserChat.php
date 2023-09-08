<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserChat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_chat_id', 'user_id', 'chat_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_chat_id' => 'integer',
        'user_id' => 'integer',
        'chat_id' => 'integer'
    ];

    function user () : BelongsTo {
        return $this->belongsTo(User::class);
    }

    function chat () : BelongsTo {
        return $this->belongsTo(chat::class);
    }
}
