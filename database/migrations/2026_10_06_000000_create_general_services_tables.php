<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_complaints', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reported_by');
            $table->string('location');
            $table->string('category');
            $table->text('description');
            $table->string('status')->default('menunggu');
            $table->timestamps();
            $table->foreign('reported_by')->references('nip_lama')->on('users')->cascadeOnDelete();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('requested_by');
            $table->string('type');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->string('title');
            $table->text('purpose');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('status')->default('menunggu');
            $table->timestamps();
            $table->foreign('requested_by')->references('nip_lama')->on('users')->cascadeOnDelete();
            $table->foreign('room_id')->references('id')->on('rooms')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('general_complaints');
    }
};
