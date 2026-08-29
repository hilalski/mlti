<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Report;
use App\Models\Room;
use App\Models\User;
use App\Notifications\NewDeviceReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ReportController extends Controller
{
    public function openTicket(Request $request)
    {
        $rooms = Room::with([
            'devices' => fn ($query) => $query->with(['type', 'condition', 'user', 'room']),
            'users.devices' => fn ($query) => $query->with(['type', 'condition', 'user', 'room']),
        ])->get();

        $rooms->each(function (Room $room): void {
            $room->setAttribute(
                'ticket_devices',
                $room->devices
                    ->merge($room->users->flatMap->devices)
                    ->unique('id')
                    ->values()
            );
        });

        $selectedRoomId = $request->query('room_id');
        $selectedDeviceId = $request->query('device_id');

        return view('report.open-ticket', compact('rooms', 'selectedRoomId', 'selectedDeviceId'));
    }

    public function create($device_id)
    {
        $device = Device::with(['type', 'condition', 'room', 'user'])->findOrFail($device_id);

        return view('report.create', compact('device'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'issue_type' => 'required|in:hardware,software,jaringan',
            'room_id' => 'nullable|exists:rooms,id',
            'device_id' => 'nullable|exists:devices,id',
            'description' => 'required|string|min:5',
        ]);

        // If issue_type is not 'jaringan', device_id is strictly required
        if ($request->issue_type !== 'jaringan' && empty($request->device_id)) {
            return back()->withErrors(['device_id' => 'Perangkat wajib dipilih untuk kendala hardware dan software.'])->withInput();
        }

        $deviceId = $request->device_id ?: null;
        $roomId = $request->room_id ?: null;

        if ($deviceId) {
            $device = Device::findOrFail($deviceId);
            // Check if there is already an active report for this device
            $activeReport = $device->activeReport();
            if ($activeReport) {
                return redirect()->route('dashboard')->with('error', 'Perangkat ini sedang dalam proses perbaikan (Tiket aktif sudah ada).');
            }
            // If room_id was not explicitly passed, infer from device's room if available
            if (!$roomId && $device->id_user && is_numeric($device->id_user) && $device->id_user < 100) {
                $roomId = $device->id_user;
            }
        }

        // Create Report
        $report = Report::create([
            'device_id' => $deviceId,
            'id_ruang' => $roomId,
            'reported_by' => Auth::user()->nip_lama,
            'issue_type' => $request->issue_type,
            'description' => $request->description,
            'status' => 'menunggu',
        ]);

        // Notify Jarkom users
        $jarkomUsers = User::where('is_jarkom', 1)->get();
        if ($jarkomUsers->count() > 0) {
            Notification::send($jarkomUsers, new NewDeviceReport($report));
        }

        return redirect()->route('dashboard')->with('success', 'Laporan tiket kendala berhasil dibuat!');
    }

    public function status($device_id)
    {
        $device = Device::with(['type', 'condition', 'room', 'user'])->findOrFail($device_id);

        // Get all reports for this device, latest first
        $reports = Report::where('device_id', $device_id)
            ->with(['technician', 'vendor', 'reporter'])
            ->latest()
            ->get();

        return view('report.status', compact('device', 'reports'));
    }

    public function history()
    {
        $user = Auth::user();
        
        $reports = Report::where('reported_by', $user->nip_lama)
            ->with(['device.type'])
            ->latest()
            ->paginate(10);
            
        return view('reports.history', compact('reports'));
    }

    public function showReport($ticketId)
    {
        $user = Auth::user();
        
        $report = Report::where(function ($query) use ($ticketId) {
                $query->where('ticket_id', $ticketId)->orWhere('id', $ticketId);
            })
            ->where('reported_by', $user->nip_lama)
            ->with(['device.type', 'device.condition', 'reporter', 'technician', 'vendor'])
            ->firstOrFail();
            
        return view('reports.show', compact('report'));
    }
}
