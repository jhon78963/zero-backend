<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $course = new Course();
        $course->description = 'Comunicación';
        $course->type = 'GENERAL';
        $course->TenantId = 1;
        $course->save();

        $course = new Course();
        $course->description = 'Matemática';
        $course->type = 'GENERAL';
        $course->TenantId = 1;
        $course->save();

        $course = new Course();
        $course->description = 'Ciencia y Tecnología';
        $course->type = 'GENERAL';
        $course->TenantId = 1;
        $course->save();

        $course = new Course();
        $course->description = 'Personal Social';
        $course->type = 'GENERAL';
        $course->TenantId = 1;
        $course->save();

        $course = new Course();
        $course->description = 'Arte y cultura';
        $course->type = 'AREA';
        $course->TenantId = 1;
        $course->save();

        $course = new Course();
        $course->description = 'Educación Religiosa';
        $course->type = 'AREA';
        $course->TenantId = 1;
        $course->save();

        $course = new Course();
        $course->description = 'Educación física';
        $course->type = 'AREA';
        $course->TenantId = 1;
        $course->save();

        $course = new Course();
        $course->description = 'Inglés';
        $course->type = 'AREA';
        $course->TenantId = 1;
        $course->save();
    }
}
