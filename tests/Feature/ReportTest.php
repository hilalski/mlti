<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Room;
use App\Models\Team;
use App\Models\Type;
use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $roomWithoutEmployee;
    protected $device;

    protected function setUp(): void
    {
        parent::setUp();

        Team::create(['id' => 1, 'fungsi' => 'Kepala']);
        $room1 = Room::create(['id' => 4, 'ruang' => 'Ruang Kepala']);
        $this->roomWithoutEmployee = Room::create(['id' => 1, 'ruang' => 'Ruang Aula']);
        $type = Type::create(['id' => 1, 'jenis' => 'PC']);
        \App\Models\Source::create(['id' => 1, 'asal' => 'Pusat']);
        \App\Models\StatusBmn::create(['id' => 1, 'status' => 'Aktif']);
        \App\Models\Condition::create(['id' => 1, 'kondisi' => 'Baik']);

        $this->user = User::create([
            'nip_lama' => 340011248,
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password'),
            'fungsi' => 1,
            'id_ruang' => 4,
        ]);

        // Device located in Ruang Aula (id_user = 1, room without employees)
        $this->device = Device::create([
            'id' => '3100102001-99',
            'id_type' => 1,
            'id_source' => 1,
            'id_status_bmn' => 1,
            'id_condition' => 1,
            'brand' => 'Dell',
            'series' => 'OptiPlex',
            'id_user' => 1, // Ruang Aula
        ]);
    }

    public function test_user_can_access_open_ticket_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('report.open-ticket'));

        $response->assertStatus(200);
        $response->assertSee('Open Ticket Pelaporan Kendala');
        $response->assertSee('Ruang Aula');
    }

    public function test_user_can_create_open_ticket_report_for_device_in_any_room(): void
    {
        $response = $this->actingAs($this->user)->post(route('report.store'), [
            'device_id' => $this->device->id,
            'issue_type' => 'hardware',
            'description' => 'Monitor tidak menyala di Ruang Aula.',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'device_id' => $this->device->id,
            'reported_by' => $this->user->nip_lama,
            'issue_type' => 'hardware',
            'description' => 'Monitor tidak menyala di Ruang Aula.',
            'status' => 'menunggu',
        ]);
    }

    public function test_user_can_create_network_report_without_device(): void
    {
        $response = $this->actingAs($this->user)->post(route('report.store'), [
            'room_id' => $this->roomWithoutEmployee->id,
            'issue_type' => 'jaringan',
            'description' => 'Koneksi WiFi di Ruang Aula terputus total sejak pagi.',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'device_id' => null,
            'id_ruang' => $this->roomWithoutEmployee->id,
            'reported_by' => $this->user->nip_lama,
            'issue_type' => 'jaringan',
            'description' => 'Koneksi WiFi di Ruang Aula terputus total sejak pagi.',
            'status' => 'menunggu',
        ]);
    }
}
