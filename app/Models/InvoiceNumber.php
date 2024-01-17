<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceNumber extends Model
{
    use HasFactory;
    protected $table = 'invoice_number';
    protected $guarded = [''];
}
