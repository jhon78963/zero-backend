<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('treasuries', function (Blueprint $table) {
            $table->id();
            $table->datetime('CreationTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('CreatorUserId')->nullable();
            $table->datetime('LastModificationTime')->nullable();
            $table->integer('LastModifierUserId')->nullable();
            $table->boolean('IsDeleted')->default(false);
            $table->integer('DeleterUserId')->nullable();
            $table->datetime('DeletionTime')->nullable();
            $table->integer('TenantId')->nullable();
            $table->string('codigo_operacion')->unique();
            $table->string('serie');
            $table->integer('numero');
            $table->string('ruc_emisor');
            $table->string('nombre_comercial_emisor');
            $table->string('direccion_emisor');
            $table->string('tipo_documento_cliente');
            $table->string('numero_documento_cliente');
            $table->string('nombre_cliente');
            $table->string('direccion_cliente');
            $table->string('tipo_moneda');
            $table->date('fecha_emision');
            $table->time('hora_emision');
            $table->decimal('porcentaje_igv');
            $table->decimal('total_igv');
            $table->decimal('total');
            $table->string('hash_cpe');
            $table->string('codigo_qr');

            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    public function down()
    {
        Schema::dropIfExists('treasuries');
    }
};
