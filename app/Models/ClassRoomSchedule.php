<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoomSchedule extends Model
{
    use HasFactory;

    protected $table = 'classroom_schedules';

    protected $guarded = [''];

    public $timestamps = false;

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
