<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('ticket_id', 10)->nullable()->unique()->after('id');
        });

        DB::table('reports')->orderBy('id')->each(function (object $report): void {
            $ticketId = 'TKT-' . strtoupper(str_pad(base_convert((string) $report->id, 10, 36), 6, '0', STR_PAD_LEFT));
            DB::table('reports')->where('id', $report->id)->update(['ticket_id' => $ticketId]);
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropUnique(['ticket_id']);
            $table->dropColumn('ticket_id');
        });
    }
};
