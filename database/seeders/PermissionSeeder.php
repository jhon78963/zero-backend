<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permission = new Permission();
        $permission->name = 'pages.user';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.user.modify';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.user.delete';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.user.assign';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.role';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.role.modify';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.role.delete';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.period';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.period.modify';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.calendar';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.calendar.modify';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.silabus';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.silabus.modify';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.silabus.delete';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.silabus.upload';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.silabus.download';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.teacher';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.teacher.modify';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.teacher.delete';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.student';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.student.modify';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.student.delete';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.secretary';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.secretary.modify';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.secretary.delete';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.grades';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.grades.modify';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.grades.delete';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.section';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.section.modify';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.section.delete';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.classroom';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.classroom.modify';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.classroom.delete';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.course';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.course.modify';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.course.delete';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.course.assign';
        $permission->roleId = 1;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.period';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.period.modify';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.calendar';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.calendar.modify';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.silabus';
        $permission->roleId = 2;
        $permission->save();


        $permission = new Permission();
        $permission->name = 'pages.silabus.download';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.teacher';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.teacher.modify';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.teacher.delete';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.student';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.student.modify';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.student.delete';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.secretary';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.secretary.modify';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.secretary.delete';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.grades';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.grades.modify';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.grades.delete';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.section';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.section.modify';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.section.delete';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.classroom';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.classroom.modify';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.classroom.delete';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.course';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.course.modify';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.course.delete';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.course.assign';
        $permission->roleId = 2;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.silabus';
        $permission->roleId = 3;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.silabus.modify';
        $permission->roleId = 3;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.silabus.delete';
        $permission->roleId = 3;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.silabus.upload';
        $permission->roleId = 3;
        $permission->save();

        $permission = new Permission();
        $permission->name = 'pages.silabus.download';
        $permission->roleId = 3;
        $permission->save();
    }
}
