<?php

namespace App\Http\Controllers;

use App\Models\InvoiceNumber;
use App\Models\SchoolRegistration;
use App\Models\Treasury;
use App\Models\TreasuryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $treasuries = DB::table('treasury_detail as td')
            ->join('treasuries as t', 't.id', 'td.treasury_id')
            ->join('students as s', 's.id', 't.student_id')
            ->where('t.TenantId', $this->academic_period->id)
            ->where('t.IsDeleted', false)
            ->select(
                't.*',
                'td.concepto',
                'td.monto_total',
                's.first_name as student_first_name',
                's.other_names as student_other_names',
                's.surname as student_surname',
                's.mother_surname as student_mother_surname'
            )
            ->get();

        return view('treasury.index', compact('treasuries'));
    }

    public function create()
    {
        $invoice = InvoiceNumber::find(1);
        $students = DB::table('school_registration as sr')
            ->join('students as s', 's.id', 'sr.student_id')
            ->where('sr.TenantId', $this->academic_period->id)
            ->select('s.*')
            ->get();

        return view('treasury.create', compact('invoice', 'students'));
    }

    public function store(Request $request)
    {
        $treasury = new Treasury();
        $treasury->codigo_operacion = $request->serie . $request->numero;
        $treasury->serie = $request->serie;
        $treasury->numero = $request->numero;
        $treasury->numero_documento_cliente = $request->numero_documento_cliente;
        $treasury->ruc_emisor = '20086468392';
        $treasury->nombre_comercial_emisor = 'San Gerardo SAC';
        $treasury->direccion_emisor = 'Mz. X 1 Lt. 15 Urb. Santa Inés - Trujillo';
        $treasury->tipo_documento_cliente = '1';
        $treasury->numero_documento_cliente = $request->numero_documento_cliente;
        $treasury->nombre_cliente = $request->nombre_cliente;
        $treasury->direccion_cliente = $request->direccion_cliente;
        $treasury->tipo_moneda = 'PEN';
        $treasury->fecha_emision = now()->format('Y-m-d H:i:s');
        $treasury->hora_emision = now()->format('H:i:s');
        $treasury->porcentaje_igv = 18;
        $treasury->total_igv = 0;
        $treasury->total = 0;
        $treasury->student_id = $request->student_id;
        $treasury->TenantId = $this->academic_period->id;
        $treasury->CreatorUserId = Auth::id();
        $treasury->save();

        for ($i = 0; $i < count($request->description); $i++) {
            $treasury_detail = new TreasuryDetail();
            $treasury_detail->cantidad = $request->quantity[$i];
            $treasury_detail->concepto = $request->description[$i];
            $treasury_detail->monto = $request->price[$i];
            $treasury_detail->monto_total = $request->total[$i];
            $treasury_detail->treasury_id = $treasury->id;
            $treasury_detail->TenantId = $this->academic_period->id;
            $treasury_detail->CreatorUserId = Auth::id();
            $treasury_detail->save();

            if ($treasury_detail) {
                DB::table('treasuries')->where('id', $treasury->id)->increment('total', $request->total[$i]);
                DB::table('treasuries')->where('id', $treasury->id)->increment('total_igv', $request->total[$i] * 0.18);
            }
        }

        if ($treasury) {
            $invoice = InvoiceNumber::find(1);
            $invoice->initial_number += 1;
            $invoice->save();
        }

        return redirect()->route('treasuries.index', $this->academic_period->name);
    }

    public function cancel($id)
    {
        $treasury = Treasury::findOrFail($id);
        $treasury->IsDeleted = true;
        $treasury->DeleterUserId = Auth::id();
        $treasury->DeletionTime = now()->format('Y-m-d H:i:s');
        $treasury->save();

        return redirect()->route('treasuries.index', $this->academic_period->name);
    }
}
