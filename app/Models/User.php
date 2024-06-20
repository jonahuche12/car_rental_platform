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
    public function SchoCatTestGrades()
    {
        return $this->hasMany(TestGrade::class);
    }

    

    public function scholarshipCategories()
    {
        return $this->belongsToMany(ScholarshipCategory::class, 'scholarship_category_user')
                    ->withTimestamps();
    }
    

    public function enrolledLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user', 'user_id', 'lesson_id')
                    ->withPivot('role') // Include the 'role' column from the pivot table if needed
                    ->withTimestamps(); // Include timestamps for pivot table
    }

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
    public function lessonTransactions()
    {
        return $this->hasMany(LessonTransaction::class);
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

    public function isEnrolledInCategory($categoryId)
    {
        return $this->scholarshipCategories()->where('scholarship_category_id', $categoryId)->exists();
    }
    public function testGrades()
    {
        return $this->hasMany(TestGrade::class);
    }

    public function rankUser()
    {
        switch ($this->profile->role) {
            case 'student':
                return $this->rankStudent();
            case 'teacher':
                return $this->rankTeacher();
            case 'school_owner':
                return $this->rankSchoolOwner();
            case 'admin':
            case 'staff':
                return $this->rankAdminOrStaff();
            default:
                return $this->rankOther();
        }
    }

/**
 * Rank student based on test grades and interactions with lessons.
 *
 * @return float The ranking score, normalized to a 0-5 scale.
 */
private function rankStudent()
{
    // Get average grade score for the student
    $averageGrade = $this->grades()->avg('score') / 100; // Normalize to 0-1 scale

    // Get average test grade score for the student
    $averageTestGrade = $this->testGrades()->avg('score') / 100; // Normalize to 0-1 scale

    // Define weights for different metrics
    $gradeWeight = 0.6; // 60% weight for grades
    $testGradeWeight = 0.4; // 40% weight for test grades

    // Combine metrics to calculate ranking
    $ranking = ($gradeWeight * $averageGrade) + ($testGradeWeight * $averageTestGrade);

    // Normalize ranking to a 0-5 scale
    return round(min($ranking * 5, 5), 2);
}

/**
 * Rank teacher based on lessons they have and their interactions.
 *
 * @return float The ranking score, normalized to a 0-5 scale.
 */
private function rankTeacher()
{
    $lessonCount = $this->lessons()->count();
    if ($lessonCount == 0) {
        return 0;
    }

    // Get total likes for lessons created by the teacher
    $totalLikes = $this->lessons()->withCount('likedUsers')->get()->sum('liked_users_count');

    // Get total favorites for lessons created by the teacher
    $totalFavorites = $this->lessons()->withCount('favoritedByUsers')->get()->sum('favorited_by_users_count');

    // Normalize likes and favorites by scaling them to a percentage of total possible interactions
    $maxLikes = $lessonCount * 100; // Max possible likes assuming each lesson can get 100 likes
    $maxFavorites = $lessonCount * 100; // Max possible favorites assuming each lesson can be favorited 100 times

    $normalizedLikes = $maxLikes > 0 ? $totalLikes / $maxLikes : 0;
    $normalizedFavorites = $maxFavorites > 0 ? $totalFavorites / $maxFavorites : 0;

    // Define weights for different metrics
    $likesWeight = 0.5; // 50% weight for likes
    $favoritesWeight = 0.5; // 50% weight for favorites

    // Combine metrics to calculate ranking
    $ranking = ($likesWeight * $normalizedLikes) + ($favoritesWeight * $normalizedFavorites);

    // Normalize ranking to a 0-5 scale
    return round(min($ranking * 5, 5), 2);
}

private function rankAdminOrStaff()
{
    $lessonCount = $this->lessons()->count();
    $activityCount = $this->events()->count();

    if ($lessonCount == 0 && $activityCount == 0) {
        return 0;
    }

    // Get total likes for lessons created by the admin or staff
    $totalLikes = $this->lessons()->withCount('likedUsers')->get()->sum('liked_users_count');

    // Get total favorites for lessons created by the admin or staff
    $totalFavorites = $this->lessons()->withCount('favoritedByUsers')->get()->sum('favorited_by_users_count');

    // Get total activities or engagements
    $totalActivities = $activityCount;

    // Normalize likes, favorites, and activities
    $maxLikes = $lessonCount * 100; // Assuming max 100 likes per lesson
    $maxFavorites = $lessonCount * 100; // Assuming max 100 favorites per lesson
    $maxActivities = 100; // Assuming max 100 activities

    $normalizedLikes = $maxLikes > 0 ? $totalLikes / $maxLikes : 0;
    $normalizedFavorites = $maxFavorites > 0 ? $totalFavorites / $maxFavorites : 0;
    $normalizedActivities = $maxActivities > 0 ? $totalActivities / $maxActivities : 0;

    // Define weights for different metrics
    $likesWeight = 0.3;
    $favoritesWeight = 0.3;
    $activitiesWeight = 0.4;

    // Combine metrics to calculate ranking
    $ranking = ($likesWeight * $normalizedLikes) + ($favoritesWeight * $normalizedFavorites) + ($activitiesWeight * $normalizedActivities);

    // Normalize ranking to a 0-5 scale
    return round(min($ranking * 5, 5), 2);
}

/**
 * Rank users with other roles based on lessons and their interactions.
 * Exclude users with 'guardian' role.
 *
 * @return float The ranking score, normalized to a 0-5 scale.
 */
private function rankOther()
{
    $lessonCount = $this->lessons()->count();
    if ($lessonCount == 0) {
        return 0;
    }

    // Get total likes for lessons created by the user
    $totalLikes = $this->lessons()->withCount('likedUsers')->get()->sum('liked_users_count');

    // Get total favorites for lessons created by the user
    $totalFavorites = $this->lessons()->withCount('favoritedByUsers')->get()->sum('favorited_by_users_count');

    // Normalize likes and favorites
    $maxLikes = $lessonCount * 100;
    $maxFavorites = $lessonCount * 100;

    $normalizedLikes = $maxLikes > 0 ? $totalLikes / $maxLikes : 0;
    $normalizedFavorites = $maxFavorites > 0 ? $totalFavorites / $maxFavorites : 0;

    // Define weights for different metrics
    $likesWeight = 0.5;
    $favoritesWeight = 0.5;

    // Combine metrics to calculate ranking
    $ranking = ($likesWeight * $normalizedLikes) + ($favoritesWeight * $normalizedFavorites);

    // Normalize ranking to a 0-5 scale
    return round(min($ranking * 5, 5), 2);
}

    // private function rankSchoolOwner()
    // {
    //     // Get the average ranking of the teachers in the school
    //     $averageTeacherRanking = $this->teachers()->get()->avg(function($teacher) {
    //         return $teacher->rankUser();
    //     }) / 5; // Normalize to 0-1 scale
    //     $averageStudentsRanking = $this->students()->get()->avg(function($student) {
    //         return $student->rankUser();
    //     }) / 5; // Normalize to 0-1 scal

        
    //     // Define weights for different metrics
    //     $teacherRankingWeight = 0.7; // 70% weight for teacher rankings
    //     $schoolRankingWeight = 0.3; // 30% weight for school ranking

    //     // Combine metrics to calculate ranking
    //     $ranking = ($teacherRankingWeight * $averageTeacherRanking) + ($schoolRankingWeight * $schoolRanking);

    //     // Normalize ranking to a 0-5 scale
    //     return min($ranking * 5, 5);
    // }

    
    /**
     * Rank admin or staff based on lessons they have and their interactions.
     *
     * @return float The ranking score, normalized to a 0-5 scale.
     */
   
    public function events()
    {
        return $this->hasMany(Event::class);
    }
    
    
    public function favoritedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_favorite', 'user_id', 'lesson_id')
                    ->withTimestamps(); // Include if you have timestamps in the pivot table
    }

  
}
