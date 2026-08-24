<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('id_type');
            $table->year('year')->nullable();
            $table->unsignedBigInteger('id_source');
            $table->string('brand')->nullable();
            $table->string('series')->nullable();
            $table->string('serial_number')->nullable();
            $table->unsignedBigInteger('id_status_bmn');
            $table->unsignedBigInteger('id_condition');
            $table->text('keterangan')->nullable();
            $table->bigInteger('id_user')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_type')->references('id')->on('types');
            $table->foreign('id_source')->references('id')->on('sources');
            $table->foreign('id_status_bmn')->references('id')->on('status_bmn');
            $table->foreign('id_condition')->references('id')->on('conditions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
