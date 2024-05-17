<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $fillable = ['question_id', 'answer', 'is_correct', 'images'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function getImagesAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }

    public function setImagesAttribute($value)
    {
        $this->attributes['images'] = $value ? implode(',', $value) : null;
    }
}
