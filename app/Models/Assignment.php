<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'course_id',
        'name',
        'description',
        'due_date',
    ];

    /**
     * Get the course associated with the assignment.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function classSection()
    {
        return $this->belongsTo(SchoolClassSection::class, 'class_section_id');
    }
    /**
     * Get the grades associated with the assignment.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'assignment_id');
    }
}
