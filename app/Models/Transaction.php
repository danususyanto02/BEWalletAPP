<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $fillable = [
        'user_id',
        'transaction_type_id',
        'payment_methods_id',
        'product_id',
        'amount',
        'transaction_code',
        'description',
        'status',
    ];

    public function transactionType(){
        return $this->belongsTo(TransactionType::class,'transaction_type_id');
    }
    public function userTransaction(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class,'payment_methods_id');
    }
}
