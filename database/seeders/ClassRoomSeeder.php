<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class ClassRoomSeeder extends Seeder
{
    private $academic_period;

    public function __construct()
    {
        $this->academic_period = View::shared('academic_period');
    }

    public function run()
    {
        $class_room = new ClassRoom();
        $class_room->grade_id = 1;
        $class_room->section_id  = 1;
        $class_room->description = '1er grado A';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 1;
        $class_room->section_id  = 2;
        $class_room->description = '1er grado B';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 1;
        $class_room->section_id  = 3;
        $class_room->description = '1er grado C';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 2;
        $class_room->section_id  = 1;
        $class_room->description = '2do grado A';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 2;
        $class_room->section_id  = 2;
        $class_room->description = '2do grado B';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 2;
        $class_room->section_id  = 3;
        $class_room->description = '2do grado C';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 3;
        $class_room->section_id  = 1;
        $class_room->description = '3ro grado A';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 3;
        $class_room->section_id  = 2;
        $class_room->description = '3ro grado B';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 3;
        $class_room->section_id  = 3;
        $class_room->description = '3ro grado C';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 4;
        $class_room->section_id  = 1;
        $class_room->description = '4to grado A';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 4;
        $class_room->section_id  = 2;
        $class_room->description = '4to grado B';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 4;
        $class_room->section_id  = 3;
        $class_room->description = '4to grado C';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 5;
        $class_room->section_id  = 1;
        $class_room->description = '5to grado A';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 5;
        $class_room->section_id  = 2;
        $class_room->description = '5to grado B';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 5;
        $class_room->section_id  = 3;
        $class_room->description = '5to grado C';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 6;
        $class_room->section_id  = 1;
        $class_room->description = '6to grado A';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 6;
        $class_room->section_id  = 2;
        $class_room->description = '6to grado B';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();

        $class_room = new ClassRoom();
        $class_room->grade_id = 6;
        $class_room->section_id  = 3;
        $class_room->description = '6to grado C';
        $class_room->limit = 25;
        $class_room->students_number = 0;
        $class_room->TenantId = $this->academic_period->id;
        $class_room->save();
    }
}
