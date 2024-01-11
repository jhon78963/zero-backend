<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCompetencia extends Model
{
    use HasFactory;

    protected $table = 'course_competencias';

    protected $guarded = [''];

    public $timestamps = false;
}
