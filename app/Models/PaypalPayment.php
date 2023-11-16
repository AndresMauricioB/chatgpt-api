<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaypalPayment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'payment_id', 'amount', 'expiration_date'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
