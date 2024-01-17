<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_number', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('serie')->nullable();
            $table->bigInteger('initial_number');
            $table->enum('invoicing_started', ['0', '1'])->default('1');
            $table->enum('status', ['ACTIVO', 'ANULADO'])->default('ACTIVO');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_number');
    }
};
