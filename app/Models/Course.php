<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
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
        'compulsory',
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
    public function getTeacherForClassSection($classSectionId)
    {
        $classSection = $this->class_sections()->wherePivot('class_id', $classSectionId)->first();
        
        if ($classSection) {
            return User::find($classSection->pivot->teacher_id);
        }
        
        return null;
    }
    public function curricula()
    {
        // Retrieve the school's country and course's general_name
        $schoolCountry = $this->school->country;
        $courseGeneralName = $this->general_name;
        // dd($courseGeneralName);

        // Retrieve curricula related to this course
        return Curriculum::where('country', $schoolCountry)
            ->where('subject', $courseGeneralName)
            ->get();
    }

    /**
     * Get the teachers assigned to the course.
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'course_teacher', 'course_id', 'user_id')
            ->withPivot('class_id')
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
    public function classes()
    {
        $classIds = [];
    
        // Iterate through each schoolClassSection related to the course
        foreach ($this->class_sections as $section) {
            // Check if schoolClass relationship exists and is not null
            if ($section->schoolClass) {
                // If schoolClass is a single instance (belongsTo relationship)
                $class = $section->schoolClass;
                
                // Ensure $class is an object before accessing its properties
                if ($class && !in_array($class->id, $classIds)) {
                    $classIds[] = $class->id;
                }
            }
        }
    
        // Retrieve unique SchoolClass instances based on collected class IDs
        return SchoolClass::whereIn('id', $classIds)->get();
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

    public function getClassForTeacher($teacherId)
    {
        // Retrieve the class section taught by the teacher for the course
        return $this->class_sections()
            ->wherePivot('teacher_id', $teacherId)
            ->first();
    }

}
