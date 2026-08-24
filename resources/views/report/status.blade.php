@extends('layouts.app')

@title('Status Perbaikan Perangkat | MLTI-Report')

@section('content')
<div class="pagetitle">
  <h1>Status Perbaikan Perangkat</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
      <li class="breadcrumb-item active">Status Perbaikan</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">

      <!-- Device summary card -->
      <div class="card mb-4 border-top border-5" style="border-top-color: var(--color-primary) !important;">
        <div class="card-body pt-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold text-dark mb-0">{{ $device->brand }} - {{ $device->series }}</h5>
            <span class="badge bg-light text-secondary border">{{ $device->type->jenis ?? 'Lainnya' }}</span>
          </div>
          <p class="text-muted mb-0 small">Kode BMN: <strong>{{ $device->id }}</strong> | S/N: {{ $device->serial_number ?: '-' }} | Tahun: {{ $device->year ?: '-' }}</p>
        </div>
      </div>

      <!-- Timeline Card -->
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 15px 20px !important; margin: -24px -24px 20px -24px !important; border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white"><i class="bi bi-clock-history me-1"></i> Riwayat Laporan & Perbaikan</h5>
          <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light" style="color: #FF84BA !important; border: none !important; font-weight: 600;"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        <div class="card-body pt-4">

          @forelse($reports as $report)
            <div class="pb-4 mb-4" style="border-bottom: 2px dashed #ffd4e5 !important;">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <span class="badge bg-{{ $report->status == 'menunggu' ? 'secondary' : ($report->status == 'diproses' ? 'primary' : ($report->status == 'selesai' ? 'success' : 'danger')) }} py-2 px-3 fw-bold fs-6">
                    @switch($report->status)
                      @case('menunggu')
                        <i class="bi bi-clock me-1"></i> Menunggu Konfirmasi
                        @break
                      @case('diproses')
                        <i class="bi bi-wrench-adjustable me-1"></i> Sedang Diproses
                        @break
                      @case('selesai')
                        <i class="bi bi-check-circle me-1"></i> Perbaikan Selesai
                        @break
                      @case('ditolak')
                        <i class="bi bi-x-circle me-1"></i> Laporan Ditolak
                        @break
                    @endswitch
                  </span>
                  <div class="mt-2 text-dark small">
                    Jenis Kendala: <strong class="badge badge-{{ $report->issue_type }}">{{ ucfirst($report->issue_type) }}</strong>
                  </div>
                </div>
                <span class="text-muted small fw-bold">{{ $report->created_at->format('d M Y, H:i') }}</span>
              </div>

              <!-- Report detail block -->
              <div class="p-3 rounded mb-3 border-start border-3 small text-dark" style="background: #fffdfd; border-color: #FF84BA !important; border-left-width: 4px !important; border-top: 1px solid #fdf5f9; border-right: 1px solid #fdf5f9; border-bottom: 1px solid #fdf5f9;">
                <strong class="small d-block mb-1" style="color: var(--text-primary);">Deskripsi Masalah dari Pegawai:</strong>
                <p class="mb-0 small text-dark" style="white-space: pre-line;">{{ $report->description }}</p>
              </div>

              <!-- Technician Action Detail -->
              @if($report->status !== 'menunggu')
                <div class="card bg-white border mb-0" style="padding: 20px !important; border-left: 4px solid #FF84BA !important; border-color: var(--border-color) !important;">
                  <div class="row g-3">
                    <div class="col-sm-6">
                      <strong class="text-secondary small mb-1 d-block"><i class="bi bi-person-workspace me-1" style="color: #FF84BA;"></i> Teknisi Penanggung Jawab:</strong>
                      <span class="text-dark small fw-bold" style="color: var(--text-primary) !important;">{{ $report->technician->name ?? 'N/A' }}</span>
                    </div>

                    @if($report->id_vendor)
                      <div class="col-sm-6">
                        <strong class="text-secondary small mb-1 d-block"><i class="bi bi-building me-1" style="color: #FF84BA;"></i> Dirujuk ke Vendor:</strong>
                        <span class="text-dark small fw-bold" style="color: var(--text-primary) !important;">{{ $report->vendor->vendor_service ?? 'N/A' }}</span>
                      </div>
                    @endif

                    <div class="col-12 mt-3 pt-3 border-top" style="border-top: 1px dashed var(--border-color) !important;">
                      <strong class="text-secondary small mb-1 d-block"><i class="bi bi-journal-text me-1" style="color: #FF84BA;"></i> Catatan Tindakan / Solusi Teknisi:</strong>
                      <p class="mb-0 small text-dark" style="white-space: pre-line;">{{ $report->technician_notes ?: 'Belum ada catatan dari tim teknis.' }}</p>
                    </div>
                  </div>
                </div>

                  @if($report->resolved_at)
                    <div class="col-12 mt-2 text-end text-muted small">
                      Diselesaikan pada: <strong>{{ $report->resolved_at->format('d M Y, H:i') }}</strong>
                    </div>
                  @endif
                </div>
              @else
                <div class="alert alert-info py-2 px-3 mb-0 small">
                  <i class="bi bi-info-circle me-1"></i> Laporan Anda telah diterima oleh sistem.
                </div>
              @endif
            </div>
          @empty
            <div class="text-center py-4">
              <i class="bi bi-clipboard-x fs-1 text-muted"></i>
              <p class="text-muted mt-2">Belum ada riwayat laporan kerusakan untuk perangkat ini.</p>
            </div>
          @endforelse

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
