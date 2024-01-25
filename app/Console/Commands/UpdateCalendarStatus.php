<?php

namespace App\Console\Commands;

use App\Models\AcademicCalendar;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateCalendarStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:calendar-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar el estado de la tabla academic_calendar según la fecha actual';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentDate = Carbon::now();

        AcademicCalendar::where('start', '<=', $currentDate)
            ->where('end', '>=', $currentDate)
            ->update(['status' => 1]);
    }
}
