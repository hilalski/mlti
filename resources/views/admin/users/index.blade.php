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
          <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-4 p-3 bg-light rounded align-items-end">
            <div class="col-12 col-md-5">
              <label for="search" class="form-label small fw-bold text-dark">Cari Pengguna</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search text-secondary"></i></span>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari Nama, NIP, Jabatan...">
              </div>
            </div>
            <div class="col-8 col-md-4">
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
            <div class="col-4 col-md-3 d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-funnel"></i> Filter</button>
              <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
          </form>

          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th scope="col">Nama & NIP</th>
                  <th scope="col" class="d-none d-md-table-cell">Jabatan</th>
                  <th scope="col" class="d-none d-sm-table-cell">Ruangan</th>
                  <th scope="col">Akses</th>
                  <th scope="col" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($users as $u)
                  <tr>
                    {{-- Name & NIP – always visible --}}
                    <td style="max-width: 200px;">
                      <div class="fw-bold text-dark" style="font-size: 0.875rem;">{{ $u->name }}</div>
                      <small class="text-muted d-block" style="font-size: 0.72rem;">{{ $u->nip_lama }}</small>
                      {{-- Mobile: show jabatan + room inline --}}
                      <div class="d-sm-none mt-1">
                        @if($u->jabatan)
                          <small class="text-muted" style="font-size: 0.72rem;">{{ $u->jabatan }}</small>
                        @endif
                        <small class="d-block fw-semibold" style="color: #99C2FF; font-size: 0.72rem;">
                          <i class="bi bi-house-door-fill me-1"></i>{{ $u->room->ruang ?? '-' }}
                        </small>
                      </div>
                    </td>

                    {{-- Jabatan --}}
                    <td class="d-none d-md-table-cell small">{{ $u->jabatan ?: '-' }}</td>

                    {{-- Ruangan --}}
                    <td class="d-none d-sm-table-cell">
                      <span class="small fw-semibold" style="color: #99C2FF;">
                        {{ $u->room->ruang ?? 'Tidak Diketahui' }}
                      </span>
                    </td>

                    {{-- Akses Jarkom --}}
                    <td class="text-nowrap">
                      @if($u->is_jarkom)
                        <span class="badge bg-success py-1 px-2 text-white"><i class="bi bi-shield-check me-1"></i>Jarkom</span>
                      @else
                        <span class="badge bg-secondary py-1 px-2 text-white">Pegawai</span>
                      @endif
                    </td>

                    {{-- Actions --}}
                    <td class="text-center text-nowrap">
                      <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('admin.users.edit', $u->nip_lama) }}" class="btn btn-sm btn-outline-primary py-1 px-2" title="Edit">
                          <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('admin.users.destroy', $u->nip_lama) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pegawai ini?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hapus" {{ auth()->user()->nip_lama === $u->nip_lama ? 'disabled' : '' }}>
                            <i class="bi bi-trash-fill"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
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
