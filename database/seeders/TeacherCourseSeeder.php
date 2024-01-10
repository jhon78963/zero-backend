<?php

namespace Database\Seeders;

use App\Models\TeacherCourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class TeacherCourseSeeder extends Seeder
{
    private $academic_period;

    public function __construct()
    {
        $this->academic_period = View::shared('academic_period');
    }

    public function run()
    {
        $teacher_course = new TeacherCourse();
        $teacher_course->teacher_id = 2;
        $teacher_course->course_id = 8;
        $teacher_course->TenantId = $this->academic_period->id;
        $teacher_course->save();
    }
}
