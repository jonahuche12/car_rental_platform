<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    protected $fillable = ['theme', 'subject', 'description', 'class_level', 'country',];

    public function topics()
    {
        return $this->belongsToMany(Curriculum::class, 'curricula_topics')
            ->withPivot('id', 'topic', 'description');
    }

    public function getTopics()
    {
        return $this->topics()->get();
    }
}
