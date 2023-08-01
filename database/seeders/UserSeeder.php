<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // desarrollador 1
        $user = new User;
        $user->username = 'bigzero';
        $user->email = 'bigzeroacc@gmail.com';
        $user->name = 'jhon';
        $user->surname = 'Livias';
        $user->password = Hash::make('123456789');
        $user->phoneNumber = '973835639';
        $user->save();

        // desarrollador 2
        $user = new User;
        $user->username = 'tiburonsin';
        $user->email = 'tiburonsin@gmail.com';
        $user->name = 'Victor';
        $user->surname = 'Leon';
        $user->password = Hash::make('123456789');
        $user->phoneNumber = '950041919';
        $user->save();

    }
}
