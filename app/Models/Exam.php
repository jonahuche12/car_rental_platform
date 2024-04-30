<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'course_id',
        'name',
        'description',
        'due_date',
        'complete_score',
        'academic_session_id',
        'term_id',
        'use_in_final_result',
    ];

    /**
     * Get the course associated with the exam.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function getMaxScore()
    {
        return $this->complete_score;
    }

    public function class_section()
    {
        return $this->belongsTo(SchoolClassSection::class, 'class_section_id');
    }
    public function classSection()
    {
        return $this->belongsTo(SchoolClassSection::class, 'class_section_id');
    }

    /**
     * Get the grades associated with the exam.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'exam_id');
    }


    public function getAverageCompleteScore()
    {
        $grades = $this->grades;

        if ($grades->isEmpty()) {
            return 0; // Return 0 if no grades are associated with this assignment
        }

        // Calculate the average complete score for this assignment
        $totalCompleteScore = $grades->sum('complete_score');
        $count = $grades->count();
        $averageCompleteScore = $count > 0 ? ($totalCompleteScore / $count) : 0;

        return $averageCompleteScore;
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    public function archive()
    {
        $this->update(['archived' => true]);
    }
}
