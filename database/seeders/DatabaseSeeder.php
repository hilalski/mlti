<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\Device;
use App\Models\Type;
use App\Models\Condition;
use App\Models\Source;
use App\Models\StatusBmn;
use App\Models\VendorService;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->refreshJsonFromExcel();

        // 1. Seed Teams
        $teams = json_decode(file_get_contents(database_path('seeders/json/team.json')), true);
        foreach ($teams as $team) {
            Team::updateOrCreate(['id' => $team['id']], ['fungsi' => $team['fungsi']]);
        }

        // Seed Rooms
        $rooms = json_decode(file_get_contents(database_path('seeders/json/room.json')), true);
        foreach ($rooms as $room) {
            Room::updateOrCreate(['id' => $room['id']], ['ruang' => $room['ruang']]);
        }

        // 2. Seed Types
        $types = json_decode(file_get_contents(database_path('seeders/json/type.json')), true);
        foreach ($types as $type) {
            Type::updateOrCreate(['id' => $type['id']], ['jenis' => $type['jenis']]);
        }

        // 3. Seed Conditions
        $conditions = json_decode(file_get_contents(database_path('seeders/json/condition.json')), true);
        foreach ($conditions as $cond) {
            Condition::updateOrCreate(['id' => $cond['id']], ['kondisi' => $cond['kondisi']]);
        }

        // 4. Seed Sources
        $sources = json_decode(file_get_contents(database_path('seeders/json/source.json')), true);
        foreach ($sources as $source) {
            Source::updateOrCreate(['id' => $source['id']], ['asal' => $source['asal']]);
        }

        // 5. Seed Status BMN
        $statusBmns = json_decode(file_get_contents(database_path('seeders/json/status_bmn.json')), true);
        foreach ($statusBmns as $status) {
            StatusBmn::updateOrCreate(['id' => $status['id']], ['status' => $status['status']]);
        }

        // 6. Seed Vendor Services
        $vendors = json_decode(file_get_contents(database_path('seeders/json/vendor_service.json')), true);
        foreach ($vendors as $vendor) {
            VendorService::updateOrCreate(['id' => $vendor['id']], ['vendor_service' => $vendor['vendor_sevice']]);
        }

        // 7. Seed Users
        $users = json_decode(file_get_contents(database_path('seeders/json/user.json')), true);
        $userNips = [];
        foreach ($users as $u) {
            $nipLama = (int)$u['nip_lama'];
            $userNips[] = $nipLama;

            $isJarkom = isset($u['is_jarkom']) ? (int)$u['is_jarkom'] : 0;
            $idRuang = isset($u['id_ruang']) ? (int)$u['id_ruang'] : null;

            // Generate clean email based on name
            $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', strtolower(explode(',', $u['nama'])[0]));
            $email = $cleanName . '@example.com';

            User::updateOrCreate(
                ['nip_lama' => $nipLama],
                [
                    'name' => $u['nama'],
                    'email' => $email,
                    'nip_baru' => trim((string)$u['nip_baru']),
                    'fungsi' => $u['fungsi'],
                    'jabatan' => $u['jabatan'],
                    'password' => Hash::make('password'),
                    'is_jarkom' => $isJarkom,
                    'id_ruang' => $idRuang,
                ]
            );
        }

        // Ensure there is at least one jarkom user if none got set
        if (User::where('is_jarkom', 1)->count() == 0) {
            User::where('nip_lama', '>', 100)->first()?->update(['is_jarkom' => 1]);
        }

        // 8. Seed QrPusat
        $qrPusats = json_decode(file_get_contents(database_path('seeders/json/qr_pusat.json')), true);
        foreach ($qrPusats as $qr) {
            \App\Models\QrPusat::updateOrCreate(
                ['qr_pusat' => $qr['qr_pusat']],
                ['id_user' => (int)$qr['id_user']]
            );
        }

        // 7. Seed Devices
        $devices = json_decode(file_get_contents(database_path('seeders/json/device.json')), true);
        $roomIds = Room::pluck('id')->toArray();
        foreach ($devices as $d) {
            $idUser = $d['id_user'];
            // Check if user or room exists in the database
            if (!in_array($idUser, $userNips) && !in_array($idUser, $roomIds)) {
                $idUser = null; // Set to null if neither exists
            }

            // Fallback for foreign keys
            $idType = is_numeric($d['id_type']) ? $d['id_type'] : 99;
            $idSource = is_numeric($d['id_source']) ? $d['id_source'] : 99;
            $idStatusBmn = is_numeric($d['id_status_bmn']) ? $d['id_status_bmn'] : 99;
            $idCondition = is_numeric($d['id_condition']) ? $d['id_condition'] : 99;

            Device::updateOrCreate(
                ['id' => $d['id']],
                [
                    'id_type' => $idType,
                    'year' => (is_numeric($d['year']) && $d['year'] != '-') ? $d['year'] : null,
                    'id_source' => $idSource,
                    'brand' => $d['brand'],
                    'series' => $d['series'],
                    'serial_number' => $d['serial_number'] != '-' ? $d['serial_number'] : null,
                    'id_status_bmn' => $idStatusBmn,
                    'id_condition' => $idCondition,
                    'keterangan' => $d['keterangan'] != '-' ? $d['keterangan'] : null,
                    'id_user' => $idUser,
                ]
            );
        }
    }

    /**
     * JSON is generated data. Refresh it from the current Excel workbooks on
     * every seed run so additions and edits in Excel are always included.
     */
    private function refreshJsonFromExcel(): void
    {
        $converter = database_path('seeders/convert_excel_to_json.py');
        if (!is_file($converter)) {
            throw new \RuntimeException("Excel converter tidak ditemukan: {$converter}");
        }

        $commands = PHP_OS_FAMILY === 'Windows'
            ? [['py', '-3', $converter], ['python', $converter]]
            : [['python3', $converter], ['python', $converter]];

        $errors = [];
        foreach ($commands as $command) {
            $process = new \Symfony\Component\Process\Process($command, base_path());
            $process->setTimeout(120);
            $process->run();

            if ($process->isSuccessful()) {
                $this->command?->info('Data seed diperbarui dari file Excel.');
                return;
            }

            $errors[] = trim($process->getErrorOutput() ?: $process->getOutput());
        }

        throw new \RuntimeException(
            'Gagal memperbarui data seed dari Excel. Pastikan Python dan openpyxl tersedia. '
            . implode(' | ', array_filter($errors))
        );
    }
}
