<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'course_id',
        'name',
        'description',
        'date',
    ];

    /**
     * Get the course associated with the assessment.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the grades associated with the assessment.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'assessment_id');
    }
}
