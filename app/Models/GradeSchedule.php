<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeSchedule extends Model
{
    use HasFactory;

    protected $table = 'grade_schedules';

    protected $guarded = [''];

    public $timestamps = false;

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
