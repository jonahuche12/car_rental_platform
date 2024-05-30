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

    // public function setImagesAttribute($value)
    // {
    //     if (is_array($value)) {
    //         $existingImages = isset($this->attributes['images']) ? explode(',', $this->attributes['images']) : [];
    //         $newImages = array_merge($existingImages, $value);
    //         $this->attributes['images'] = implode(',', array_unique($newImages));
    //     } else {
    //         $this->attributes['images'] = null;
    //     }
    // }
    public function setImagesAttribute($value)
    {
        $this->attributes['images'] = $value ? implode(',', $value) : null;
    }
}
