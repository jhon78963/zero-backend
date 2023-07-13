<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserRole;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $user_role = new UserRole();
        $user_role->userId = 1;
        $user_role->roleId = 1;
        $user_role->save();
    }
}
