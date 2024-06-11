<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipCategory extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'required_viewed_lessons', 'reward_amount', 'description', 'required_connects', 'scholarship_id', 'start_date', 'end_date'];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'scholarship_category_test')
                    ->withTimestamps();
    }

    public function students()
{
    return $this->belongsToMany(User::class, 'scholarship_category_user')
                ->withPivot('avg_score', 'passed')
                ->withTimestamps();
}

}
