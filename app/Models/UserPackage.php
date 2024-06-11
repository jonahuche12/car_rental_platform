<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'picture',
        'price',
        'duration_in_days',
        'max_lessons_per_day',
        'max_uploads',
      
    ];

    
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function getUserUsagePercentage()
    {
        $totalUsers = $this->users()->count();

        if ($totalUsers > 0) {
            // Calculate the percentage based on the total number of schools
            $percentage = $totalUsers /   100;

            // Ensure the percentage does not exceed 100
            return min(100, $percentage);
        }

        return 0; // Default to 0 if there are no schools using the package
    }

}
