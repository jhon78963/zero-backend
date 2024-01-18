<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AcedemicSilabusController extends Controller
{
    private $academic_period;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin-Secretaria,pages.silabus')->only(['index']);
        $this->academic_period = View::shared('academic_period');
    }

    public function index()
    {
        return view('academic.silabus.admin.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
