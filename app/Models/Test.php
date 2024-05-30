<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'type', 'academic_session_id', 'class_level', 'term_id', 'max_no_of_questions', 'complete_score','duration'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function scholarshipCategories()
    {
        return $this->belongsToMany(ScholarshipCategory::class, 'scholarship_category_test')
                    ->withTimestamps();
    }
    public function testGrades()
    {
        return $this->hasMany(TestGrade::class);
    }

}
