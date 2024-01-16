<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treasury extends Model
{
    use HasFactory;
    protected $table = 'treasuries';
    protected $primaryKey = 'treasury_id';
    protected $guarded = [''];
    public $timestamps = false;

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
