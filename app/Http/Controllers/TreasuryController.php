<?php

namespace App\Http\Controllers;

use App\Models\Treasury;
use App\Models\TreasuryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class TreasuryController extends Controller
{
    private $academic_period;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin,pages.calendar')->only(['index']);
        $this->middleware('check.permissions:Admin,pages.calendar.modify')->only(['store']);
        $this->academic_period = View::shared('academic_period');
    }

    public function index()
    {
        $treasuries = DB::table('treasury_detail as td')->join('treasuries as t', 't.id', 'td.treasury_id')
            ->where('t.TenantId', $this->academic_period->id)
            ->select('t.*', 'td.concepto')
            ->get();
        return view('treasury.index', compact('treasuries'));
    }

    public function create(){
        return view('treasury.create');
    }
}
