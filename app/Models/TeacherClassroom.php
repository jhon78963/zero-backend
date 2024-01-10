<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherClassroom extends Model
{
    use HasFactory;

    protected $table = 'teacher_classrooms';

    protected $guarded = [''];

    public $timestamps = false;

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class, 'classroom_id');
    }
}
