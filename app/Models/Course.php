<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $guarded = [''];

    public $timestamps = false;

    public function courseGrades()
    {
        return $this->hasMany(CourseGrade::class, 'course_id');
    }

    public function teacherCourses()
    {
        return $this->hasMany(TeacherCourse::class, 'course_id');
    }
}
