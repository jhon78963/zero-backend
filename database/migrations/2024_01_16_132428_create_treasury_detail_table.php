<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('treasury_detail', function (Blueprint $table) {
            $table->id();
            $table->datetime('CreationTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('CreatorUserId')->nullable();
            $table->integer('TenantId')->nullable();
            $table->integer('cantidad')->default(1);
            $table->string('concepto');
            $table->float('monto');
            $table->float('monto_total');

            $table->unsignedBigInteger('treasury_id');
            $table->foreign('treasury_id')->references('id')->on('treasuries');
        });
    }

    public function down()
    {
        Schema::dropIfExists('treasury_detail');
    }
};
