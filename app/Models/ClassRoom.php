<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'class_rooms';

    protected $guarded = [''];

    public $timestamps = false;

    public function teacherClassrooms()
    {
        return $this->hasMany(TeacherClassroom::class, 'classroom_id');
    }
}
