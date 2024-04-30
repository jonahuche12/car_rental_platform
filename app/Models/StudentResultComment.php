<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResultComment extends Model
{
    use HasFactory;

    protected $table = 'student_result_comments';

    protected $fillable = [
        'student_id',
        'class_id',
        'class_section_id',
        'total_average_score',
        'student_result_id',
        'form_teacher_id',
        'academic_session_id',
        'term_id',
        'comment',
    ];

    // Define relationships if needed
    public function studentResult()
    {
        return $this->belongsTo(StudentResult::class, 'student_result_id');
    }

    // Define other relationships as needed
}
