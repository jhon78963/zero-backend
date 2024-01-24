<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $payment = new Payment();
        $payment->description = 'Cuota de ingreso';
        $payment->cost = 1600;
        $payment->save();

        $payment = new Payment();
        $payment->description = 'Matricula';
        $payment->cost = 750;
        $payment->save();

        $payment = new Payment();
        $payment->description = 'Pensión Marzo';
        $payment->cost = 750;
        $payment->save();

        $payment = new Payment();
        $payment->description = 'Pensión Abril';
        $payment->cost = 750;
        $payment->save();

        $payment = new Payment();
        $payment->description = 'Pensión Mayo';
        $payment->cost = 750;
        $payment->save();

        $payment = new Payment();
        $payment->description = 'Pensión Junio';
        $payment->cost = 750;
        $payment->save();

        $payment = new Payment();
        $payment->description = 'Pensión Julio';
        $payment->cost = 750;
        $payment->save();

        $payment = new Payment();
        $payment->description = 'Pensión Agosto';
        $payment->cost = 750;
        $payment->save();

        $payment = new Payment();
        $payment->description = 'Pensión Septiembre';
        $payment->cost = 750;
        $payment->save();

        $payment = new Payment();
        $payment->description = 'Pensión Octubre';
        $payment->cost = 750;
        $payment->save();

        $payment = new Payment();
        $payment->description = 'Pensión Noviembre';
        $payment->cost = 750;
        $payment->save();

        $payment = new Payment();
        $payment->description = 'Pensión Diciembre';
        $payment->cost = 750;
        $payment->save();
    }
}
