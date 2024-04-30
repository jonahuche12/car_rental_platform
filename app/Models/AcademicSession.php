<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the terms associated with the academic session.
     */
    public function terms()
    {
        return $this->hasMany(Term::class);
    }
}
