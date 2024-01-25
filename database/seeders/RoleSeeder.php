<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $role = new Role();
        $role->name = 'Admin';
        $role->isStatic = True;
        $role->save();

        $role = new Role();
        $role->name = 'Secretaria';
        $role->isStatic = false;
        $role->save();

        $role = new Role();
        $role->name = 'Docente';
        $role->isStatic = false;
        $role->save();

        $role = new Role();
        $role->name = 'Estudiante';
        $role->isStatic = false;
        $role->save();

        $role = new Role();
        $role->name = 'Dirección Académica';
        $role->isStatic = false;
        $role->save();
    }
}
