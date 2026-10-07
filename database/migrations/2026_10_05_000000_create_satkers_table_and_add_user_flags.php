<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satkers', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('satker');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('is_umum')->default(0)->after('is_jarkom');
            $table->unsignedBigInteger('id_satker')->nullable()->after('id_ruang');

            $table->foreign('id_satker')->references('id')->on('satkers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_satker']);
            $table->dropColumn(['is_umum', 'id_satker']);
        });

        Schema::dropIfExists('satkers');
    }
};
