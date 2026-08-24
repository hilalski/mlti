<?php

namespace App\Http\Controllers;

use App\Models\Device;
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

        // Fetch available devices (where id_user is Gudang (15), Unknown (99), or null)
        $availableDevices = Device::where(function($q) {
            $q->whereIn('id_user', [15, 99])
              ->orWhereNull('id_user');
        })->with(['type', 'condition'])->get();

        $types = Type::all();

        return view('dashboard', compact('devices', 'roomDevices', 'availableDevices', 'types'));
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
