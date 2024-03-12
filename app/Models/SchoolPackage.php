<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolPackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'picture',
        'price',
        'duration_in_days',
        'max_students',
        'max_admins',
        'max_teachers',
        'max_classes',
        'is_active',
    ];

    public function schools()
    {
        return $this->hasMany(School::class);
    }

    public function getUsagePercentage()
    {
        $totalSchools = $this->schools()->count();

        if ($totalSchools > 0) {
            // Calculate the percentage based on the total number of schools
            $percentage = ($totalSchools / $this->max_students) * 100;

            // Ensure the percentage does not exceed 100
            return min(100, $percentage);
        }

        return 0; // Default to 0 if there are no schools using the package
    }

}
