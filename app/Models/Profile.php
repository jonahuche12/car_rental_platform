<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'full_name',
        'date_of_birth',
        'gender',
        'profile_picture',
        'email',
        'bio',
        'phone_number',
        'country',
        'state',
        'city',
        'address',
        'student_id',
        'current_class',
        'class_grade',
        'section',
        'roll_number',
        'teacher_id',
        'subjects_taught',
        'classes_assigned',
        'qualifications',
        'certifications',
        'years_of_experience',
        'relationship',
        'ward_name',
        'ward_class',
        'staff_id',
        'department',
        'position',
        'staff_qualifications',
        'staff_certifications',
        'years_of_service',
        'admin_confirmed',
        'teacher_confirmed',
        'student_confirmed',
        'staff_confirmed',
        'class_confirmed',
        'permission_confirm_student',
        'permission_confirm_admin',
        'permission_confirm_teacher',
        'permission_create_lesson',
        'permission_create_course',
        'permission_create_class',
        'permission_create_event',
        'permission_confirm_staff',
        'class_id',
        'school_connects',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
