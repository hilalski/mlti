<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('jenis');
            $table->timestamps();
        });

        Schema::create('conditions', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('kondisi');
            $table->timestamps();
        });

        Schema::create('sources', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('asal');
            $table->timestamps();
        });

        Schema::create('status_bmn', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('vendor_services', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('vendor_service');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_services');
        Schema::dropIfExists('status_bmn');
        Schema::dropIfExists('sources');
        Schema::dropIfExists('conditions');
        Schema::dropIfExists('types');
    }
};
