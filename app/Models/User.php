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
        'user_package_id',
        'expected_expiration'
    ];

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
                    'qualifications',
                    'certifications',
                    'years_of_experience',
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
                    'qualifications',
                    'certifications',
                    'years_of_experience',
                    'teacher_id',
                    'subjects_taught',
                    'classes_assigned',
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
                    'qualifications',
                    'certifications',
                    'years_of_experience',
                    'staff_id',
                    'department',
                    'position',
                    'staff_qualifications',
                    'staff_certifications',
                    'years_of_service',
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
                    'qualifications',
                    'certifications',
                    'ward_name',
                    'ward_class',
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

    public function ownedSchools()
    {
        return $this->hasMany(School::class, 'school_owner_id');
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


    
}
