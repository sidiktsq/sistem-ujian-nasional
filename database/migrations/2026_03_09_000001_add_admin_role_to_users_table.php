<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Update role column to include 'admin'
            $table->enum('role', ['admin', 'guru', 'murid'])->default('murid')->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['guru', 'murid'])->default('murid')->change();
        });
    }
};
