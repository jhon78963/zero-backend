<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class SectionSeeder extends Seeder
{
    public function run()
    {
        $section = new Section();
        $section->description = 'A';
        $section->TenantId = 1;
        $section->save();

        $section = new Section();
        $section->description = 'B';
        $section->TenantId = 1;
        $section->save();

        $section = new Section();
        $section->description = 'C';
        $section->TenantId = 1;
        $section->save();
    }
}
