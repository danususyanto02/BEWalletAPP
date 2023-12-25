<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranferHistory extends Model
{
    use HasFactory;

    protected $table = 'transfer_histories';
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'transaction_code',

    ];
}
