<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;
use App\Models\Profile;
use App\Models\School;
use App\Models\Qualification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\StudentResult;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'school_id',
        'active_package',
        'user_package_id',
        'expected_expiration'
    ];


    public function ownedSchools()
    {
        return $this->hasMany(School::class, 'school_owner_id');
    }


    public function formClasses()
    {
        return $this->belongsToMany(SchoolClassSection::class, 'form_teacher_class_section', 'user_id', 'class_section_id')
            ->withTimestamps();
    }
    

    public function userPackage()
    {
        return $this->belongsTo(UserPackage::class, 'user_package_id');
    }

    public function getUserPackages()
    {
        return UserPackage::all();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'expected_expiration' => 'datetime',
    ];

    /**
     * Check if the user has a profile.
     *
     * @return bool
     */
    public function hasProfile()
    {
        
        return $this->profile()->exists();
    }

    /**
     * Define the user's profile relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        
        return $this->hasOne(Profile::class);
    }

    public function isProfileComplete()
    {
        $profile = $this->profile;

        if (!$profile) {
            return false;
        }

        switch ($profile->role) {
            case 'school_owner':
                return $this->checkProfileFields([
                    'role',
                    'full_name',
                    'date_of_birth',
                    'gender',
                    'profile_picture',
                    'email',
                    'phone_number',
                    'country',
                    'state',
                    'city',
                    'address',
                    
                ]);
            case 'super_admin':
                    return $this->checkProfileFields([
                        'role',
                        'full_name',
                        'date_of_birth',
                        'gender',
                        // 'profile_picture',
                        'email',
                        'phone_number',
                        'country',
                        'state',
                        'city',
                        'address',
                        
                    ]);
            case 'teacher':
                return $this->checkProfileFields([
                    'role',
                    'full_name',
                    'date_of_birth',
                    'gender',
                    'profile_picture',
                    'email',
                    'phone_number',
                    'country',
                    'state',
                    'city',
                    'address',
                ]);
            case 'staff':
                return $this->checkProfileFields([
                    'role',
                    'full_name',
                    'date_of_birth',
                    'gender',
                    'profile_picture',
                    'email',
                    'phone_number',
                    'country',
                    'state',
                    'city',
                    'address',
                ]);
            case 'guardian':
                return $this->checkProfileFields([
                    'role',
                    'full_name',
                    'date_of_birth',
                    'gender',
                    'profile_picture',
                    'email',
                    'phone_number',
                    'address',
                ]);
            case 'student':
                return $this->checkProfileFields([
                    'role',
                    'full_name',
                    'date_of_birth',
                    'gender',
                    'profile_picture',
                    'email',
                    'phone_number',
                    'address',
                    // 'student_id',
                    // 'class_grade',
                    // 'section',
                    // 'roll_number',
                ]);
            default:
                return false;
        }
    }
    public function schoolClass()
    {
        // Retrieve the schoolClass based on user's profile class_id and school_id
        return SchoolClass::where('id', $this->profile->class_id)
            ->where('school_id', $this->school_id)
            ->first();
    }

    public function userClassSection()
    {
        return $this->belongsTo(SchoolClassSection::class, 'class_section_id');
    }


    // public function userClassSection()
    // {
    //     return $this->belongsTo(SchoolClassSection::class, 'school_class_section_user', 'user_id');
    // }

    public function classSection()
    {
        return $this->belongsToMany(SchoolClassSection::class, 'school_class_section_user', 'user_id', 'class_section_id')
            ->withTimestamps();
    }

    /**
     * Check if the specified fields in the profile are not empty.
     *
     * @param array $fields
     * @return bool
     */
    private function checkProfileFields(array $fields)
    {
        foreach ($fields as $field) {
            if (empty($this->profile->{$field})) {
                return false;
            }
        }

        return true;
    }

    
    public function isSchoolAdmin(School $school)
    {
        // dd($school);
        return $this->school && $this->school->id === $school->id;
    }


    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
        
    }

    /**
     * Define the school for which the user is a student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
   


    public function qualifications()
    {
        return $this->hasMany(Qualification::class);
    }

    /**
     * Check if the user has qualifications.
     *
     * @return bool
     */
    public function hasQualifications()
    {
        return $this->qualifications()->exists();
    }

    public function addQualification(array $qualificationData)
    {
        return $this->qualifications()->create($qualificationData);
    }
    public function attendanceForToday()
    {
        // Assuming you have a relationship to the Attendance model
        return $this->attendance()->whereDate('date', Carbon::today())->value('attendance');
    }
    

    /**
     * Define a relationship with the Attendance model.
     */
    public function attendance()
    {
        // Assuming you have a one-to-many relationship with the Attendance model
        return $this->hasMany(Attendance::class);
    }

    public function student_courses()
    {
        return $this->belongsToMany(Course::class, 'course_student', 'user_id', 'course_id')
            ->withTimestamps();
    }

    public function teacher_courses()
    {
        return $this->belongsToMany(Course::class, 'course_teacher', 'user_id')
            ->wherePivot('user_id', $this->id) // Filter by the current user's ID
            ->withTimestamps();
    }

    public function getGradeForAssignment($assignmentId)
    {
        // Retrieve the grade for the specified assignment ID
        return $this->grades()->where('assignment_id', $assignmentId)->first();
    }


    public function getGradeForAssessment($assessmentId)
    {
        // Retrieve the grade for the specified assignment ID
        return $this->grades()->where('assessment_id', $assessmentId)->first();
    }
    public function getGradeForExam($examId)
    {
        // Retrieve the grade for the specified assignment ID
        return $this->grades()->where('exam_id', $examId)->first();
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'user_id');
    }
    
    /**
     * Get the available courses for the student based on their class section.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Course[]
     */
    public function availableCourses()
    {
        // Retrieve the class section of the student
        $classSection = $this->userClassSection;
    
        // Check if the class section exists
        if ($classSection) {
            // Retrieve the IDs of courses already enrolled by the student
            $enrolledCourseIds = $this->student_courses->pluck('id')->toArray();
    
            // Retrieve the courses available for the class section excluding those already enrolled
            return $classSection->courses()
                ->whereNotIn('courses.id', $enrolledCourseIds) // Specify the table name to avoid ambiguity
                ->get();
        }
    
        // If the class section does not exist, return an empty collection
        return collect();
    }

    public function studentSelectedCourses()
    {
        // Retrieve the class section of the student
        $classSection = $this->userClassSection;
    
        // Check if the class section exists
        if ($classSection) {
            // Retrieve the IDs of courses already enrolled by the student
            $enrolledCourseIds = $this->student_courses->pluck('id')->toArray();
    
            // Retrieve the courses available for the class section excluding those already enrolled
            return $classSection->courses()
                ->where('courses.id', $enrolledCourseIds) // Specify the table name to avoid ambiguity
                ->get();
        }
    
        // If the class section does not exist, return an empty collection
        return collect();
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function enrolledLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user', 'user_id', 'lesson_id')
                    ->withPivot('role') // Include the 'role' column from the pivot table if needed
                    ->withTimestamps(); // Include timestamps for pivot table
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function favoriteLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_favorite')
                    ->withTimestamps(); // Include if you have timestamps in the pivot table
    }
    public function likedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_likes')->withTimestamps();
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query->where('first_name', 'like', "%$term%")
                  ->orWhere('last_name', 'like', "%$term%")
                  ->orWhere('email', 'like', "%$term%");
        });
    }
    public function wards()
    {
        return $this->belongsToMany(User::class, 'guardian_ward', 'guardian_id', 'ward_id')
            ->withPivot('confirmed')
            ->withTimestamps();
    }
    
    public function guardians()
    {
        return $this->belongsToMany(User::class, 'guardian_ward', 'ward_id', 'guardian_id')
            ->withPivot('confirmed')
            ->withTimestamps();
    }
    
    public function unconfirmedGuardians()
    {
        return $this->guardians()->wherePivot('confirmed', false);
    }

    // Get unconfirmed wards of a guardian
    public function unconfirmedWards()
    {
        return $this->wards()->wherePivot('confirmed', false);
    }
    public function studentResults(): HasMany
    {
        return $this->hasMany(StudentResult::class, 'student_id');
    }
   
    
}
