<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicPeriod;
use App\Models\InvoiceNumber;
use Carbon\Carbon;


class AcademicPeriodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin,pages.period')->only(['index', 'home']);
        $this->middleware('check.permissions:Admin,pages.period.modify')->only(['store']);
    }

    public function periodHome()
    {
        $academic_periods = AcademicPeriod::where('IsDeleted', false)->get();
        return view('home.period', compact('academic_periods'));
    }

    public function index()
    {
        $academic_periods = AcademicPeriod::where('IsDeleted', false)->get();
        return view('setup.academic-period', compact('academic_periods'));
    }

    public function store(Request $request)
    {
        if (AcademicPeriod::all()->count()) {
            $last_period_id = AcademicPeriod::all()->last()->id + 1;
        } else {
            $last_period_id = 1;
        }

        AcademicPeriod::create([
            'id' => $last_period_id,
            'TenantId' => $last_period_id,
            'name' => 'pa-' . $request->name,
            'year' => $request->name,
            'yearName' => $request->yearName,
            'CreatorUserId' => Auth::id(),
        ]);

        InvoiceNumber::create([
            'type' => 'Boleta electrónica',
            'serie' => 'B00' . $last_period_id,
            'initial_number' => 1001,
            'invoicing_started' => '1',
            'status' => 'ACTIVO',
            'TenantId' => $last_period_id
        ]);

        return back();
    }

    public function home($period_name)
    {
        $diaActual = Carbon::now('America/Lima')->locale('es')->isoFormat('dddd');
        $fechaActual = Carbon::now('America/Lima')->locale('es')->isoFormat("MMMM D, YYYY");
        $period = AcademicPeriod::where('name', $period_name)->first();
        return view('academic.period', compact('diaActual', 'fechaActual', 'period'));
    }
}
