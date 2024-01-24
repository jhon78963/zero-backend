<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SyllabusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Docente,pages.silabus')->only(['index']);
    }

    public function index($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        return view('academic.silabus.teacher.index', compact('period'));
    }
}
