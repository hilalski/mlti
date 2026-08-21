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
        <div class="card-header bg-dark">
          <h5 class="card-title my-0 text-white"><i class="bi bi-info-circle me-1"></i> Spesifikasi Perangkat</h5>
        </div>
        <div class="card-body pt-3">
          
          <div class="text-center py-3 border-bottom mb-3">
            <div class="p-3 bg-light rounded-circle text-primary fs-1 mx-auto d-flex align-items-center justify-content-center" style="width: 75px; height: 75px;">
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

          <table class="table table-striped table-bordered small mb-0">
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
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title my-0 text-white"><i class="bi bi-journal-text me-1"></i> Riwayat Kerusakan & Perbaikan</h5>
          <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body pt-3">
          
          @forelse($reports as $report)
            <div class="border-bottom pb-4 mb-4">
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
              <div class="bg-light p-3 rounded mb-3 border-start border-3 border-secondary small text-dark">
                <span class="fw-bold d-block mb-1">Kendala yang Dilaporkan:</span>
                <span style="white-space: pre-line;">{{ $report->description }}</span>
              </div>

              @if($report->status !== 'menunggu')
                <div class="row g-2 small">
                  <div class="col-sm-6">
                    <div class="p-2 border rounded bg-white">
                      <span class="text-muted d-block" style="font-size: 0.75rem;">Teknisi Penanggung Jawab</span>
                      <strong class="text-dark">{{ $report->technician->name ?? 'N/A' }}</strong>
                    </div>
                  </div>
                  @if($report->id_vendor)
                    <div class="col-sm-6">
                      <div class="p-2 border rounded bg-white">
                        <span class="text-muted d-block" style="font-size: 0.75rem;">Vendor Rujukan</span>
                        <strong class="text-dark">{{ $report->vendor->vendor_service ?? 'N/A' }}</strong>
                      </div>
                    </div>
                  @endif
                  <div class="col-12 mt-2">
                    <div class="p-2 border border-warning-subtle rounded bg-white" style="border-left: 3px solid var(--color-accent) !important;">
                      <span class="text-muted d-block" style="font-size: 0.75rem;">Tindakan / Solusi Teknisi</span>
                      <span class="text-dark fw-semibold" style="white-space: pre-line;">{{ $report->technician_notes ?: 'Belum ada catatan solusi.' }}</span>
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
