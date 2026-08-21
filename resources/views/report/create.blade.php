@extends('layouts.app')

@title('Buat Laporan Kerusakan | MLTI-Report')

@section('content')
<div class="pagetitle">
  <h1>Laporkan Kerusakan Perangkat</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
      <li class="breadcrumb-item active">Buat Laporan</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">

      <div class="card">
        <div class="card-header d-flex align-items-center">
          <i class="bi bi-exclamation-triangle me-2 fs-5"></i>
          <h5 class="card-title my-0 text-white">Form Pelaporan Kerusakan</h5>
        </div>

        <div class="card-body pt-3">
          
          <!-- Device Info Highlight -->
          <div class="alert alert-light border mb-4 p-3 d-flex align-items-center justify-content-between">
            <div>
              <h6 class="mb-1 fw-bold text-dark">{{ $device->brand }} - {{ $device->series }}</h6>
              <p class="mb-0 text-muted small">Kode BMN: <strong>{{ $device->id }}</strong> | S/N: {{ $device->serial_number ?: '-' }}</p>
            </div>
            <span class="badge bg-secondary p-2">{{ $device->type->jenis ?? 'Lainnya' }}</span>
          </div>

          <form action="{{ route('report.store') }}" method="POST">
            @csrf

            <!-- Hidden input for device_id -->
            <input type="hidden" name="device_id" value="{{ $device->id }}">

            <!-- Issue Type Select -->
            <div class="mb-3">
              <label for="issue_type" class="form-label fw-bold text-dark">Jenis Kendala <span class="text-danger">*</span></label>
              <select class="form-select @error('issue_type') is-invalid @enderror" id="issue_type" name="issue_type" required>
                <option value="" disabled selected>-- Pilih Jenis Kendala --</option>
                <option value="hardware" {{ old('issue_type') == 'hardware' ? 'selected' : '' }}>Hardware (Perangkat Keras: Monitor, RAM, Harddisk, Mati Total)</option>
                <option value="software" {{ old('issue_type') == 'software' ? 'selected' : '' }}>Software (Perangkat Lunak: OS Lambat, Aplikasi Crash, Lisensi)</option>
                <option value="jaringan" {{ old('issue_type') == 'jaringan' ? 'selected' : '' }}>Jaringan (Internet Lambat, Tidak Terkoneksi WiFi/LAN, Akses Portal)</option>
              </select>
              @error('issue_type')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Description Textarea -->
            <div class="mb-4">
              <label for="description" class="form-label fw-bold text-dark">Deskripsi Kendala <span class="text-danger">*</span></label>
              <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Jelaskan secara mendetail kendala atau kerusakan yang dialami oleh perangkat tersebut..." required>{{ old('description') }}</textarea>
              <div class="form-text small text-muted">Jelaskan kronologis kendala secara jelas untuk memudahkan tim teknis melakukan diagnosis.</div>
              @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Action buttons -->
            <div class="d-flex justify-content-end gap-2 border-top pt-3">
              <a href="{{ route('dashboard') }}" class="btn btn-secondary px-4">Batal</a>
              <button type="submit" class="btn btn-primary px-4 btn-accent">Kirim Laporan</button>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
