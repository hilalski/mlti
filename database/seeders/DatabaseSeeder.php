<?php

namespace Database\Seeders;

use App\Models\User;
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
        // 1. Seed Teams
        $teams = json_decode(file_get_contents(database_path('seeders/json/team.json')), true);
        foreach ($teams as $team) {
            Team::updateOrCreate(['id' => $team['id']], ['fungsi' => $team['fungsi']]);
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

            // Determine if Jarkom (is_jarkom = 1)
            // Let's set is_jarkom = 1 for anyone in fungsi = 9 (IPDS/IT/Jarkom team)
            $isJarkom = ($u['fungsi'] == 9) ? 1 : 0;

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
                ]
            );
        }

        // Ensure there is at least one jarkom user if none got set
        if (User::where('is_jarkom', 1)->count() == 0) {
            User::where('nip_lama', '>', 100)->first()?->update(['is_jarkom' => 1]);
        }

        // 7. Seed Devices
        $devices = json_decode(file_get_contents(database_path('seeders/json/device.json')), true);
        foreach ($devices as $d) {
            $idUser = $d['id_user'];
            // Check if user exists in the database
            if (!in_array($idUser, $userNips)) {
                $idUser = null; // Set to null if user doesn't exist
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
}

