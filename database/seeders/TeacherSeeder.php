<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class TeacherSeeder extends Seeder
{
    private $academic_period;

    public function __construct()
    {
        $this->academic_period = View::shared('academic_period');
    }

    public function run()
    {
        $teacher = new Teacher();
        $teacher->first_name = 'Juan';
        $teacher->surname = 'Perez';
        $teacher->dni = '12345678';
        $teacher->code = '1231';
        $teacher->institutional_email = 'jperez@sage.edu.pe';
        $teacher->type = 'GENERAL';
        $teacher->TenantId = $this->academic_period->id;
        $teacher->save();

        $teacher = new Teacher();
        $teacher->first_name = 'Julio';
        $teacher->surname = 'Peralta';
        $teacher->dni = '12345678';
        $teacher->code = '1232';
        $teacher->institutional_email = 'jperalta@sage.edu.pe';
        $teacher->type = 'AREA';
        $teacher->TenantId = $this->academic_period->id;
        $teacher->save();
    }
}
