<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'teacher_id',
        'school_id',
        'date',
        'attendance',
        'academic_session_id',
        'term_id',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Calculate the attendance percentage for a student within the specified academic session.
     *
     * @param int $studentId
     * @param int $academicSessionId
     * @return float|null
     */
    public static function calculateAttendancePercentage($studentId, $academicSessionId)
    {
        // Count the total number of attendance records for the student
        $totalRecords = self::where('user_id', $studentId)
            ->where('academic_session_id', $academicSessionId)
            ->count();

        // Count the number of days the student was present
        $presentRecords = self::where('user_id', $studentId)
            ->where('academic_session_id', $academicSessionId)
            ->where('attendance', true)
            ->count();

        // Calculate the attendance percentage
        if ($totalRecords > 0) {
            return ($presentRecords / $totalRecords) * 100;
        }

        return null; // Return null if there are no attendance records
    }
}
