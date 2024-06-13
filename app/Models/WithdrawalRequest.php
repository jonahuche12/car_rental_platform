<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'amount',
        'token',
        'completed',
        'processed_at'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include not completed withdrawal requests.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotCompleted($query)
    {
        return $query->where('completed', false);
    }
}
