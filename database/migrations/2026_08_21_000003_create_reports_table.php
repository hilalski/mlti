<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('device_id');
            $table->bigInteger('reported_by');
            $table->enum('issue_type', ['hardware', 'software', 'jaringan']);
            $table->text('description');
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'ditolak'])->default('menunggu');
            $table->text('technician_notes')->nullable();
            $table->bigInteger('handled_by')->nullable();
            $table->unsignedBigInteger('id_vendor')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('reported_by')->references('nip_lama')->on('users')->onDelete('cascade');
            $table->foreign('handled_by')->references('nip_lama')->on('users')->onDelete('set null');
            $table->foreign('id_vendor')->references('id')->on('vendor_services')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
