<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_classroom', function (Blueprint $table) {
            $table->id();
            $table->datetime('CreationTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('CreatorUserId')->nullable();
            $table->integer('TenantId')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('classroom_id');
            $table->enum('grade_final', ['PROMOVIDO', 'REPITENTE', 'PERMANENCIA', 'EN CURSO'])->default('EN CURSO');
            $table->string('grade_extension');
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('classroom_id')->references('id')->on('class_rooms');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_classroom');
    }
};
