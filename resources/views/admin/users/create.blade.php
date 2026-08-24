@extends('layouts.app')

@section('title', 'Tambah Akun Pengguna | Tim Jarkom')

@section('content')
<div class="pagetitle">
  <h1 class="fw-bold" style="color: var(--color-primary);"><i class="bi bi-person-plus-fill me-1"></i> Tambah Akun Pengguna</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Kelola Akun</a></li>
      <li class="breadcrumb-item active">Tambah Akun</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">

      <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 15px 20px !important; margin: -24px -24px 20px -24px !important; border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white"><i class="bi bi-person-lines-fill me-1"></i> Form Pengguna Baru</h5>
          <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light" style="color: #FF84BA !important; border: none !important; font-weight: 600;"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        <div class="card-body pt-3">
          
          <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="row g-3">
              <!-- NIP Lama -->
              <div class="col-md-6">
                <label for="nip_lama" class="form-label fw-bold text-dark">NIP Lama <span class="text-danger">*</span></label>
                <input type="number" name="nip_lama" id="nip_lama" value="{{ old('nip_lama') }}" class="form-control @error('nip_lama') is-invalid @enderror" required placeholder="Contoh: 340014682">
                @error('nip_lama')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- NIP Baru -->
              <div class="col-md-6">
                <label for="nip_baru" class="form-label fw-bold text-dark">NIP Baru</label>
                <input type="text" name="nip_baru" id="nip_baru" value="{{ old('nip_baru') }}" class="form-control @error('nip_baru') is-invalid @enderror" placeholder="Contoh: 196902101994031006">
                @error('nip_baru')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Nama Lengkap -->
              <div class="col-md-12">
                <label for="name" class="form-label fw-bold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required placeholder="Contoh: Sutino, S.E.">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Email -->
              <div class="col-md-12">
                <label for="email" class="form-label fw-bold text-dark">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Contoh: sutino@example.com">
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Fungsi / Team -->
              <div class="col-md-6">
                <label for="fungsi" class="form-label fw-bold text-dark">Fungsi (Tim Kerja) <span class="text-danger">*</span></label>
                <select name="fungsi" id="fungsi" class="form-select @error('fungsi') is-invalid @enderror" required>
                  <option value="">-- Pilih Tim Kerja --</option>
                  @foreach($teams as $team)
                    <option value="{{ $team->id }}" {{ old('fungsi', 99) == $team->id ? 'selected' : '' }}>
                      Fungsi {{ $team->id }} - {{ $team->fungsi }}
                    </option>
                  @endforeach
                </select>
                @error('fungsi')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Jabatan -->
              <div class="col-md-6">
                <label for="jabatan" class="form-label fw-bold text-dark">Jabatan</label>
                <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}" class="form-control @error('jabatan') is-invalid @enderror" placeholder="Contoh: Arsiparis Ahli Madya">
                @error('jabatan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Ruangan -->
              <div class="col-md-6">
                <label for="id_ruang" class="form-label fw-bold text-dark">Ruangan</label>
                <select name="id_ruang" id="id_ruang" class="form-select @error('id_ruang') is-invalid @enderror">
                  <option value="">-- Pilih Ruangan --</option>
                  @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ old('id_ruang') == $room->id ? 'selected' : '' }}>
                      {{ $room->ruang }}
                    </option>
                  @endforeach
                </select>
                @error('id_ruang')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Hak Akses Jarkom -->
              <div class="col-md-6">
                <label for="is_jarkom" class="form-label fw-bold text-dark">Hak Akses</label>
                <select name="is_jarkom" id="is_jarkom" class="form-select @error('is_jarkom') is-invalid @enderror">
                  <option value="0" {{ old('is_jarkom') == '0' ? 'selected' : '' }}>Standard (Pegawai)</option>
                  <option value="1" {{ old('is_jarkom') == '1' ? 'selected' : '' }}>Administrator (Tim Jarkom)</option>
                </select>
                @error('is_jarkom')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Password -->
              <div class="col-md-12">
                <label for="password" class="form-label fw-bold text-dark">Kata Sandi (Password)</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Biarkan kosong untuk menggunakan password default: 'password'">
                <small class="text-muted text-xs">Kata sandi minimal berisi 6 karakter.</small>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
              <button type="reset" class="btn btn-secondary me-2"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
              <button type="submit" class="btn btn-primary btn-accent"><i class="bi bi-save"></i> Simpan Pengguna</button>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
