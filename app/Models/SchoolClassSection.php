<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClassSection extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'code',
        'user_id',
        'class_id', // Foreign key to relate section to a class
        'main_form_teacher_id',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'class_section_id');
    }

    public function formTeachers()
    {
        return $this->belongsToMany(User::class, 'form_teacher_class_section', 'class_section_id', 'user_id')
            ->withTimestamps();
    }
    public function mainFormTeacher()
    {
        return $this->belongsTo(User::class, 'main_form_teacher_id');
    }
    public function student()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'school_class_section_user', 'class_section_id', 'user_id')
            ->withTimestamps();
    }
    public function getPotentialStudents()
    {
        $potentialStudents = User::whereHas('profile', function ($query) {
                $query->where('class_id', $this->schoolClass->id);
                    // ->where('class_section_id', '!=', $this->id);
            })
            ->get();

        return $potentialStudents;
    }

    
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_class', 'class_id', 'course_id')
            ->withTimestamps();
    }

    public function promotionCriteria()
    {
        return $this->hasMany(PromotionCriteria::class);
    }


}
