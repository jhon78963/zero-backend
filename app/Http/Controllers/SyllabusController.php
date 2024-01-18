<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SyllabusController extends Controller
{
    private $academic_period;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Docente,pages.silabus')->only(['index']);
        $this->academic_period = View::shared('academic_period');
    }

    public function index()
    {
        return view('academic.silabus.teacher.index');
    }
}
