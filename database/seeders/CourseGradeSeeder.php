<?php

namespace Database\Seeders;

use App\Models\CourseGrade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class CourseGradeSeeder extends Seeder
{
    private $academic_period;

    public function __construct()
    {
        $this->academic_period = View::shared('academic_period');
    }

    public function run()
    {
        $course_grade = new CourseGrade();
        $course_grade->course_id = 1;
        $course_grade->grade_id = 1;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 1;
        $course_grade->grade_id = 2;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 1;
        $course_grade->grade_id = 3;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 1;
        $course_grade->grade_id = 4;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 1;
        $course_grade->grade_id = 5;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 1;
        $course_grade->grade_id = 6;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 2;
        $course_grade->grade_id = 1;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 2;
        $course_grade->grade_id = 2;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 2;
        $course_grade->grade_id = 3;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 2;
        $course_grade->grade_id = 4;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 2;
        $course_grade->grade_id = 5;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 2;
        $course_grade->grade_id = 6;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 3;
        $course_grade->grade_id = 1;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 3;
        $course_grade->grade_id = 2;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 3;
        $course_grade->grade_id = 3;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 3;
        $course_grade->grade_id = 4;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 3;
        $course_grade->grade_id = 5;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 3;
        $course_grade->grade_id = 6;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 4;
        $course_grade->grade_id = 1;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 4;
        $course_grade->grade_id = 2;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 4;
        $course_grade->grade_id = 3;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 4;
        $course_grade->grade_id = 4;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 4;
        $course_grade->grade_id = 5;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 4;
        $course_grade->grade_id = 6;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 5;
        $course_grade->grade_id = 1;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 5;
        $course_grade->grade_id = 2;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 5;
        $course_grade->grade_id = 3;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 5;
        $course_grade->grade_id = 4;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 5;
        $course_grade->grade_id = 5;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 5;
        $course_grade->grade_id = 6;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 6;
        $course_grade->grade_id = 1;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 6;
        $course_grade->grade_id = 2;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 6;
        $course_grade->grade_id = 3;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 6;
        $course_grade->grade_id = 4;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 6;
        $course_grade->grade_id = 5;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 6;
        $course_grade->grade_id = 6;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 7;
        $course_grade->grade_id = 1;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 7;
        $course_grade->grade_id = 2;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 7;
        $course_grade->grade_id = 3;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 7;
        $course_grade->grade_id = 4;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 7;
        $course_grade->grade_id = 5;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 7;
        $course_grade->grade_id = 6;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 8;
        $course_grade->grade_id = 1;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 8;
        $course_grade->grade_id = 2;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 8;
        $course_grade->grade_id = 3;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 8;
        $course_grade->grade_id = 4;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 8;
        $course_grade->grade_id = 5;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();

        $course_grade = new CourseGrade();
        $course_grade->course_id = 8;
        $course_grade->grade_id = 6;
        $course_grade->TenantId = $this->academic_period->id;
        $course_grade->save();
    }
}
