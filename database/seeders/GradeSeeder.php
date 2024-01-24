<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class GradeSeeder extends Seeder
{
    public function run()
    {
        $grade = new Grade();
        $grade->description = '1er grado';
        $grade->TenantId = 1;
        $grade->save();

        $grade = new Grade();
        $grade->description = '2do grado';
        $grade->TenantId = 1;
        $grade->save();

        $grade = new Grade();
        $grade->description = '3ero grado';
        $grade->TenantId = 1;
        $grade->save();

        $grade = new Grade();
        $grade->description = '4to grado';
        $grade->TenantId = 1;
        $grade->save();

        $grade = new Grade();
        $grade->description = '5to grado';
        $grade->TenantId = 1;
        $grade->save();

        $grade = new Grade();
        $grade->description = '6to grado';
        $grade->TenantId = 1;
        $grade->save();
    }
}
