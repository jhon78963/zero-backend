<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    use HasFactory;

    protected $table = 'student_payments';

    protected $guarded = [''];

    public $timestamps = false;

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
