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

        $currentDate = Carbon::now();

        $calendar = AcademicCalendar::where('start', '<=', $currentDate)
            ->where('end', '>=', $currentDate)
            ->first();

        View::share('calendar_global', $calendar);
    }
}
