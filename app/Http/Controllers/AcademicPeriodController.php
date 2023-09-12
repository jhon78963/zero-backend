<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AcademicPeriodController extends Controller
{
    public function index()
    {
        return view('setup.academic-period');
    }
}
