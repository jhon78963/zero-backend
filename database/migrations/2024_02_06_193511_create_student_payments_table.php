<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->datetime('CreationTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('CreatorUserId')->nullable();
            $table->integer('TenantId')->nullable();
            $table->unsignedBigInteger('classroom_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('payment_id');
            $table->boolean('isPaid')->default(false);
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('payment_id')->references('id')->on('payments');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_payments');
    }
};
