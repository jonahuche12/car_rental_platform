<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'amount',
        'id_paid_for',
        'paid_for',
        'package_id',
        'payment_session_id',
        'payment_marked',
        'payment_confirmed',
        'confirmation_link',
        'status',
    ];
}
