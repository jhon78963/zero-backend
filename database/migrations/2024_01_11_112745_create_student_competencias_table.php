<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_competencias', function (Blueprint $table) {
            $table->id();
            $table->datetime('CreationTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('CreatorUserId')->nullable();
            $table->integer('TenantId')->nullable();
            $table->varchar('grade_b_1', 2)->nullable();
            $table->varchar('grade_b_2', 2)->nullable();
            $table->varchar('grade_b_3', 2)->nullable();
            $table->varchar('grade_b_4', 2)->nullable();
            $table->unsignedBigInteger('classroom_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_competencia_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('course_competencia_id')->references('id')->on('course_competencias');
            $table->foreign('classroom_id')->references('id')->on('class_rooms');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_competencias');
    }
};
