@extends('layouts.app')

@section('title', 'Detail Laporan Kerusakan | MLTI-Report')

@section('content')
<div class="pagetitle">
  <h1 class="fw-bold" style="color: var(--color-primary);">Detail Laporan Kerusakan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
      <li class="breadcrumb-item"><a href="{{ route('reports.history') }}">Riwayat Laporan</a></li>
      <li class="breadcrumb-item active">Detail Laporan</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    
    <!-- Report Details (Left Column) -->
    <div class="col-lg-6 col-md-12 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 15px 20px !important; margin: -24px -24px 20px -24px !important; border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white"><i class="bi bi-file-earmark-text-fill me-1"></i> Detail Laporan</h5>
        </div>
        <div class="card-body pt-3">
          
          <table class="table align-middle mb-0">
            <tbody>
              <tr>
                <td class="fw-bold text-dark" style="width: 160px; border-bottom: 1px solid #fdf5f9;">Pelapor</td>
                <td style="border-bottom: 1px solid #fdf5f9;">{{ $report->reporter->name ?? 'N/A' }}</td>
              </tr>
              <tr>
                <td class="fw-bold text-dark" style="border-bottom: 1px solid #fdf5f9;">Perangkat</td>
                <td style="border-bottom: 1px solid #fdf5f9;">
                  <span class="fw-bold">{{ $report->device->brand ?? 'N/A' }} - {{ $report->device->series ?? 'N/A' }}</span>
                  <span class="text-muted ms-2" style="font-size: 0.8rem;">(BMN: {{ $report->device_id }})</span>
                </td>
              </tr>
              <tr>
                <td class="fw-bold text-dark" style="border-bottom: 1px solid #fdf5f9;">Jenis Kendala</td>
                <td style="border-bottom: 1px solid #fdf5f9;">
                  <span class="badge badge-{{ $report->issue_type }}">{{ ucfirst($report->issue_type) }}</span>
                </td>
              </tr>
              <tr>
                <td class="fw-bold text-dark" style="border-bottom: 1px solid #fdf5f9;">Tanggal Lapor</td>
                <td style="border-bottom: 1px solid #fdf5f9;">{{ $report->created_at->format('d M Y, H:i') }}</td>
              </tr>
              <tr>
                <td class="fw-bold text-dark" style="border-bottom: 1px solid #fdf5f9;">Deskripsi Kendala</td>
                <td style="border-bottom: 1px solid #fdf5f9; white-space: pre-line;">{{ $report->description }}</td>
              </tr>
              <tr>
                <td class="fw-bold text-dark" style="border-bottom: none;">Status Laporan</td>
                <td style="border-bottom: none;">
                  <span class="badge bg-{{ $report->status == 'menunggu' ? 'secondary' : ($report->status == 'diproses' ? 'primary' : ($report->status == 'selesai' ? 'success' : 'danger')) }} py-1 px-2 text-white">
                    {{ ucfirst($report->status) }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>

    <!-- Technical Action Detail (Right Column) -->
    <div class="col-lg-6 col-md-12 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 15px 20px !important; margin: -24px -24px 20px -24px !important; border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white"><i class="bi bi-wrench-adjustable me-1"></i> Penanganan Teknis</h5>
          <a href="{{ route('reports.history') }}" class="btn btn-sm btn-light" style="color: #FF84BA !important; border: none !important; font-weight: 600;"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
        
        <div class="card-body pt-3">
          
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
                  <strong class="text-secondary small mb-1 d-block"><i class="bi bi-journal-text me-1" style="color: #FF84BA;"></i> Tindakan / Solusi Teknisi:</strong>
                  <p class="mb-0 small text-dark" style="white-space: pre-line;">{{ $report->technician_notes ?: 'Belum ada catatan solusi.' }}</p>
                </div>
              </div>
            </div>

            @if($report->resolved_at)
              <div class="text-end text-muted small mt-3">
                Diselesaikan pada: <strong>{{ $report->resolved_at->format('d M Y, H:i') }}</strong>
              </div>
            @endif
          @else
            <div class="alert alert-success border-0 py-3 px-4 mb-0 shadow-sm" style="border-left: 4px solid #99C2FF !important; background: linear-gradient(135deg, rgba(153, 194, 255, 0.08), rgba(255, 132, 186, 0.08)) !important;">
              <div class="d-flex align-items-start">
                <i class="bi bi-clock-fill text-primary fs-4 me-3"></i>
                <div>
                  <h6 class="fw-bold mb-1" style="color: var(--text-primary);">Menunggu Penugasan Teknisi</h6>
                  <p class="mb-0 text-muted small" style="line-height: 1.5;">Laporan Anda telah berhasil diajukan dan saat ini terdaftar di antrian sistem. Tim Jarkom sedang meninjau dan akan segera menugaskan petugas teknis.</p>
                </div>
              </div>
            </div>
          @endif

        </div>
      </div>
    </div>

  </div>
</section>
@endsection
