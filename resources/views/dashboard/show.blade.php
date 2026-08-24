@extends('layouts.app')

@section('title', 'Detail Perangkat & Riwayat | MLTI-Report')

@section('content')
<div class="pagetitle">
  <h1 class="fw-bold" style="color: var(--color-primary);">Detail Perangkat TI</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Perangkat Saya</a></li>
      <li class="breadcrumb-item active">Detail</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    
    <!-- Specs Column -->
    <div class="col-lg-5 col-md-12">
      <div class="card shadow-sm border-0 mb-4 h-100">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 15px 20px !important; margin: -24px -24px 20px -24px !important; border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white"><i class="bi bi-info-circle me-1"></i> Spesifikasi Perangkat</h5>
        </div>
        <div class="card-body pt-3">
          
          <div class="text-center py-3 border-bottom mb-3">
            <div class="p-3 rounded-circle fs-1 mx-auto d-flex align-items-center justify-content-center" style="width: 75px; height: 75px; background: #fff1f7; color: #FF84BA; border: 1px solid #ffd4e5;">
              @switch(strtolower($device->type->jenis ?? ''))
                @case('pc') <i class="bi bi-pc-display fs-2"></i> @break
                @case('laptop') <i class="bi bi-laptop fs-2"></i> @break
                @case('printer') <i class="bi bi-printer fs-2"></i> @break
                @case('ups') <i class="bi bi-lightning-charge fs-2"></i> @break
                @default <i class="bi bi-cpu fs-2"></i>
              @endswitch
            </div>
            <h5 class="fw-bold text-dark mt-2 mb-1">{{ $device->brand }}</h5>
            <span class="text-muted small">{{ $device->series }}</span>
          </div>

          <table class="table small mb-0">
            <tbody>
              <tr>
                <th style="width: 130px;">Kode BMN</th>
                <td class="fw-bold text-dark">{{ $device->id }}</td>
              </tr>
              <tr>
                <th>Kategori</th>
                <td>{{ $device->type->jenis ?? 'Lainnya' }}</td>
              </tr>
              <tr>
                <th>Nomor Seri</th>
                <td>{{ $device->serial_number ?: '-' }}</td>
              </tr>
              <tr>
                <th>Tahun</th>
                <td>{{ $device->year ?: '-' }}</td>
              </tr>
              <tr>
                <th>Kondisi</th>
                <td>
                  <span class="badge bg-{{ $device->id_condition == 1 ? 'success' : ($device->id_condition == 2 ? 'warning text-dark' : 'danger') }} px-2 py-1">
                    {{ $device->condition->kondisi ?? 'Tidak Diketahui' }}
                  </span>
                </td>
              </tr>
              <tr>
                <th>Asal Pengadaan</th>
                <td>{{ $device->source->asal ?? 'Tidak Diketahui' }}</td>
              </tr>
              <tr>
                <th>Status BMN</th>
                <td>{{ $device->statusBmn->status ?? 'Tidak Diketahui' }}</td>
              </tr>
              <tr>
                <th>Keterangan</th>
                <td>{{ $device->keterangan ?: '-' }}</td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>

    <!-- History Column -->
    <div class="col-lg-7 col-md-12">
      <div class="card shadow-sm border-0 mb-4 h-100">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 15px 20px !important; margin: -24px -24px 20px -24px !important; border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white"><i class="bi bi-journal-text me-1"></i> Riwayat Kerusakan & Perbaikan</h5>
          <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light" style="color: #FF84BA !important; border: none !important; font-weight: 600;"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body pt-3">
          
          @forelse($reports as $report)
            <div class="pb-4 mb-4" style="border-bottom: 2px dashed #ffd4e5 !important;">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <span class="badge bg-{{ $report->status == 'menunggu' ? 'secondary' : ($report->status == 'diproses' ? 'primary' : ($report->status == 'selesai' ? 'success' : 'danger')) }} py-1 px-2 small">
                    {{ ucfirst($report->status) }}
                  </span>
                  <span class="badge badge-{{ $report->issue_type }} ms-1">{{ ucfirst($report->issue_type) }}</span>
                </div>
                <span class="text-muted small">{{ $report->created_at->format('d M Y, H:i') }}</span>
              </div>

              <!-- Issue desc -->
              <div class="p-3 rounded mb-3 border-start border-3 small text-dark" style="background: #fffdfd; border-color: #FF84BA !important; border-left-width: 4px !important; border-top: 1px solid #fdf5f9; border-right: 1px solid #fdf5f9; border-bottom: 1px solid #fdf5f9;">
                <span class="fw-bold d-block mb-1" style="color: var(--text-primary);">Kendala yang Dilaporkan:</span>
                <span style="white-space: pre-line;">{{ $report->description }}</span>
              </div>

              @if($report->status !== 'menunggu')
                <div class="p-3 border rounded bg-white small" style="border-left: 4px solid #FF84BA !important; border-color: var(--border-color) !important;">
                  <div class="row g-2">
                    <div class="col-sm-6">
                      <span class="text-muted d-block" style="font-size: 0.75rem;">Teknisi Penanggung Jawab</span>
                      <strong class="text-dark" style="color: var(--text-primary) !important;">{{ $report->technician->name ?? 'N/A' }}</strong>
                    </div>
                    @if($report->id_vendor)
                      <div class="col-sm-6">
                        <span class="text-muted d-block" style="font-size: 0.75rem;">Vendor Rujukan</span>
                        <strong class="text-dark" style="color: var(--text-primary) !important;">{{ $report->vendor->vendor_service ?? 'N/A' }}</strong>
                      </div>
                    @endif
                    <div class="col-12 mt-2 pt-2 border-top" style="border-top: 1px dashed var(--border-color) !important;">
                      <span class="text-muted d-block" style="font-size: 0.75rem;">Tindakan / Solusi Teknisi</span>
                      <span class="fw-semibold" style="white-space: pre-line; color: var(--text-primary) !important;">{{ $report->technician_notes ?: 'Belum ada catatan solusi.' }}</span>
                    </div>
                  </div>
                </div>
              @else
                <div class="alert alert-light py-2 px-3 border mb-0 small text-muted">
                  <i class="bi bi-clock me-1"></i> Laporan ini sedang dalam antrian penugasan teknisi.
                </div>
              @endif

            </div>
          @empty
            <div class="text-center py-5 text-muted">
              <i class="bi bi-clipboard-x fs-1 d-block mb-2"></i>
              <span>Belum ada riwayat pelaporan kerusakan pada perangkat ini.</span>
            </div>
          @endforelse

        </div>
      </div>
    </div>

  </div>
</section>
@endsection
