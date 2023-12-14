<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->datetime('CreationTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('CreatorUserId')->nullable();
            $table->integer('TenantId')->nullable();
            $table->unsignedBigInteger('roleId')->nullable();
            $table->foreign('roleId')->references('id')->on('roles');
            $table->unsignedBigInteger('userId')->nullable();
            $table->foreign('userId')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropForeign(['roleId']);
            $table->dropColumn('roleId');
            $table->dropForeign(['userId']);
            $table->dropColumn('userId');
        });
    }
};
