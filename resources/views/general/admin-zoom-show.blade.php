@extends('layouts.app')
@section('title', 'Tindak Lanjut Zoom | MLTI')
@section('content')
    <div class="pagetitle">
        <h1>Tindak Lanjut Pengajuan Zoom</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.zoom-reports') }}">Laporan Zoom</a></li>
                <li class="breadcrumb-item active">Detail Pengajuan</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title my-0"><i class="bi bi-info-circle me-1"></i>Detail Pengajuan</h5>
                    </div>
                    <div class="card-body pt-3">
                        <table class="table table-hover">
                            <tr>
                                <th style="width:38%">ID Pengajuan</th>
                                <td class="text-primary fw-bold">{{ $booking->booking_code ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Pemohon</th>
                                <td>{{ $booking->requester->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>{{ $booking->requester->jabatan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Judul kegiatan</th>
                                <td>{{ $booking->title }}</td>
                            </tr>
                            <tr>
                                <th>Jadwal</th>
                                <td>{{ $booking->starts_at->format('d M Y, H:i') }}<br>s.d.
                                    {{ $booking->ends_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Partisipan</th>
                                <td>{{ $booking->participants }} orang</td>
                            </tr>
                            <tr>
                                <th>Live streaming</th>
                                <td>{{ $booking->live_streaming ? 'Ya' : 'Tidak' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><span
                                        class="badge bg-{{ $booking->status === 'disetujui' ? 'success' : ($booking->status === 'ditolak' ? 'danger' : 'warning text-dark') }}">{{ ucfirst($booking->status) }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title my-0"><i class="bi bi-wrench-adjustable me-1"></i>Form Tindak Lanjut</h5><a
                            href="{{ route('admin.zoom-reports') }}" class="btn btn-sm btn-secondary">Kembali</a>
                    </div>
                    <div class="card-body pt-3">
                        <form method="POST" action="{{ route('admin.zoom-reports.update', $booking) }}">@csrf
                            @method('PATCH')<div class="mb-3"><label class="form-label fw-bold">Status
                                    Pengajuan</label><select name="status" class="form-select" required>
                                    <option value="menunggu"
                                        {{ old('status', $booking->status) === 'menunggu' ? 'selected' : '' }}>Menunggu
                                    </option>
                                    <option value="disetujui"
                                        {{ old('status', $booking->status) === 'disetujui' ? 'selected' : '' }}>Disetujui
                                    </option>
                                    <option value="ditolak"
                                        {{ old('status', $booking->status) === 'ditolak' ? 'selected' : '' }}>
                                        Ditolak</option>
                                </select></div>
                            <div class="mb-3"><label class="form-label fw-bold">Alokasi Ruang Zoom</label><select
                                    name="id_zoom_room" class="form-select @error('id_zoom_room') is-invalid @enderror">
                                    <option value="">-- Pilih ruang Zoom --</option>
                                    @foreach($zoomRooms as $zoomRoom)
                                        <option value="{{ $zoomRoom->id }}" {{ old('id_zoom_room', $booking->id_zoom_room) == $zoomRoom->id ? 'selected' : '' }}>
                                            {{ $zoomRoom->zoom_room }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_zoom_room')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4"><label class="form-label fw-bold">Catatan Tindak Lanjut</label>
                                <textarea name="admin_notes" rows="6" class="form-control"
                                    placeholder="Contoh: Ruang Zoom 1 dialokasikan dan tautan akan dikirimkan kepada pemohon.">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                            </div><button class="btn btn-primary w-100 py-2">Simpan &amp; Perbarui Status</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
