<?php

namespace Database\Seeders;

use App\Models\AcademicPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicPeriodSeeder extends Seeder
{
    public function run()
    {
        $academic_period = new AcademicPeriod();
        $academic_period->CreatorUserId = 1;
        $academic_period->IsDeleted = 0;
        $academic_period->TenantId = 1;
        $academic_period->name = 'pa-2023';
        $academic_period->year = '2023';
        $academic_period->yearName = 'Año de la unidad, la paz y el desarrollo';
        $academic_period->save();
    }
}
