<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'teacher_id',
        'school_id',
        'date',
        'attendance',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
