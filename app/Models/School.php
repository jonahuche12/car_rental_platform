<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'mission',
        'motto',
        'vision',
        'logo',
        'address',
        'city',
        'state',
        'country',
        'email',
        'phone_number',
        'website',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'total_students',
        'total_teachers',
        'total_staff',
        'is_active',
        'school_package_id',
        'school_owner_id',
    ];

    public function schoolClassSections()
    {
        return $this->hasManyThrough(
            SchoolClassSection::class,
            SchoolClass::class,
            'school_id', // Foreign key on SchoolClass table
            'class_id',  // Foreign key on SchoolClassSection table
            'id',        // Local key on School table
            'id'         // Local key on SchoolClass table
        );
    }

    public function schoolPackage()
    {
        return $this->belongsTo(SchoolPackage::class);
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function schoolOwner()
    {
        return $this->belongsTo(User::class, 'school_owner_id');
    }

    public function getAdmins()
    {
        // Retrieve users with roles 'admin', 'teacher', and 'staff' (excluding 'student' and 'guardian')
        return $this->hasMany(User::class, 'school_id')->whereHas('profile', function ($query) {
            $query->whereIn('role', ['admin', 'teacher', 'staff']);
        })->get();
    }

    public function getPotentialAdmins()
    {
        // Retrieve users with roles 'admin', 'teacher', and 'staff' (excluding 'student' and 'guardian')
        return $this->hasMany(User::class, 'school_id')
            ->whereHas('profile', function ($query) {
                $query->whereIn('role', ['admin', 'teacher', 'staff'])
                    ->where('admin_confirmed', false);
            })
            ->get();
    }



    public function getConfirmedAdmins()
    {
        // Retrieve all users with a specific role (e.g., 'admin') associated with the school
        return $this->hasMany(User::class, 'school_id')->whereHas('profile', function ($query) {
            $query->where('admin_confirmed', 1);
        })->get();
    }


    /**
     * Define the students relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        // Retrieve users with the role 'student' associated with the school
        return $this->hasMany(User::class, 'school_id')
                    ->whereHas('profile', function ($query) {
                        $query->where('role', 'student');
                    });
    }


    public function teachers()
    {
        // Retrieve users with the role 'student' associated with the school
        return $this->hasMany(User::class, 'school_id')
                    ->whereHas('profile', function ($query) {
                        $query->where('role', 'teacher');
                    });
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    

    public function confirmedStudents()
    {
        // Retrieve confirmed students associated with the school
        return $this->hasMany(User::class, 'school_id')
        ->whereHas('profile', function ($query) {
            $query->where('role', 'student')->where('student_confirmed', true);
        });
    }

   // School model
    public function potentialStudents()
    {
        // Assuming 'potential_students' is the pivot table name for the many-to-many relationship
        return $this->hasMany(User::class, 'school_id')
                    ->whereHas('profile', function ($query) {
                        $query->where('role', 'student')->where('student_confirmed', false);
                    });
    }

    

    public function confirmedTeachers()
    {
        // Retrieve confirmed teachers associated with the school
        return $this->hasMany(User::class, 'school_id')
                    ->whereHas('profile', function ($query) {
                        $query->where('role', 'teacher')->where('teacher_confirmed', true);
                    });
    }

    public function potentialTeachers()
    {
        // Retrieve potential teachers associated with the school
        return $this->hasMany(User::class, 'school_id')
                    ->whereHas('profile', function ($query) {
                        $query->where('role', 'teacher')->where('teacher_confirmed', false);
                    });
    }

        
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    // Define the relationship with Term
    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public static function manageAllSchools()
    {
        // Retrieve all schools ordered alphabetically by name
        $schools = School::orderBy('name')->get();

        return $schools;
    }


    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
    public function lessonTransactions()
    {
        return $this->hasMany(LessonTransaction::class);
    }
    public function getRanking()
    {
        // Get total likes for lessons in this school
        $totalLikes = $this->lessons()
            ->withCount('likedUsers')
            ->get()
            ->sum('liked_users_count');
    
        // Get total favorites for lessons in this school
        $totalFavorites = $this->lessons()
            ->whereHas('favoritedByUsers')
            ->get()
            ->count();
    
        // Get the average grade score for students in this school
        $averageGrade = $this->students()
            ->join('test_grades', 'users.id', '=', 'test_grades.user_id')
            ->avg('test_grades.score');
    
        // Normalize likes and favorites by scaling them to a percentage of total possible likes and favorites
        $maxLikes = $this->lessons()->count() * 100; // Max possible likes assuming each lesson can get 100 likes
        $maxFavorites = $this->lessons()->count() * 100; // Max possible favorites assuming each lesson can be favorited 100 times
    
        $normalizedLikes = $totalLikes / $maxLikes;
        $normalizedFavorites = $totalFavorites / $maxFavorites;
    
        // Combine metrics to calculate ranking
        // Adjust weights as needed to ensure total weight is 100%
        $likesWeight = 0.3; // 30% weight for likes
        $favoritesWeight = 0.2; // 20% weight for favorites
        $gradeWeight = 0.5; // 50% weight for grades
    
        $ranking = ($likesWeight * $normalizedLikes) + ($favoritesWeight * $normalizedFavorites) + ($gradeWeight * $averageGrade);
    
        // Normalize ranking to a 0-5 scale
        $maxPossibleRanking = ($likesWeight + $favoritesWeight + $gradeWeight) * 100; // Max possible ranking
        $normalizedRanking = ($ranking / $maxPossibleRanking) * 5; // Scale to 0-5
    
        // Ensure ranking does not exceed 5
        $finalRanking = min($normalizedRanking, 5);
    
        // Round to 2 decimal places
        $roundedRanking = round($finalRanking, 2);
    
        return $roundedRanking;
    }
    
    
    
}
