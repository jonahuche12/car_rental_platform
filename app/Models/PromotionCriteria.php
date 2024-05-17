<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionCriteria extends Model
{
    use HasFactory;
    protected $fillable = [
        'academic_session_id',
        'school_class_section_id',
        'school_class_id',
        'student_promoted',
        'required_avg_score',
        'total_attendance_percentage',
        'compulsory_courses_avg_score',
    ];

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function schoolClassSection()
    {
        return $this->belongsTo(SchoolClassSection::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
}
