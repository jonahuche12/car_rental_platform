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
                $query->where('class_id', $this->schoolClass->id) // Assuming the class_id is stored directly in the profile
                    ->where('class_confirmed', false);
            })
            
            ->get();
            // dd($this->schoolClass->id);
            return $potentialStudents;
    }


}
