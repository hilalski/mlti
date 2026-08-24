@extends('layouts.app')

@section('title', 'Manajemen Akun Pengguna | Tim Jarkom')

@section('content')
<div class="pagetitle">
  <h1 class="fw-bold" style="color: var(--color-primary);"><i class="bi bi-people-fill me-1"></i> Manajemen Akun Pengguna</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
      <li class="breadcrumb-item active">Kelola Akun</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 15px 20px !important; margin: -24px -24px 20px -24px !important; border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white"><i class="bi bi-person-lines-fill me-1"></i> Daftar Pengguna</h5>
          <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-light" style="color: #FF84BA !important; border: none !important; font-weight: 600;"><i class="bi bi-plus-circle-fill me-1"></i> Tambah Akun</a>
        </div>

        <div class="card-body pt-3">
          
          <!-- Search & Room Filters -->
          <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 mb-4 p-3 bg-light rounded align-items-end">
            <div class="col-md-5">
              <label for="search" class="form-label small fw-bold text-dark">Cari Pengguna</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search text-secondary"></i></span>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari Nama, NIP, Jabatan...">
              </div>
            </div>
            <div class="col-md-4">
              <label for="room_id" class="form-label small fw-bold text-dark">Filter Ruangan</label>
              <select name="room_id" id="room_id" class="form-select form-select-sm">
                <option value="">-- Semua Ruangan --</option>
                @foreach($rooms as $room)
                  <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                    {{ $room->ruang }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-funnel"></i> Filter</button>
              <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
            </div>
          </form>

          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th scope="col">Nama & NIP</th>
                  <th scope="col">Jabatan</th>
                  <th scope="col">Ruangan</th>
                  <th scope="col">Akses Jarkom</th>
                  <th scope="col" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($users as $u)
                  <tr>
                    <td>
                      <div class="fw-bold text-dark">{{ $u->name }}</div>
                      <small class="text-muted" style="font-size: 0.75rem;">{{ $u->nip_lama }} - {{ $u->nip_baru }}</small>
                    </td>
                    <td class="small">{{ $u->jabatan ?: '-' }}</td>
                    <td>
                      <span class="small fw-semibold" style="color: #99C2FF;">
                        {{ $u->room->ruang ?? 'Tidak Diketahui' }}
                      </span>
                    </td>
                    <td>
                      @if($u->is_jarkom)
                        <span class="badge bg-success py-1 px-3 text-white"><i class="bi bi-shield-check me-1"></i> Jarkom</span>
                      @else
                        <span class="badge bg-secondary py-1 px-3 text-white">Pegawai</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('admin.users.edit', $u->nip_lama) }}" class="btn btn-sm btn-outline-primary py-1 px-2.5">
                          <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <form action="{{ route('admin.users.destroy', $u->nip_lama) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pegawai ini?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2.5" {{ auth()->user()->nip_lama === $u->nip_lama ? 'disabled' : '' }}>
                            <i class="bi bi-trash-fill"></i> Hapus
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                      <i class="bi bi-people fs-2 d-block mb-2"></i>
                      Tidak ada akun pengguna yang cocok dengan kriteria pencarian.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-end mt-3">
            {{ $users->links() }}
          </div>

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
