<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('device_id')->nullable()->change();
            $table->unsignedBigInteger('id_ruang')->nullable()->after('device_id');
            $table->foreign('id_ruang')->references('id')->on('rooms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['id_ruang']);
            $table->dropColumn('id_ruang');
            $table->string('device_id')->nullable(false)->change();
        });
    }
};
