<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\InvoiceNumber;
use App\Models\Payment;
use App\Models\SchoolRegistration;
use App\Models\StudentPayment;
use App\Models\Treasury;
use App\Models\TreasuryDetail;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class TreasuryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['getPaymentByStudent']]);
        $this->middleware('check.permissions:Admin,pages.calendar')->only(['index']);
        $this->middleware('check.permissions:Admin,pages.calendar.modify')->only(['store']);
    }

    public function index($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $treasuries = DB::table('treasury_detail as td')
            ->join('treasuries as t', 't.id', 'td.treasury_id')
            ->join('students as s', 's.id', 't.student_id')
            ->join('payments as p', 'p.id', 'td.concepto')
            ->where('t.TenantId', $period->id)
            ->where('t.IsDeleted', false)
            ->select(
                't.*',
                'p.*',
                'td.concepto',
                'td.monto_total',
                's.first_name as student_first_name',
                's.other_names as student_other_names',
                's.surname as student_surname',
                's.mother_surname as student_mother_surname'
            )
            ->get();

        $payments = Payment::paginate(8);

        $current_month = now()->format('Y-m-d');
        $morosos = StudentPayment::join('students as s', 's.id', '=', 'student_payments.student_id')
            ->join('payments as p', 'p.id', '=', 'student_payments.payment_id')
            ->join('class_rooms as c', 'c.id', 'student_payments.classroom_id')
            ->where('student_payments.TenantId', $period->id)
            ->where('student_payments.isPaid', false)
            ->where('p.due_date', '<', $current_month)
            ->select('s.*', 'p.description', 'p.due_date', 'p.cost', 'c.description as classroom')
            ->distinct()
            ->get();

        return view('treasury.index', compact('treasuries', 'payments', 'period', 'morosos'));
    }

    public function generateMorososPDF($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $current_month = now()->format('Y-m-d');
        $morosos = StudentPayment::join('students as s', 's.id', '=', 'student_payments.student_id')
            ->join('payments as p', 'p.id', '=', 'student_payments.payment_id')
            ->join('class_rooms as c', 'c.id', 'student_payments.classroom_id')
            ->where('student_payments.TenantId', $period->id)
            ->where('student_payments.isPaid', false)
            ->where('p.due_date', '<', $current_month)
            ->select('s.*', 'p.description', 'p.due_date', 'p.cost', 'c.description as classroom')
            ->distinct()
            ->get();

        $pdf = DomPDF::loadView('treasury.pdf', compact('period', 'morosos'))->setPaper('a4')->setWarnings(false);
        return $pdf->stream('reporte-morosos.pdf');
    }

    public function create($period_name)
    {
        $period = AcademicPeriod::where('name', $period_name)->first();
        $invoice = InvoiceNumber::where('TenantId', $period->id)->first();
        $payments = Payment::all();
        $students = DB::table('school_registration as sr')
            ->join('students as s', 's.id', 'sr.student_id')
            ->where('sr.TenantId', $period->id)
            ->select('s.*')
            ->orderBy('s.surname')->orderBy('s.mother_surname')->orderBy('first_name')->orderBy('other_names')
            ->get();

        return view('treasury.create', compact('invoice', 'students', 'period', 'payments'));
    }

    public function getPaymentByStudent($period_id, $student_id)
    {
        $payments = StudentPayment::join('payments as p', 'p.id', 'student_payments.payment_id')
            ->where('student_payments.TenantId', $period_id)
            ->where('student_payments.student_id', $student_id)
            ->where('student_payments.isPaid', false)
            ->select('student_payments.*', 'p.description', 'p.cost', 'p.id as payment_id')
            ->get();

        return $payments;
    }

    public function store(Request $request, $period_id)
    {
        $period = AcademicPeriod::where('id', $period_id)->first();

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
        $treasury->TenantId = $period_id;
        $treasury->CreatorUserId = Auth::id();
        $treasury->save();

        for ($i = 0; $i < count($request->description); $i++) {
            list($id, $cost, $payment_id) = explode('_', $request->description[$i]);
            $treasury_detail = new TreasuryDetail();
            $treasury_detail->cantidad = 1;
            $treasury_detail->concepto = $payment_id;
            $treasury_detail->monto = $request->price[$i];
            $treasury_detail->monto_total = $request->total[$i];
            $treasury_detail->treasury_id = $treasury->id;
            $treasury_detail->TenantId = $period_id;
            $treasury_detail->CreatorUserId = Auth::id();
            $treasury_detail->save();

            if ($treasury_detail) {
                DB::table('treasuries')->where('id', $treasury->id)->increment('total', $request->total[$i]);
                DB::table('treasuries')->where('id', $treasury->id)->increment('total_igv', $request->total[$i] * 0.18);

                $student_payment = StudentPayment::find($id);
                $student_payment->isPaid = true;
                $student_payment->save();
            }
        }

        if ($treasury) {
            $invoice = InvoiceNumber::find(1);
            $invoice->initial_number += 1;
            $invoice->save();
        }

        return redirect()->route('treasuries.index', $period->name);
    }

    public function cancel($period_id, $id)
    {
        $period = AcademicPeriod::where('id', $period_id)->first();
        $treasury = Treasury::findOrFail($id);
        $treasury->IsDeleted = true;
        $treasury->DeleterUserId = Auth::id();
        $treasury->DeletionTime = now()->format('Y-m-d H:i:s');
        $treasury->save();

        return redirect()->route('treasuries.index', $period->name);
    }

    public function savePayment(Request $request)
    {
        $payment = new Payment();
        $payment->description = $request->description;
        $payment->cost = $request->cost;
        $payment->save();

        return back();
    }

    public function updatePayment(Request $request, $period_id, $payment_id)
    {
        $payment = Payment::findOrFail($payment_id);
        $payment->description = $request->description;
        $payment->cost = $request->cost;
        $payment->save();

        return back();
    }

    public function deletePayment(Request $request, $period_id, $payment_id)
    {
        Payment::findOrFail($payment_id)->delete();

        return back();
    }
}
