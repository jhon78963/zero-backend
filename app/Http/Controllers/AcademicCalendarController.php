<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicCalendar;
use Carbon\Carbon;

class AcademicCalendarController extends Controller
{
    private $academic_period;

    public function __construct()
    {
        $this->academic_period = view()->shared('academic_period');
    }

    public function index()
    {
        $academic_calendars = AcademicCalendar::where('TenantId',$this->academic_period->id)->get();
        return view('academic.calendar', compact('academic_calendars'));
    }

    public function store(Request $request)
    {
        if (AcademicCalendar::all()->count()) {
            $last_calendar_id = AcademicCalendar::all()->last()->id+1;
        } else {
            $last_calendar_id = 1;
        }

        $fechaInicio = Carbon::parse($request->start);
        $fechaFin = Carbon::parse($request->end);
        $duration_activity = ($fechaInicio->diffInDays($fechaFin) + 1 == 1) ? ($fechaInicio->diffInDays($fechaFin) + 1).' día' : ($fechaInicio->diffInDays($fechaFin) + 1).' días';

        AcademicCalendar::create([
            'TenantId' => $this->academic_period->id,
            'responsible_person' => $request->responsible_person,
            'activity' => $request->activity,
            'start' => $request->start,
            'end' => $request->end,
            'duration_activity' => $duration_activity,
            'CreatorUserId' => Auth::id()
        ]);

        return back();
    }
}