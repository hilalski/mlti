<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Type;
use App\Models\Source;
use App\Models\StatusBmn;
use App\Models\Condition;
use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $query = Device::with(['type', 'condition', 'user', 'room']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('series', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type_id')) {
            $query->where('id_type', $request->type_id);
        }

        if ($request->filled('condition_id')) {
            $query->where('id_condition', $request->condition_id);
        }

        if ($request->filled('room_id')) {
            $roomId = $request->room_id;
            $userIdsInRoom = User::where('id_ruang', $roomId)->pluck('nip_lama')->toArray();

            $query->where(function($q) use ($roomId, $userIdsInRoom) {
                $q->where('id_user', $roomId)
                  ->orWhereIn('id_user', $userIdsInRoom);
            });
        }

        $devices = $query->paginate(15)->withQueryString();
        $types = Type::all();
        $conditions = Condition::all();
        $rooms = Room::orderBy('ruang')->get();

        return view('admin.devices.index', compact('devices', 'types', 'conditions', 'rooms'));
    }

    public function create()
    {
        $types = Type::all();
        $sources = Source::all();
        $statusBmns = StatusBmn::all();
        $conditions = Condition::all();
        $users = User::orderBy('name')->get();
        $rooms = Room::orderBy('ruang')->get();

        return view('admin.devices.create', compact('types', 'sources', 'statusBmns', 'conditions', 'users', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|string|unique:devices,id',
            'id_type' => 'required|exists:types,id',
            'year' => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'id_source' => 'required|exists:sources,id',
            'brand' => 'required|string|max:255',
            'series' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'id_status_bmn' => 'required|exists:status_bmn,id',
            'id_condition' => 'required|exists:conditions,id',
            'keterangan' => 'nullable|string',
            'id_user' => 'nullable|integer',
        ]);

        // Validate that id_user exists in either users or rooms
        if ($request->filled('id_user')) {
            $idUser = $request->id_user;
            $userExists = User::where('nip_lama', $idUser)->exists();
            $roomExists = Room::where('id', $idUser)->exists();

            if (!$userExists && !$roomExists) {
                return back()->withErrors(['id_user' => 'Pemilik (User atau Ruangan) yang dipilih tidak terdaftar di sistem.'])->withInput();
            }
        }

        Device::create([
            'id' => $request->id,
            'id_type' => $request->id_type,
            'year' => $request->year,
            'id_source' => $request->id_source,
            'brand' => $request->brand,
            'series' => $request->series,
            'serial_number' => $request->serial_number,
            'id_status_bmn' => $request->id_status_bmn,
            'id_condition' => $request->id_condition,
            'keterangan' => $request->keterangan,
            'id_user' => $request->id_user ?: null,
        ]);

        return redirect()->route('admin.devices.index')->with('success', 'Perangkat TI berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $device = Device::findOrFail($id);
        $types = Type::all();
        $sources = Source::all();
        $statusBmns = StatusBmn::all();
        $conditions = Condition::all();
        $users = User::orderBy('name')->get();
        $rooms = Room::orderBy('ruang')->get();

        return view('admin.devices.edit', compact('device', 'types', 'sources', 'statusBmns', 'conditions', 'users', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);

        $request->validate([
            'id' => [
                'required',
                'string',
                Rule::unique('devices', 'id')->ignore($device->id, 'id')
            ],
            'id_type' => 'required|exists:types,id',
            'year' => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'id_source' => 'required|exists:sources,id',
            'brand' => 'required|string|max:255',
            'series' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'id_status_bmn' => 'required|exists:status_bmn,id',
            'id_condition' => 'required|exists:conditions,id',
            'keterangan' => 'nullable|string',
            'id_user' => 'nullable|integer',
        ]);

        // Validate that id_user exists in either users or rooms
        if ($request->filled('id_user')) {
            $idUser = $request->id_user;
            $userExists = User::where('nip_lama', $idUser)->exists();
            $roomExists = Room::where('id', $idUser)->exists();

            if (!$userExists && !$roomExists) {
                return back()->withErrors(['id_user' => 'Pemilik (User atau Ruangan) yang dipilih tidak terdaftar di sistem.'])->withInput();
            }
        }

        $device->update([
            'id' => $request->id,
            'id_type' => $request->id_type,
            'year' => $request->year,
            'id_source' => $request->id_source,
            'brand' => $request->brand,
            'series' => $request->series,
            'serial_number' => $request->serial_number,
            'id_status_bmn' => $request->id_status_bmn,
            'id_condition' => $request->id_condition,
            'keterangan' => $request->keterangan,
            'id_user' => $request->id_user ?: null,
        ]);

        return redirect()->route('admin.devices.index')->with('success', 'Perangkat TI berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();

        return redirect()->route('admin.devices.index')->with('success', 'Perangkat TI berhasil dihapus.');
    }
}
