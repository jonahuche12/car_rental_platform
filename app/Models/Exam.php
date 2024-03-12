<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'course_id',
        'name',
        'description',
        'date',
    ];

    /**
     * Get the course associated with the exam.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the grades associated with the exam.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'exam_id');
    }
}
