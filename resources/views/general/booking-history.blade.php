@extends('layouts.app')
@section('title', 'Riwayat Pengajuan | MLTI')
@section('content')
    <div class="pagetitle">
        <h1>Riwayat Pengajuan {{ $type === 'zoom' ? 'Zoom' : 'Ruangan' }}</h1>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kegiatan</th>
                                <th>Jadwal</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->title }} @if ($booking->room)
                                            <small class="d-block text-muted">{{ $booking->room->ruang }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $booking->starts_at->format('d M Y H:i') }}</td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span></td>
                                    <td class="text-center">
                                        <form method="POST"
                                            action="{{ route('general.booking.destroy', [$type, $booking]) }}"
                                            class="d-inline" onsubmit="return confirm('Hapus pengajuan ini?')">@csrf
                                            @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i
                                                    class="bi bi-trash"></i> Hapus</button></form>
                                    </td>
                            </tr>@empty<tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>{{ $bookings->links() }}
            </div>
        </div>
    </section>
@endsection
