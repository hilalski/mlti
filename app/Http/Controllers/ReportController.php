<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Report;
use App\Models\User;
use App\Notifications\NewDeviceReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ReportController extends Controller
{
    public function create($device_id)
    {
        $device = Device::where('id', $device_id)
            ->where(function($q) {
                $q->where('id_user', Auth::user()->nip_lama);
                if (Auth::user()->id_ruang) {
                    $q->orWhere('id_user', Auth::user()->id_ruang);
                }
            })
            ->firstOrFail();

        return view('report.create', compact('device'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
            'issue_type' => 'required|in:hardware,software,jaringan',
            'description' => 'required|string|min:5',
        ]);

        $device = Device::where('id', $request->device_id)
            ->where(function($q) {
                $q->where('id_user', Auth::user()->nip_lama);
                if (Auth::user()->id_ruang) {
                    $q->orWhere('id_user', Auth::user()->id_ruang);
                }
            })
            ->firstOrFail();

        // Check if there is already an active report for this device
        $activeReport = $device->activeReport();
        if ($activeReport) {
            return redirect()->route('dashboard')->with('error', 'Perangkat ini sedang dalam proses perbaikan.');
        }

        // Create Report
        $report = Report::create([
            'device_id' => $device->id,
            'reported_by' => Auth::user()->nip_lama,
            'issue_type' => $request->issue_type,
            'description' => $request->description,
            'status' => 'menunggu',
        ]);

        // Optional: Update device condition to 'Rusak Ringan' (2) or similar if needed,
        // but we can leave it as is or update it based on requirements.

        // Notify Jarkom users
        $jarkomUsers = User::where('is_jarkom', 1)->get();
        if ($jarkomUsers->count() > 0) {
            Notification::send($jarkomUsers, new NewDeviceReport($report));
        }

        return redirect()->route('dashboard')->with('success', 'Laporan kerusakan berhasil dikirim!');
    }

    public function status($device_id)
    {
        $device = Device::where('id', $device_id)
            ->where(function($q) {
                $q->where('id_user', Auth::user()->nip_lama);
                if (Auth::user()->id_ruang) {
                    $q->orWhere('id_user', Auth::user()->id_ruang);
                }
            })
            ->firstOrFail();

        // Get all reports for this device, latest first
        $reports = Report::where('device_id', $device_id)
            ->with(['technician', 'vendor'])
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

    public function showReport($id)
    {
        $user = Auth::user();
        
        $report = Report::where('id', $id)
            ->where('reported_by', $user->nip_lama)
            ->with(['device.type', 'device.condition', 'reporter', 'technician', 'vendor'])
            ->firstOrFail();
            
        return view('reports.show', compact('report'));
    }
}
