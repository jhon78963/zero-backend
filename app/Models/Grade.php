<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';

    protected $guarded = [''];

    public $timestamps = false;

    public function courseGrades()
    {
        return $this->hasMany(CourseGrade::class, 'grade_id');
    }
}
