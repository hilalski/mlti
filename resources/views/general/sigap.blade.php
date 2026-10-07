@extends('layouts.app')
@section('title', 'SIGAP | MLTI')
@section('content')
<div class="pagetitle"><h1>SIGAP</h1><p class="text-muted mb-0">Pengaduan kerusakan fasilitas umum.</p></div>
<section class="section"><div class="row justify-content-center"><div class="col-lg-8"><div class="card"><div class="card-body pt-4"><form method="POST" action="{{ route('general.sigap.store') }}">@csrf
  <div class="mb-3"><label class="form-label">Lokasi <span class="text-danger">*</span></label><input name="location" value="{{ old('location') }}" class="form-control @error('location') is-invalid @enderror" placeholder="Contoh: Lantai 2, Ruang Rapat" required>@error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
  <div class="mb-3"><label class="form-label">Jenis Kerusakan <span class="text-danger">*</span></label><select name="category" class="form-select" required><option value="">Pilih jenis kerusakan</option><option value="AC rusak">AC rusak</option><option value="Pintu rusak">Pintu rusak</option><option value="Listrik">Listrik</option><option value="Plumbing">Plumbing / air</option><option value="Lainnya">Lainnya</option></select></div>
  <div class="mb-3"><label class="form-label">Deskripsi <span class="text-danger">*</span></label><textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea></div>
  <button class="btn btn-primary"><i class="bi bi-send me-1"></i>Kirim Pengaduan</button>
</form></div></div></div></div>
</section>
@endsection
