<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    use HasFactory;

    protected $table = 'academic_calendars';

    protected $guarded = [''];

    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo(Role::class, 'responsible_person');
    }
}
