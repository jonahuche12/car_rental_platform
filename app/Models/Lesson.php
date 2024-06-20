<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'subject', 'description', 'thumbnail', 'video_url', 'school_connects_required', 'school_id', 'user_id'
    ];
    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'lesson_likes', 'lesson_id', 'user_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'lesson_favorite')
                    ->withTimestamps(); // Include if you have timestamps in the pivot table
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'lesson_user')->wherePivot('role', 'student');
    }

    public function enrolledUsers()
    {
        return $this->belongsToMany(User::class, 'lesson_user', 'lesson_id', 'user_id')
                    ->withPivot('role'); // Include the 'role' column from the pivot table if needed
    }
    public function schoolConnectsHistory()
    {
        // Get all enrolled users count
        $enrolledUsersCount = $this->enrolledUsers()->count();

        // Calculate the progression of school_connects_required over time
        $history = [];
        $currentRequired = 9; // Default value for school_connects_required

        // Initialize the history with the default state
        $history[] = [
            'enrolled_users_count' => 0,
            'school_connects_required' => $currentRequired,
        ];

        // Simulate the progression based on the number of enrolled users
        for ($i = 1; $i <= $enrolledUsersCount; $i++) {
            // Calculate new school_connects_required based on enrollment factor
            $enrollmentFactor = 0.1; // Example: Increase school_connects_required by 10% for each enrolled user
            $currentRequired *= (1 + $enrollmentFactor);

            // Store the current state of school_connects_required
            $history[] = [
                'enrolled_users_count' => $i,
                'school_connects_required' => $currentRequired,
            ];
        }

        return $history;
    }
   
  
    public function scopeSearch($query, $term)
    {
        return $query->where('title', 'like', "%$term%")
                     ->orWhere('description', 'like', "%$term%");
    }
    

}
