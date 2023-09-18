<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->string('responsible_person');
            $table->string('activity');
            $table->date('start');
            $table->date('end');
            $table->string('duration_activity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_calendars');
    }
};