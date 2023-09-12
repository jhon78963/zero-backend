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
    }
}