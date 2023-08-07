<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferHistory extends Model
{
    use HasFactory;
    protected $table = 'transaction_histories';
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'transaction_code',
    ];
}
