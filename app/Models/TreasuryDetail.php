<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreasuryDetail extends Model
{
    use HasFactory;
    protected $table = 'treasury_detail';
    protected $guarded = [''];
    public $timestamps = false;
}
