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

    
}
