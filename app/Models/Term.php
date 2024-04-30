<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'academic_session_id'];

    /**
     * Get the academic session that owns the term.
     */
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
