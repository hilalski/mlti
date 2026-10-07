<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zoom_rooms', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('zoom_room');
            $table->timestamps();
        });

        DB::table('zoom_rooms')->insert([
            ['id' => 1, 'zoom_room' => 'Ruang Zoom 1', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'zoom_room' => 'Ruang Zoom 2', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('id_zoom_room')->nullable()->after('room_id');
            $table->foreign('id_zoom_room')->references('id')->on('zoom_rooms')->nullOnDelete();
        });

        // Preserve room assignments made before the relation was introduced.
        DB::table('bookings')->whereNotNull('zoom_room')->orderBy('id')->each(function (object $booking): void {
            $zoomRoomId = DB::table('zoom_rooms')->where('zoom_room', $booking->zoom_room)->value('id');
            if ($zoomRoomId) {
                DB::table('bookings')->where('id', $booking->id)->update(['id_zoom_room' => $zoomRoomId]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['id_zoom_room']);
            $table->dropColumn('id_zoom_room');
        });

        Schema::dropIfExists('zoom_rooms');
    }
};
