<?php

namespace Database\Seeders;

use App\Models\AcademicPeriod;
use App\Models\InvoiceNumber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicPeriodSeeder extends Seeder
{
    public function run()
    {
        $year = now()->format('Y');
        $academic_period = new AcademicPeriod();
        $academic_period->CreatorUserId = 1;
        $academic_period->IsDeleted = 0;
        $academic_period->TenantId = 1;
        $academic_period->name = 'pa-' . $year;
        $academic_period->year = $year;
        $academic_period->yearName = '';
        $academic_period->save();

        $invoice = new InvoiceNumber();
        $invoice->TenantId = 1;
        $invoice->type = 'boleta electrónica';
        $invoice->serie = 1001;
        $invoice->initial_number = 1;
        $invoice->invoicing_started = '1';
        $invoice->status = 'ANULADO';
         $invoice->save();
    }
}
