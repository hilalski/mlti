@extends('layouts.app')

@section('title', 'Edit Perangkat TI | Tim Jarkom')

@section('content')
<div class="pagetitle">
  <h1 class="fw-bold" style="color: var(--color-primary);"><i class="bi bi-laptop me-1"></i> Edit Perangkat TI</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.devices.index') }}">Kelola Perangkat</a></li>
      <li class="breadcrumb-item active">Edit Perangkat</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">

      <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 15px 20px !important; margin: -24px -24px 20px -24px !important; border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white"><i class="bi bi-info-circle me-1"></i> Form Edit Perangkat: {{ $device->brand }} (BMN: {{ $device->id }})</h5>
          <a href="{{ route('admin.devices.index') }}" class="btn btn-sm btn-light" style="color: #FF84BA !important; border: none !important; font-weight: 600;"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        <div class="card-body pt-3">
          
          <form action="{{ route('admin.devices.update', $device->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
              <!-- Kode BMN -->
              <div class="col-md-6">
                <label for="id" class="form-label fw-bold text-dark">Kode BMN <span class="text-danger">*</span></label>
                <input type="text" name="id" id="id" value="{{ old('id', $device->id) }}" class="form-control @error('id') is-invalid @enderror" required placeholder="Contoh: 3100102001-108">
                @error('id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Kategori Perangkat -->
              <div class="col-md-6">
                <label for="id_type" class="form-label fw-bold text-dark">Kategori Perangkat <span class="text-danger">*</span></label>
                <select name="id_type" id="id_type" class="form-select @error('id_type') is-invalid @enderror" required>
                  <option value="">-- Pilih Kategori --</option>
                  @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ old('id_type', $device->id_type) == $type->id ? 'selected' : '' }}>
                      {{ $type->jenis }}
                    </option>
                  @endforeach
                </select>
                @error('id_type')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Merek -->
              <div class="col-md-6">
                <label for="brand" class="form-label fw-bold text-dark">Merek <span class="text-danger">*</span></label>
                <input type="text" name="brand" id="brand" value="{{ old('brand', $device->brand) }}" class="form-control @error('brand') is-invalid @enderror" required placeholder="Contoh: DELL, ASUS, Brother">
                @error('brand')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Seri / Model -->
              <div class="col-md-6">
                <label for="series" class="form-label fw-bold text-dark">Seri / Model <span class="text-danger">*</span></label>
                <input type="text" name="series" id="series" value="{{ old('series', $device->series) }}" class="form-control @error('series') is-invalid @enderror" required placeholder="Contoh: Optiplex 3040, DCP-T710W">
                @error('series')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Nomor Seri -->
              <div class="col-md-6">
                <label for="serial_number" class="form-label fw-bold text-dark">Nomor Seri (S/N)</label>
                <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $device->serial_number) }}" class="form-control @error('serial_number') is-invalid @enderror" placeholder="Contoh: CN0Y13H6">
                @error('serial_number')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Tahun Perolehan -->
              <div class="col-md-6">
                <label for="year" class="form-label fw-bold text-dark">Tahun Perolehan</label>
                <input type="number" name="year" id="year" value="{{ old('year', $device->year) }}" class="form-control @error('year') is-invalid @enderror" placeholder="Contoh: 2018">
                @error('year')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Asal Pengadaan -->
              <div class="col-md-6">
                <label for="id_source" class="form-label fw-bold text-dark">Asal Pengadaan <span class="text-danger">*</span></label>
                <select name="id_source" id="id_source" class="form-select @error('id_source') is-invalid @enderror" required>
                  <option value="">-- Pilih Asal Pengadaan --</option>
                  @foreach($sources as $source)
                    <option value="{{ $source->id }}" {{ old('id_source', $device->id_source) == $source->id ? 'selected' : '' }}>
                      {{ $source->asal }}
                    </option>
                  @endforeach
                </select>
                @error('id_source')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Status BMN -->
              <div class="col-md-6">
                <label for="id_status_bmn" class="form-label fw-bold text-dark">Status BMN <span class="text-danger">*</span></label>
                <select name="id_status_bmn" id="id_status_bmn" class="form-select @error('id_status_bmn') is-invalid @enderror" required>
                  <option value="">-- Pilih Status BMN --</option>
                  @foreach($statusBmns as $status)
                    <option value="{{ $status->id }}" {{ old('id_status_bmn', $device->id_status_bmn) == $status->id ? 'selected' : '' }}>
                      {{ $status->status }}
                    </option>
                  @endforeach
                </select>
                @error('id_status_bmn')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Kondisi Perangkat -->
              <div class="col-md-6">
                <label for="id_condition" class="form-label fw-bold text-dark">Kondisi Perangkat <span class="text-danger">*</span></label>
                <select name="id_condition" id="id_condition" class="form-select @error('id_condition') is-invalid @enderror" required>
                  <option value="">-- Pilih Kondisi --</option>
                  @foreach($conditions as $cond)
                    <option value="{{ $cond->id }}" {{ old('id_condition', $device->id_condition) == $cond->id ? 'selected' : '' }}>
                      {{ $cond->kondisi }}
                    </option>
                  @endforeach
                </select>
                @error('id_condition')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Penempatan / Pemilik -->
              <div class="col-md-6">
                <label for="id_user" class="form-label fw-bold text-dark">Penempatan / Pemilik</label>
                <select name="id_user" id="id_user" class="form-select @error('id_user') is-invalid @enderror">
                  <option value="">-- Tidak Ada / Gudang (15) --</option>
                  
                  <optgroup label="Pegawai / User">
                    @foreach($users as $user)
                      <option value="{{ $user->nip_lama }}" {{ old('id_user', $device->id_user) == $user->nip_lama ? 'selected' : '' }}>
                        {{ $user->name }} (NIP: {{ $user->nip_lama }})
                      </option>
                    @endforeach
                  </optgroup>
                  
                  <optgroup label="Ruangan">
                    @foreach($rooms as $room)
                      <option value="{{ $room->id }}" {{ old('id_user', $device->id_user) == $room->id ? 'selected' : '' }}>
                        {{ $room->ruang }} (ID: {{ $room->id }})
                      </option>
                    @endforeach
                  </optgroup>
                </select>
                @error('id_user')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Keterangan -->
              <div class="col-md-12">
                <label for="keterangan" class="form-label fw-bold text-dark">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="4" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Tuliskan spesifikasi detail tambahan, riwayat upgrade, atau catatan lainnya tentang perangkat ini...">{{ old('keterangan', $device->keterangan) }}</textarea>
                @error('keterangan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
              <button type="submit" class="btn btn-primary btn-accent"><i class="bi bi-save"></i> Perbarui Perangkat</button>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
