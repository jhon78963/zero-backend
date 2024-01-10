<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public $timestamps = false;

    public function teacherClassrooms()
    {
        return $this->hasMany(TeacherClassroom::class, 'teacher_id');
    }

    public function teacherCourses()
    {
        return $this->hasMany(TeacherCourse::class, 'teacher_id');
    }
}
