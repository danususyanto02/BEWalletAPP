<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallets extends Model
{
    use HasFactory;

    protected $table = 'wallet';

    protected $fillable = [
        'balance',
        'pin',
        'user_id',
        'card_number'
    ];
}
