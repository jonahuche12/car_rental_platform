<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'course_id',
        'assignment_id',
        'assessment_id',
        'complete_score',
        'exam_id',
        'score',
        'academic_session_id',
        'term_id',
    ];

    /**
     * Get the user (student) associated with the grade.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // Define a method to calculate the average complete score for a specific assessment type
    public static function getAverageCompleteScore($grades, $assessmentType)
    {
        // Determine the default complete score based on the assessment type
        $defaultCompleteScore=10;
        if ($assessmentType == 'exam_id') {
            $defaultCompleteScore = 100;
            
        }

        // Calculate the average complete score based on the grades and assessment type
        $totalCompleteScore = 0;
        $totalCount = 0;

        foreach ($grades as $grade) {
            // Check if the specific assessment type column is not null
            if ($grade->$assessmentType !== null) {
                // Use the grade's complete score if not null and not equal to 0.00; otherwise, use the default complete score
                $completeScore = ($grade->complete_score !== null && $grade->complete_score != 0.00) ? $grade->complete_score : $defaultCompleteScore;

                $totalCompleteScore += $completeScore;
                $totalCount++;
            }
        }

        // Avoid division by zero
        if ($totalCount > 0) {
            return $totalCompleteScore / $totalCount;
        } else {
            return 0;
        }
    }




    /**
     * Get the course associated with the grade.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the assignment associated with the grade.
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Get the assessment associated with the grade.
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get the exam associated with the grade.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
    public static function calculatePercentage(float $score, float $completeScore): float
    {
        if ($completeScore <= 0) {
            return 0; // Avoid division by zero
        }

        return ($score / $completeScore) * 100;
    }
    public static function calculateGrade(float $percentage): string
    {
        if ($percentage >= 80) {
            return 'A+';
        } elseif ($percentage >= 70) {
            return 'A';
        } elseif ($percentage >= 60) {
            return 'B';
        } elseif ($percentage >= 50) {
            return 'C';
        } elseif ($percentage >= 40) {
            return 'D';
        } elseif ($percentage >= 30) {
            return 'E';
        } else {
            return 'F';
        }
    }
    public function getTotalScoreForTermAndCourse($termId, $courseId)
    {
        return $this->where('term_id', $termId)
                    ->where('course_id', $courseId)
                    ->sum('score');
    }
    
    public function getAverageScoreForTermAndCourse($termId, $courseId)
    {
        $count = $this->where('term_id', $termId)
                      ->where('course_id', $courseId)
                      ->count();
        $totalScore = $this->getTotalScoreForTermAndCourse($termId, $courseId);
    
        return $count > 0 ? $totalScore / $count : 0;
    }
    public function calculateGradeDistribution($scores, $maxScore)
    {
        // Define grade boundaries and corresponding grades
        $gradeBoundaries = [
            ['grade' => 'A+', 'minPercentage' => 80],
            ['grade' => 'A', 'minPercentage' => 70],
            ['grade' => 'B', 'minPercentage' => 60],
            ['grade' => 'C', 'minPercentage' => 50],
            ['grade' => 'D', 'minPercentage' => 40],
            ['grade' => 'E', 'minPercentage' => 30],
            ['grade' => 'F', 'minPercentage' => 0],
        ];

        // Initialize grade distribution array
        $gradeDistribution = array_fill_keys(array_column($gradeBoundaries, 'grade'), 0);

        // Calculate grade distribution based on scores
        foreach ($scores as $score) {
            $percentage = $this->calculatePercentage($score, $maxScore); // Use $this to call model method
            $grade = $this->determineGrade($percentage, $gradeBoundaries); // Use $this to call model method
            
            if (array_key_exists($grade, $gradeDistribution)) {
                $gradeDistribution[$grade]++;
            }
        }

        return $gradeDistribution;
    }

    public function determineGrade($percentage, $gradeBoundaries)
    {
        foreach ($gradeBoundaries as $boundary) {
            if ($percentage >= $boundary['minPercentage']) {
                return $boundary['grade'];
            }
        }

        return 'F'; // Default to 'F' if no grade is matched
    }

    
}
