<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Room;
use App\Models\Type;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch devices belonging to this user
        $devices = Device::where('id_user', $user->nip_lama)
            ->with(['type', 'condition'])
            ->get();

        // Fetch devices belonging to this user's room
        $roomDevices = collect();
        if ($user->id_ruang) {
            $roomDevices = Device::where('id_user', $user->id_ruang)
                ->with(['type', 'condition'])
                ->get();
        }

        // Fetch all devices for addition/swap (excluding the ones the current user already owns)
        $availableDevices = Device::where(function($q) use ($user) {
            $q->where('id_user', '!=', $user->nip_lama)
              ->orWhereNull('id_user');
        })->with(['type', 'condition', 'user', 'room'])->get();

        $types = Type::all();

        // Open Ticket includes devices owned directly by the room and by its users.
        $allRooms = Room::with([
            'devices' => fn ($query) => $query->with(['type', 'condition', 'user', 'room']),
            'users.devices' => fn ($query) => $query->with(['type', 'condition', 'user', 'room']),
        ])->get();

        $allRooms->each(function (Room $room): void {
            $room->setAttribute(
                'ticket_devices',
                $room->devices
                    ->merge($room->users->flatMap->devices)
                    ->unique('id')
                    ->values()
            );
        });

        return view('dashboard', compact('devices', 'roomDevices', 'availableDevices', 'types', 'allRooms'));
    }

    /**
     * Parse the BMN QR payload and return the report form URL for the matched device.
     */
    public function quickScan(Request $request)
    {
        $request->validate([
            'qr_string' => ['required', 'string'],
        ]);

        $parts = array_map('trim', explode('*', $request->input('qr_string')));
        if (count($parts) < 4 || $parts[2] === '' || $parts[3] === '') {
            return response()->json([
                'message' => 'Format QR perangkat tidak dikenali.',
            ], 422);
        }

        $deviceId = $parts[2] . '-' . $parts[3];
        $device = Device::find($deviceId);

        if (!$device) {
            return response()->json([
                'message' => "Perangkat dengan kode BMN {$deviceId} tidak ditemukan.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'device_id' => $device->id,
            'redirect' => route('report.create', $device->id),
        ]);
    }

    public function manage(Request $request)
    {
        $types = Type::all();
        
        $query = Device::with(['type', 'condition', 'user']);

        // Default type if none selected (e.g. PC = 1, Laptop = 2, UPS = 4, or first available)
        if ($request->filled('type_id')) {
            $query->where('id_type', $request->type_id);
        } else {
            // Show all or first type
            $query->where('id_type', 1); // Default to PC
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('series', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        $devices = $query->paginate(12)->withQueryString();

        return view('dashboard.manage', compact('devices', 'types'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
        ]);

        $device = Device::findOrFail($request->device_id);
        
        // Assign to logged-in user
        $device->update([
            'id_user' => Auth::user()->nip_lama
        ]);

        return back()->with('success', 'Perangkat berhasil ditambahkan ke daftar penguasaan Anda.');
    }

    /**
     * Assign a room device to the currently logged-in user (take personal ownership).
     */
    public function assignFromRoom(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
        ]);

        $device = Device::findOrFail($request->device_id);
        $user   = Auth::user();

        // Only allow if device belongs to the user's room
        if ($device->id_user != $user->id_ruang) {
            return back()->with('error', 'Perangkat ini bukan milik ruangan Anda.');
        }

        $device->update(['id_user' => $user->nip_lama]);

        return back()->with('success', 'Perangkat ruangan berhasil Anda kuasai secara pribadi.');
    }

    /** Assign an available device to the authenticated user's room. */
    public function assignToRoom(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
        ]);

        $user = Auth::user();
        if (!$user->id_ruang) {
            return back()->with('error', 'Akun Anda belum terhubung ke ruangan.');
        }

        $device = Device::findOrFail($request->device_id);
        if ((string) $device->id_user === (string) $user->id_ruang) {
            return back()->with('error', 'Perangkat tersebut sudah terdaftar di ruangan Anda.');
        }
        $device->update(['id_user' => $user->id_ruang]);

        return back()->with('success', 'Perangkat berhasil ditambahkan ke perangkat ruangan.');
    }

    public function unassign(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
        ]);

        $device = Device::findOrFail($request->device_id);

        // Ensure user owns it before releasing
        if ($device->id_user == Auth::user()->nip_lama) {
            $device->update([
                'id_user' => null
            ]);
            return back()->with('success', 'Perangkat berhasil dilepaskan dari daftar penguasaan Anda.');
        }

        return back()->with('error', 'Anda tidak memiliki hak untuk melepaskan perangkat ini.');
    }

    /**
     * Move a room device to Gudang (id_user = 15).
     * Used by the "delete" button on room device cards.
     */
    public function moveToGudang(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
        ]);

        $device = Device::findOrFail($request->device_id);

        // Only allow moving devices that belong to the user's room
        $user = Auth::user();
        if ($device->id_user != $user->id_ruang) {
            return back()->with('error', 'Anda tidak memiliki hak untuk memindahkan perangkat ini.');
        }

        $device->update(['id_user' => 15]); // 15 = Gudang

        return back()->with('success', 'Perangkat berhasil dipindahkan ke Gudang.');
    }

    public function swap(Request $request)
    {
        $request->validate([
            'old_device_id' => 'required|exists:devices,id',
            'new_device_id' => 'required|exists:devices,id',
        ]);

        $oldDevice = Device::findOrFail($request->old_device_id);
        $newDevice = Device::findOrFail($request->new_device_id);

        if ($oldDevice->id_user != Auth::user()->nip_lama) {
            return back()->with('error', 'Anda tidak memiliki hak untuk mengganti perangkat ini.');
        }

        \DB::transaction(function() use ($oldDevice, $newDevice) {
            // Set old device to Gudang (15)
            $oldDevice->update(['id_user' => 15]);
            // Assign new device to current user
            $newDevice->update(['id_user' => Auth::user()->nip_lama]);
        });

        return back()->with('success', 'Perangkat berhasil diganti.');
    }

    public function show($id)
    {
        $device = Device::with(['type', 'condition', 'source', 'statusBmn', 'user'])->findOrFail($id);
        
        // Retrieve repair history for this device
        $reports = Report::where('device_id', $id)
            ->with(['reporter', 'technician', 'vendor'])
            ->latest()
            ->get();

        return view('dashboard.show', compact('device', 'reports'));
    }
}
