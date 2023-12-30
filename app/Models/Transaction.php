<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_type', 'amount', 'account_card_id'
    ];

    protected $casts = [
        'transaction_type' => TransactionType::class
    ];

    public function accountCard()
    {
        return $this->belongsTo(AccountCard::class);
    }

    public function scopeDebit($q)
    {
        return $q->where('transaction_type', TransactionType::Debit->value);
    }

    public function scopeCredit($q)
    {
        return $q->where('transaction_type', TransactionType::Credit->value);
    }

}
