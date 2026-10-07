@extends('layouts.app')
@section('title', 'Laporan Zoom | MLTI')
@section('content')
    <div class="pagetitle">
        <h1>Kelola Pengajuan Zoom</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Laporan Zoom</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title my-0 text-white"><i class="bi bi-camera-video-fill me-1"></i> Pengajuan Zoom</h5>
            </div>
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID Pengajuan</th>
                                <th>Pemohon</th>
                                <th>Kegiatan</th>
                                <th>Jadwal</th>
                                <th>Partisipan</th>
                                <th>Ruang Zoom</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td><span class="text-primary fw-bold">{{ $booking->booking_code ?? '-' }}</span></td>
                                    <td><strong>{{ $booking->requester->name ?? '-' }}</strong><small
                                            class="d-block text-muted">{{ $booking->requester->jabatan ?? '-' }}</small>
                                    </td>
                                    <td>{{ $booking->title }}
                                    </td>
                                    <td>{{ $booking->starts_at->format('d M Y, H:i') }}<small
                                            class="d-block text-muted">s.d.
                                            {{ $booking->ends_at->format('d M Y, H:i') }}</small></td>
                                    <td>{{ $booking->participants }} orang</td>
                                    <td>{{ $booking->zoomRoom->zoom_room ?? '-' }}</td>
                                    <td><span
                                            class="badge bg-{{ $booking->status === 'disetujui' ? 'success' : ($booking->status === 'ditolak' ? 'danger' : 'warning text-dark') }}">{{ ucfirst($booking->status) }}</span>
                                    </td>
                                    <td class="text-center"><a href="{{ route('admin.zoom-reports.show', $booking) }}"
                                            class="btn btn-sm btn-primary"><i class="bi bi-pencil-square"></i> Tindak
                                            Lanjut</a></td>
                            </tr>@empty<tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Belum ada pengajuan Zoom.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $bookings->links() }}</div>
            </div>
        </div>
    </section>
@endsection
