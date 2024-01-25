<?php

namespace Database\Seeders;

use App\Models\AcademicCalendar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicCalendarSeeder extends Seeder
{
    public function run()
    {
        $academic_calendar = new AcademicCalendar();
        $academic_calendar->responsible_person = 5;
        $academic_calendar->activity = 'Apertura del periodo académico';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->responsible_person = 2;
        $academic_calendar->activity = 'Recepción de solicitud de vacantes';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 2;
        $academic_calendar->activity = 'Matrículas';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 5;
        $academic_calendar->activity = 'Preparación de inicio de clases escolares';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 5;
        $academic_calendar->activity = 'I Bimestre';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 4;
        $academic_calendar->activity = 'Vacaciones de otoño';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 3;
        $academic_calendar->activity = 'Subida de notas I Bimestre';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 5;
        $academic_calendar->activity = 'II Bimestre';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 4;
        $academic_calendar->activity = 'Vacaciones de invierno';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 3;
        $academic_calendar->activity = 'Subida de notas II Bimestre';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 5;
        $academic_calendar->activity = 'III Bimestre';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 4;
        $academic_calendar->activity = 'Vacaciones de primavera';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 3;
        $academic_calendar->activity = 'Subida de notas III Bimestre';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 5;
        $academic_calendar->activity = 'IV Bimestre';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 3;
        $academic_calendar->activity = 'Subida de notas IV Bimestre';
        $academic_calendar->status = 0;
        $academic_calendar->save();

        $academic_calendar = new AcademicCalendar();
        $academic_calendar->TenantId = 1;
        $academic_calendar->responsible_person = 5;
        $academic_calendar->activity = 'Clausura del año escolar';
        $academic_calendar->status = 0;
        $academic_calendar->save();

    }
}
