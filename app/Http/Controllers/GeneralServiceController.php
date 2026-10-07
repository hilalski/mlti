<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\GeneralComplaint;
use App\Models\Room;
use App\Models\ZoomRoom;
use Illuminate\Http\Request;

class GeneralServiceController extends Controller
{
    public function sigap()
    {
        return view('general.sigap');
    }

    public function storeSigap(Request $request)
    {
        $data = $request->validate(['location' => 'required|string|max:255', 'category' => 'required|string|max:100', 'description' => 'required|string|min:5']);
        GeneralComplaint::create($data + ['reported_by' => $request->user()->nip_lama]);
        return redirect()->route('general.sigap')->with('success', 'Pengaduan SIGAP berhasil dikirim.');
    }

    public function bookingForm(string $type)
    {
        abort_unless(in_array($type, ['zoom', 'room']), 404);
        $bookings = Booking::with(['room', 'zoomRoom'])->where('type', $type)->where('ends_at', '>=', now()->startOfWeek())
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->orderBy('starts_at')->get();
        $rooms = $type === 'room' ? Room::orderBy('ruang')->get() : collect();
        $calendarBookings = $bookings->map(function (Booking $booking): array {
            return [
                'title' => $booking->type === 'zoom' && $booking->zoomRoom ? '[' . $booking->zoomRoom->zoom_room . '] ' . $booking->title : $booking->title,
                'status' => $booking->status,
                'start' => $booking->starts_at->toIso8601String(),
                'end' => $booking->ends_at->toIso8601String(),
                'zoom_room_id' => $booking->id_zoom_room,
            ];
        })->values();

        return view('general.booking-form', compact('type', 'bookings', 'calendarBookings', 'rooms'));
    }

    public function storeBooking(Request $request, string $type)
    {
        abort_unless(in_array($type, ['zoom', 'room']), 404);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'purpose' => $type === 'zoom' ? 'nullable|string' : 'required|string|min:5',
            'starts_at' => 'required|date|after:now', 'ends_at' => 'required|date|after:starts_at',
            'room_id' => $type === 'room' ? 'required|exists:rooms,id' : 'nullable',
            'participants' => $type === 'zoom' ? 'required|integer|min:1|max:10000' : 'nullable',
            'live_streaming' => $type === 'zoom' ? 'nullable|boolean' : 'nullable',
        ]);

        if ($type === 'zoom') {
            $data['live_streaming'] = $request->boolean('live_streaming');
            $data['purpose'] = $data['live_streaming'] ? 'Memerlukan akses live streaming.' : 'Tidak memerlukan akses live streaming.';
        }

        $conflict = Booking::where('type', $type)->where('status', 'disetujui')->where('starts_at', '<', $data['ends_at'])->where('ends_at', '>', $data['starts_at']);
        if ($type === 'room') {
            $conflict->where('room_id', $data['room_id']);
        } elseif ($type === 'zoom') {
            $conflict = null;
        }
        if ($conflict && $conflict->exists()) {
            return back()->withErrors(['starts_at' => 'Jadwal yang dipilih bertabrakan dengan pengajuan yang sudah ada.'])->withInput();
        }
        Booking::create($data + ['requested_by' => $request->user()->nip_lama, 'type' => $type]);
        return redirect()->route('general.booking.form', $type)->with('success', 'Pengajuan berhasil dikirim.');
    }

    public function bookingHistory(string $type)
    {
        abort_unless(in_array($type, ['zoom', 'room']), 404);
        $bookings = Booking::with('room')->where('type', $type)->where('requested_by', request()->user()->nip_lama)->latest()->paginate(10);
        return view('general.booking-history', compact('type', 'bookings'));
    }

    public function destroyBooking(string $type, Booking $booking)
    {
        abort_unless(in_array($type, ['zoom', 'room']) && $booking->type === $type, 404);
        abort_unless($booking->requested_by === request()->user()->nip_lama, 403);
        $booking->delete();

        return back()->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function adminComplaints()
    {
        $complaints = GeneralComplaint::with('reporter')->latest()->paginate(15);
        return view('general.admin-complaints', compact('complaints'));
    }

    public function adminBookings()
    {
        return $this->bookingReport('room', 'Laporan Ruangan');
    }

    public function adminZoomBookings()
    {
        $bookings = Booking::with(['requester', 'zoomRoom'])->where('type', 'zoom')->latest()->paginate(15);
        return view('general.admin-zoom-bookings', compact('bookings'));
    }

    public function showZoomBooking(Booking $booking)
    {
        abort_unless($booking->type === 'zoom', 404);
        $booking->load(['requester', 'zoomRoom']);
        $zoomRooms = ZoomRoom::orderBy('zoom_room')->get();
        return view('general.admin-zoom-show', compact('booking', 'zoomRooms'));
    }

    public function updateZoomBooking(Request $request, Booking $booking)
    {
        abort_unless($booking->type === 'zoom', 404);
        $data = $request->validate([
            'status' => 'required|in:menunggu,disetujui,ditolak',
            'id_zoom_room' => 'nullable|exists:zoom_rooms,id',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        if ($data['status'] === 'disetujui' && empty($data['id_zoom_room'])) {
            return back()->withErrors(['id_zoom_room' => 'Pilih Ruang Zoom sebelum menyetujui pengajuan.'])->withInput();
        }

        if ($data['status'] === 'disetujui') {
            $conflict = Booking::where('type', 'zoom')->where('status', 'disetujui')->where('id_zoom_room', $data['id_zoom_room'])
                ->where('id', '!=', $booking->id)->where('starts_at', '<', $booking->ends_at)->where('ends_at', '>', $booking->starts_at)->exists();
            if ($conflict) {
                return back()->withErrors(['id_zoom_room' => 'Ruang Zoom yang dipilih sudah terpakai pada jadwal tersebut.'])->withInput();
            }
        }

        $booking->update($data);

        if ($data['status'] === 'disetujui') {
            Booking::where('type', 'zoom')
                ->where('status', 'menunggu')
                ->where('id', '!=', $booking->id)
                ->where('starts_at', '<', $booking->ends_at)
                ->where('ends_at', '>', $booking->starts_at)
                ->update([
                    'status' => 'ditolak',
                    'admin_notes' => 'Ditolak otomatis karena jadwal beririsan dengan pengajuan Zoom yang telah disetujui.',
                ]);
        }

        return redirect()->route('admin.zoom-reports.show', $booking)->with('success', 'Tindak lanjut pengajuan Zoom berhasil disimpan.');
    }

    private function bookingReport(string $type, string $title)
    {
        $bookings = Booking::with(['requester', 'room'])->where('type', $type)->latest()->paginate(15);
        return view('general.admin-bookings', compact('bookings', 'title'));
    }
}
