<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('academic_calendars', function (Blueprint $table) {
            $table->id();
            $table->datetime('CreationTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('CreatorUserId')->nullable();
            $table->datetime('LastModificationTime')->nullable();
            $table->integer('LastModifierUserId')->nullable();
            $table->boolean('IsDeleted')->default(false);
            $table->integer('DeleterUserId')->nullable();
            $table->datetime('DeletionTime')->nullable();
            $table->integer('TenantId')->nullable();
            $table->unsignedBigInteger('responsible_person');
            $table->string('activity');
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->string('duration_activity')->nullable();
            $table->boolean('status')->default(false);
            $table->foreign('responsible_person')->references('id')->on('roles');
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_calendars');
    }
};
