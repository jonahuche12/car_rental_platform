<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Collection;

class SchoolClass extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'class_level',
        'picture',
        'description',
        'school_id', // Foreign key to relate class to a school
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function scholarships()
    {
        return Scholarship::where('academic_session_id', $this->school->academicSession->id)
            ->where('term_id', $this->school->term->id)
            ->where('class_level', $this->class_level)
            ->get();
    }

    public function schoolClassSections()
    {
        return $this->hasMany(SchoolClassSection::class, 'class_id');
    }

    // SchoolClass model
    public function allStudents()
    {
        return $this->hasManyThrough(User::class, SchoolClassSection::class, 'class_id', 'id', 'id', 'user_id');
    }

    
    public function students()
    {
        // Get the teachers from all class sections and merge them
        $studentsFromSections = collect();
        foreach ($this->schoolClassSections as $section) {
            $studentsFromSections = $studentsFromSections->merge($section->students);
        }
    
        // Return the unique set of teachers based on the user_id
        return new Collection($studentsFromSections->unique('id')->all());
    }
    
    public function curriculum()
    {
        // dd($this->class_level);
        // dd(Curriculum::where('class_level', $this->class_level)->get());
        // Assuming there are intermediate tables: school_class_sections and curriculum_sections
        return Curriculum::where('country', $this->school->country)
            ->where('class_level', $this->class_level)
            
            ->get();
    }




    /**
     * Get all the teachers assigned to the class.
     */
    public function teachers(): Collection
    {
        // Get the teachers from all class sections and merge them
        $teachersFromSections = collect();
        foreach ($this->schoolClassSections as $section) {
            $teachersFromSections = $teachersFromSections->merge($section->formTeachers);
        }
    
        // Return the unique set of teachers based on the user_id
        return new Collection($teachersFromSections->unique('id')->all());
    }

    public function classPotentialStudents()
    {

        return User::whereHas('profile', function ($query) {
            $query->where('class_id', $this->id) // Assuming the class_id is stored directly in the profile
                ->where('class_confirmed', false);
        });
         
    }
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'class_level', 'class_level');
    }

    /**
     * Get lessons related to this school class based on class_level.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLessonsByClassLevel()
    {
        // Retrieve lessons where the class_level matches the current class_level of this SchoolClass
        return Lesson::where('class_level', $this->class_level)->get();
    }

    public function courses()
    {
        $courseIds = [];

        foreach ($this->schoolClassSections as $section) {
            $sectionCourses = $section->courses;

            foreach ($sectionCourses as $course) {
                if (!in_array($course->id, $courseIds)) {
                    $courseIds[] = $course->id;
                }
            }
        }

        // Retrieve unique courses based on collected course IDs
        return Course::whereIn('id', $courseIds)->get();
    }
   


}
