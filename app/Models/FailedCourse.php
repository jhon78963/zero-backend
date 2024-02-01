<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedCourse extends Model
{
    use HasFactory;
    protected $table = 'student_failed_course';
    protected $primaryKey = 'student_id';
    protected $guarded = [''];
    public $timestamps = false;
}
