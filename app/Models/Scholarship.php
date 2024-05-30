<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'published', 'class_level', 'academic_session_id', 'term_id'];

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function categories()
    {
        return $this->hasMany(ScholarshipCategory::class);
    }
}
