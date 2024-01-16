<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('razon_social');
            $table->string('ruc');
            $table->string('direccion');
            $table->string('logo_path')->nullable();

            $table->string('sol_user');
            $table->string('sol_pass');

            $table->string('cert_path');

            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();

            $table->boolean('production')->default(false);

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
