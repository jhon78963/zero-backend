<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicPeriod;
use Carbon\Carbon;


class AcademicPeriodController extends Controller
{
    public function index()
    {
        $academic_periods = AcademicPeriod::where('IsDeleted', false)->get();
        return view('setup.academic-period', compact('academic_periods'));
    }

    public function store(Request $request)
    {
        $currentYear = Carbon::now()->year;

        if (AcademicPeriod::all()->count()) {
            $last_period_id = AcademicPeriod::all()->last()->id+1;
        } else {
            $last_period_id = 1;
        }

        AcademicPeriod::create([
            'id' => $last_period_id,
            'TenantId' => $last_period_id,
            'name' => $request->name,
            'year' => $currentYear,
            'yearName' => $request->yearName,
            'CreatorUserId' => Auth::id(),
        ]);

        return back();
    }

    public function home($id)
    {
        $diaActual = Carbon::now('America/Lima')->locale('es')->isoFormat('dddd');
        $fechaActual = Carbon::now('America/Lima')->locale('es')->isoFormat("MMMM D, YYYY");
        return view('academic.period', compact('diaActual', 'fechaActual'));
    }
}