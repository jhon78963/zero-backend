<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherCourse extends Model
{
    use HasFactory;

    protected $table = 'teacher_courses';

    protected $guarded = [''];

    public $timestamps = false;

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
