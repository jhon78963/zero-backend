<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class SectionSeeder extends Seeder
{
    private $academic_period;

    public function __construct()
    {
        $this->academic_period = View::shared('academic_period');
    }

    public function run()
    {
        $section = new Section();
        $section->description = 'A';
        $section->TenantId = $this->academic_period->id;
        $section->save();

        $section = new Section();
        $section->description = 'B';
        $section->TenantId = $this->academic_period->id;
        $section->save();

        $section = new Section();
        $section->description = 'C';
        $section->TenantId = $this->academic_period->id;
        $section->save();
    }
}
