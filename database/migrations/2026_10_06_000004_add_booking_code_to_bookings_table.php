<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_code', 10)->nullable()->unique()->after('id');
        });

        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        DB::table('bookings')->where('type', 'zoom')->whereNull('booking_code')->orderBy('id')->each(function (object $booking) use ($letters): void {
            do {
                $code = 'ZMT-';
                for ($index = 0; $index < 6; $index++) {
                    $code .= $letters[random_int(0, strlen($letters) - 1)];
                }
            } while (DB::table('bookings')->where('booking_code', $code)->exists());

            DB::table('bookings')->where('id', $booking->id)->update(['booking_code' => $code]);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique(['booking_code']);
            $table->dropColumn('booking_code');
        });
    }
};
