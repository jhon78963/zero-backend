<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class CourseSeeder extends Seeder
{
    private $academic_period;

    public function __construct()
    {
        $this->academic_period = View::shared('academic_period');
    }

    public function run()
    {
        $course = new Course();
        $course->description = 'Comunicación';
        $course->TenantId = $this->academic_period->id;
        $course->save();

        $course = new Course();
        $course->description = 'Matemática';
        $course->TenantId = $this->academic_period->id;
        $course->save();

        $course = new Course();
        $course->description = 'Ciencia y Tecnología';
        $course->TenantId = $this->academic_period->id;
        $course->save();

        $course = new Course();
        $course->description = 'Personal Social';
        $course->TenantId = $this->academic_period->id;
        $course->save();

        $course = new Course();
        $course->description = 'Arte y cultura';
        $course->TenantId = $this->academic_period->id;
        $course->save();

        $course = new Course();
        $course->description = 'Educación Religiosa';
        $course->TenantId = $this->academic_period->id;
        $course->save();

        $course = new Course();
        $course->description = 'Educación física';
        $course->TenantId = $this->academic_period->id;
        $course->save();

        $course = new Course();
        $course->description = 'Inglés';
        $course->TenantId = $this->academic_period->id;
        $course->save();
    }
}
