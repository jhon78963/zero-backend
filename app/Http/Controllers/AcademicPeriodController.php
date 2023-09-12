<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  App\Models\AcademicPeriod;
use  App\Models\Tenant;

class AcademicPeriodController extends Controller
{
    public function index()
    {
        return view('setup.academic-period');
    }

    public function store(Request $request)
    {
        if (AcademicPeriod::all()->count()) {
            $last_period_id = AcademicPeriod::all()->last()->id+1;
        } else {
            $last_period_id = 1;
        }

        AcademicPeriod::create([
            'id' => $last_period_id,
            'TenantId' => $last_period_id,
            'domain' => $request->domain,
            'yearName' => "hola",
            'CreatorUserId' => Auth::id(),
        ]);

        $tenant = Tenant::create([
            'id' => $last_period_id,
            'CreatorUserId' => Auth::id(),
        ]);

        $tenant->domains()->create(['domain' => $request->domain.'.localhost']);

        return back();
    }
}