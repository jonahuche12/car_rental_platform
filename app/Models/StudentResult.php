<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResult extends Model
{
    use HasFactory;
    
        protected $fillable = [
            'student_id',
            'academic_session_id',
            'term_id',
            'school_id',
            'class_section_id',
            'class_id',
            'form_teacher_id', // Add form teacher's ID
            'course_name',
            'assignment_score',
            'assessment_score',
            'exam_score',
            'total_score',
            'grade',
        ];
    
        public function academicSession()
        {
            return $this->belongsTo(AcademicSession::class);
        }
    
        public function term()
        {
            return $this->belongsTo(Term::class);
        }
    
        public function school()
        {
            return $this->belongsTo(School::class);
        }
    
        public function schoolClassSection()
        {
            return $this->belongsTo(SchoolClassSection::class, 'class_section_id');
        }
    
        public function schoolClass()
        {
            return $this->belongsTo(SchoolClass::class, 'class_id');
        }
    
        public function formTeacher()
        {
            return $this->belongsTo(User::class, 'form_teacher_id');
        }
    
        public function student()
        {
            return $this->belongsTo(User::class, 'student_id');
        }
        public function comments()
        {
            return $this->hasMany(StudentResultComment::class, 'student_result_id');
        }
        
        
}
