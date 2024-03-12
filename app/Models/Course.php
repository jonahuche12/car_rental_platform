<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'general_name',
        'code',
        'school_id',
    ];

    /**
     * Get the school that owns the course.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public static function getAllUniqueSubjects()
    {
        return Curriculum::distinct('subject')->pluck('subject');
    }

    /**
     * Get the teachers assigned to the course.
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'course_teacher', 'course_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get the students enrolled in the course.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get the classes in which the course is available.
     */
    public function class_sections()
    {
        return $this->belongsToMany(SchoolClassSection::class, 'course_class', 'course_id', 'class_id')
            ->withPivot('teacher_id')
            ->withTimestamps();
    }
    
    public function getAllSections()
    {
        return $this->class_sections;
    }


    /**
     * Get the assignments associated with the course.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the assessments associated with the course.
     */
    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    /**
     * Get the exams associated with the course.
     */
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get the grades associated with the course.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'course_id');
    }
}
