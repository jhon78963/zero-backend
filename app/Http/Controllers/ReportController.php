<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        return view('reports.index', compact('period'));
    }
}
