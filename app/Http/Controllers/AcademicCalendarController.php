<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicCalendar;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class AcademicCalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.permissions:Admin,pages.calendar')->only(['index']);
        $this->middleware('check.permissions:Admin,pages.calendar.modify')->only(['store']);
    }

    public function index()
    {
        $academic_calendars = AcademicCalendar::where('IsDeleted', false)->get();
        $roles = Role::where('IsDeleted', false)->get();
        return view('home.calendar', compact('academic_calendars', 'roles'));
    }

    public function store(Request $request)
    {
        $fechaInicio = Carbon::parse($request->start);
        $fechaFin = Carbon::parse($request->end);

        $diffInDays = $fechaInicio->diffInDays($fechaFin) + 1;

        if ($diffInDays <= 6) {
            $duration_activity = ($diffInDays == 1) ? $diffInDays . ' día' : $diffInDays . ' días';
        } else {
            $diffInWeeks = ceil($diffInDays / 7);
            $duration_activity = ($diffInWeeks == 1) ? $diffInWeeks . ' semana' : $diffInWeeks . ' semanas';
        }

        AcademicCalendar::create([
            'TenantId' => $request->TenantId,
            'responsible_person' => $request->responsible_person,
            'activity' => $request->activity,
            'start' => $request->start,
            'end' => $request->end,
            'duration_activity' => $duration_activity,
            'CreatorUserId' => Auth::id(),
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $fechaInicio = Carbon::parse($request->start);
        $fechaFin = Carbon::parse($request->end);

        $diffInDays = $fechaInicio->diffInDays($fechaFin) + 1;

        if ($diffInDays <= 6) {
            $duration_activity = ($diffInDays == 1) ? $diffInDays . ' día' : $diffInDays . ' días';
        } else {
            $diffInWeeks = ceil($diffInDays / 7);
            $duration_activity = ($diffInWeeks == 1) ? $diffInWeeks . ' semana' : $diffInWeeks . ' semanas';
        }

        AcademicCalendar::findOrFail($id)->update([
            'TenantId' => $request->TenantId,
            'responsible_person' => $request->responsible_person,
            'activity' => $request->activity,
            'start' => $request->start,
            'end' => $request->end,
            'duration_activity' => $duration_activity,
            'LastModifierUserId' => Auth::id(),
            'LastModificationTime' => now()->format('Y-m-d h:i:s'),
        ]);

        return back();
    }

    public function destroy($id)
    {
        AcademicCalendar::findOrFail($id)->update([
            'IsDeleted' => true,
            'DeleterUserId' => Auth::id(),
            'DeletionTime' => now()->format('Y-m-d h:i:s'),
        ]);

        return back();
    }
}
