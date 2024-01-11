<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCompetencia extends Model
{
    use HasFactory;

    protected $table = 'student_competencias';

    protected $guarded = [''];

    public $timestamps = false;
}
