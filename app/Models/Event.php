<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'banner_picture',
        'school_id',
        'academic_session_id',
        'term_id',
    ];
    protected $dates = ['start_date', 'end_date'];


    /**
     * Get the school associated with the event.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the academic session associated with the event.
     */
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    /**
     * Get the term associated with the event.
     */
    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    public function scopeSearch($query, $term)
    {
        return $query->where('title', 'like', "%$term%")
                     ->orWhere('description', 'like', "%$term%")
                     ->orWhere('location', 'like', "%$term%");
    }
}
