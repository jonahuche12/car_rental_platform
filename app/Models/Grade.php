<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'course_id',
        'assignment_id',
        'assessment_id',
        'exam_id',
        'score',
    ];

    /**
     * Get the user (student) associated with the grade.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the course associated with the grade.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the assignment associated with the grade.
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Get the assessment associated with the grade.
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get the exam associated with the grade.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
