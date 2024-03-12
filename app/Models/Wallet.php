<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'merchant_id',
        'balance',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    // public function transactions()
    // {
    //     return $this->hasMany(Transaction::class);
    // }

}

