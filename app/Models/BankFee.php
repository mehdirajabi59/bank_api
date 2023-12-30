<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'price', 'account_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
