<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\QrPusat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_using_scanned_qr_code_url(): void
    {
        // 1. Seed dependencies required by users table (teams and rooms)
        \App\Models\Team::create(['id' => 1, 'fungsi' => 'Kepala']);
        \App\Models\Room::create(['id' => 4, 'ruang' => 'Kepala']);

        // 2. Create a user
        $user = User::create([
            'nip_lama' => 340011248,
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
            'fungsi' => 1,
            'id_ruang' => 4,
        ]);

        // 3. Create the QR Pusat entry linking to the user
        QrPusat::create([
            'qr_pusat' => 'https://badgebps.web.bps.go.id/card/id/S1FFKzdhNllibXN1V2JXdm43VHE1dz09',
            'id_user' => 340011248,
        ]);

        // 4. Send verify request
        $response = $this->postJson(route('login.verify'), [
            'qr_string' => 'https://badgebps.web.bps.go.id/card/id/S1FFKzdhNllibXN1V2JXdm43VHE1dz09',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => route('dashboard'),
            ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_login_using_direct_nip_lama(): void
    {
        // 1. Seed dependencies required by users table
        \App\Models\Team::create(['id' => 1, 'fungsi' => 'Kepala']);
        \App\Models\Room::create(['id' => 4, 'ruang' => 'Kepala']);

        // 2. Create a user
        $user = User::create([
            'nip_lama' => 340011248,
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
            'fungsi' => 1,
            'id_ruang' => 4,
        ]);

        // 3. Send verify request with NIP directly (manual login fallback)
        $response = $this->postJson(route('login.verify'), [
            'qr_string' => '340011248',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => route('dashboard'),
            ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_qr_code(): void
    {
        // Send request with non-existent QR
        $response = $this->postJson(route('login.verify'), [
            'qr_string' => 'https://non-existent-qr-code',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);

        $this->assertGuest();
    }
}
