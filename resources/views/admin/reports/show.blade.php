@extends('layouts.app')

@section('title', 'Detail Penanganan Laporan | MLTI-Report')

@section('content')
<div class="pagetitle">
  <h1>Penanganan Laporan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Daftar Laporan</a></li>
      <li class="breadcrumb-item active">Detail Laporan</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    
    <!-- Report Details (Left column) -->
    <div class="col-lg-6 col-md-12">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title my-0"><i class="bi bi-info-circle me-1 text-primary"></i> Detail Laporan</h5>
        </div>
        <div class="card-body pt-3">
          
          <table class="table table-hover align-middle mb-0">
            <tr>
              <td class="fw-bold text-dark" style="width: 180px; border-bottom: 1px solid #f1f5f9;">Pelapor</td>
              <td style="border-bottom: 1px solid #f1f5f9;">{{ $report->reporter->name ?? 'N/A' }}</td>
            </tr>
            <tr>
              <td class="fw-bold text-dark" style="border-bottom: 1px solid #f1f5f9;">Jabatan</td>
              <td style="border-bottom: 1px solid #f1f5f9;">{{ $report->reporter->jabatan ?? '-' }}</td>
            </tr>
            <tr>
              <td class="fw-bold text-dark" style="border-bottom: 1px solid #f1f5f9;">Perangkat / Lokasi</td>
              <td style="border-bottom: 1px solid #f1f5f9;">
                @if($report->device)
                  <span class="fw-bold">{{ $report->device->brand ?? 'Perangkat' }} - {{ $report->device->series ?? '' }}</span>
                  <span class="text-muted ms-2" style="font-size: 0.8rem;">(BMN: {{ $report->device_id }})</span>
                @else
                  <span class="fw-bold text-primary"><i class="bi bi-wifi me-1"></i> Jaringan {{ $report->room->ruang ?? 'Ruangan' }}</span>
                  <span class="badge bg-light text-primary border border-primary-subtle ms-2 small">Seluruh Ruangan</span>
                @endif
              </td>
            </tr>
            <tr>
              <td class="fw-bold text-dark" style="border-bottom: 1px solid #f1f5f9;">Jenis Kendala</td>
              <td style="border-bottom: 1px solid #f1f5f9;"><span class="badge badge-{{ $report->issue_type }}">{{ ucfirst($report->issue_type) }}</span></td>
            </tr>
            <tr>
              <td class="fw-bold text-dark" style="border-bottom: 1px solid #f1f5f9;">Tanggal Lapor</td>
              <td style="border-bottom: 1px solid #f1f5f9;">{{ $report->created_at->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
              <td class="fw-bold text-dark" style="border-bottom: 1px solid #f1f5f9;">Deskripsi Kendala</td>
              <td style="border-bottom: 1px solid #f1f5f9; white-space: pre-line;">{{ $report->description }}</td>
            </tr>
            <tr>
              <td class="fw-bold text-dark" style="border-bottom: none;">Status Saat Ini</td>
              <td style="border-bottom: none;">
                <span class="badge bg-{{ $report->status == 'menunggu' ? 'secondary' : ($report->status == 'diproses' ? 'primary' : ($report->status == 'selesai' ? 'success' : 'danger')) }} py-1 px-2">
                  {{ ucfirst($report->status) }}
                </span>
              </td>
            </tr>
          </table>

        </div>
      </div>
    </div>

    <!-- Actions & Technician Form (Right column) -->
    <div class="col-lg-6 col-md-12">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title my-0"><i class="bi bi-wrench-adjustable me-1 text-primary"></i> Form Penanganan Teknis</h5>
          <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
        
        <div class="card-body pt-3">
          
          <form action="{{ route('admin.reports.update', $report->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <!-- Status Selection -->
            <div class="mb-3">
              <label for="status" class="form-label fw-bold text-dark">Ubah Status Perbaikan</label>
              <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="menunggu" {{ $report->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="diproses" {{ $report->status == 'diproses' ? 'selected' : '' }}>Diproses (Sedang Diperbaiki)</option>
                <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>Selesai (Perbaikan Selesai)</option>
                <option value="ditolak" {{ $report->status == 'ditolak' ? 'selected' : '' }}>Ditolak (Laporan Dibatalkan/Ditolak)</option>
              </select>
              @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Technician Assignment -->
            <div class="mb-3">
              <label for="handled_by" class="form-label fw-bold text-dark">Petugas Teknisi</label>
              <select name="handled_by" id="handled_by" class="form-select @error('handled_by') is-invalid @enderror">
                <option value="">-- Pilih Teknisi (Ditugaskan) --</option>
                @foreach($technicians as $tech)
                  <option value="{{ $tech->nip_lama }}" {{ old('handled_by', $report->handled_by) == $tech->nip_lama ? 'selected' : '' }}>
                    {{ $tech->name }}
                  </option>
                @endforeach
              </select>
              @error('handled_by')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Vendor (Optional) -->
            <div class="mb-3">
              <label for="id_vendor" class="form-label fw-bold text-dark">Rujuk ke Vendor (Opsional)</label>
              <select name="id_vendor" id="id_vendor" class="form-select @error('id_vendor') is-invalid @enderror">
                <option value="">-- Tidak Ada Vendor (Ditangani Sendiri) --</option>
                @foreach($vendors as $vendor)
                  <option value="{{ $vendor->id }}" {{ $report->id_vendor == $vendor->id ? 'selected' : '' }}>{{ $vendor->vendor_service }}</option>
                @endforeach
              </select>
              @error('id_vendor')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Technician Notes -->
            <div class="mb-4">
              <label for="technician_notes" class="form-label fw-bold text-dark">Catatan Tindakan / Solusi Teknisi</label>
              <textarea name="technician_notes" id="technician_notes" rows="6" class="form-control @error('technician_notes') is-invalid @enderror" placeholder="Tuliskan tindakan yang diambil, komponen yang diganti, atau alasan laporan ditolak/ditunda...">{{ old('technician_notes', $report->technician_notes) }}</textarea>
              @error('technician_notes')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 btn-accent">Simpan & Perbarui Status</button>

          </form>

        </div>
      </div>
    </div>

  </div>
</section>
@endsection
