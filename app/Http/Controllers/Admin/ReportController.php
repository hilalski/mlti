<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\VendorService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['device.type', 'reporter']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('issue_type')) {
            $query->where('issue_type', $request->issue_type);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

    public function show($ticketId)
    {
        $report = Report::with(['device.type', 'device.condition', 'reporter', 'technician', 'vendor'])
            ->where(fn ($query) => $query->where('ticket_id', $ticketId)->orWhere('id', $ticketId))
            ->firstOrFail();
        $vendors = VendorService::all();
        $technicians = User::where('is_jarkom', 1)->get();

        return view('admin.reports.show', compact('report', 'vendors', 'technicians'));
    }

    public function update(Request $request, $ticketId)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,ditolak',
            'technician_notes' => 'nullable|string',
            'id_vendor' => 'nullable|exists:vendor_services,id',
            'handled_by' => 'nullable|exists:users,nip_lama',
        ]);

        $report = Report::where(fn ($query) => $query->where('ticket_id', $ticketId)->orWhere('id', $ticketId))->firstOrFail();

        $data = [
            'status' => $request->status,
            'technician_notes' => $request->technician_notes,
            'id_vendor' => $request->id_vendor ?: null,
            'handled_by' => $request->handled_by ?: ($report->handled_by ?: Auth::user()->nip_lama),
        ];

        if ($request->status === 'selesai') {
            $data['resolved_at'] = now();
        }

        $report->update($data);

        return redirect()->route('admin.reports.show', $report->ticket_id)->with('success', 'Laporan berhasil diperbarui.');
    }
}
