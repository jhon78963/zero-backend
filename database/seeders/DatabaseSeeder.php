<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(AcademicPeriodSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(SectionSeeder::class);
        $this->call(ClassRoomSeeder::class);
    }
}
