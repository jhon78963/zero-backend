<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('classroom_student_attendance', function (Blueprint $table) {
            $table->id();
            $table->datetime('CreationTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('CreatorUserId')->nullable();
            $table->integer('TenantId')->nullable();
            $table->enum('status', ['PRESENTE', 'TARDANZA', 'FALTA', 'FALTA JUSTIFICADA']);
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('attendance_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('attendance_id')->references('id')->on('classroom_attendances');
        });
    }

    public function down()
    {
        Schema::dropIfExists('classroom_student_attendance');
    }
};
