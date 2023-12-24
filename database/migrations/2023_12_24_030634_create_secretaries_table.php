<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('secretaries', function (Blueprint $table) {
            $table->id();
            $table->datetime('CreationTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('CreatorUserId')->nullable();
            $table->datetime('LastModificationTime')->nullable();
            $table->integer('LastModifierUserId')->nullable();
            $table->boolean('IsDeleted')->default(false);
            $table->integer('DeleterUserId')->nullable();
            $table->datetime('DeletionTime')->nullable();
            $table->integer('TenantId')->nullable();
            $table->string('first_name', 15);
            $table->string('other_names', 30)->nullable();
            $table->string('surname', 15);
            $table->string('mother_surname', 15)->nullable();
            $table->string('dni', 8);
            $table->string('code', 4);
            $table->string('intitutional_email', 190);
            $table->string('phone', 9)->nullable();
            $table->string('address')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('secretaries');
    }
};
