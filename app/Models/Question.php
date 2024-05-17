<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['test_id', 'question', 'answer_type', 'images'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
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
