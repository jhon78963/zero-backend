<?php

namespace App\Providers;

use App\Models\AcademicCalendar;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\AcademicPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::share('academic_periods', AcademicPeriod::where('status', true)->get());

        $currentDate = now()->format('Y-m-d');

        $calendar_matriculas = AcademicCalendar::where('start', '<=', $currentDate)
            ->where('end', '>=', $currentDate)
            ->where('responsible_person', 2)
            ->first();

        $calendar_notas = AcademicCalendar::where('start', '<=', $currentDate)
            ->where('end', '>=', $currentDate)
            ->where('responsible_person', 3)
            ->first();

        View::share('calendar_matriculas', $calendar_matriculas);
        View::share('calendar_notas', $calendar_notas);
    }
}
