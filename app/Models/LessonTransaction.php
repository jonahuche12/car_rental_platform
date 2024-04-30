<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'user_id',
        'school_id',
        'type', // This could be 'teacher_earnings' or 'school_earnings'
        'amount',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
