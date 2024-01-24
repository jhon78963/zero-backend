<?php

namespace Database\Seeders;

use App\Models\TeacherClassroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class TeacherClassroomSeeder extends Seeder
{
    public function run()
    {
        $teacher_classroom = new TeacherClassroom();
        $teacher_classroom->teacher_id = 1;
        $teacher_classroom->classroom_id = 1;
        $teacher_classroom->TenantId = 1;
        $teacher_classroom->save();
    }
}
