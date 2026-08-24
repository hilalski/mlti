@extends('layouts.app')

@section('title', 'Manajemen Perangkat TI | Tim Jarkom')

@section('content')
<div class="pagetitle">
  <h1 class="fw-bold" style="color: var(--color-primary);"><i class="bi bi-laptop-fill me-1"></i> Manajemen Perangkat TI</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
      <li class="breadcrumb-item active">Kelola Perangkat</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 15px 20px !important; margin: -24px -24px 20px -24px !important; border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white"><i class="bi bi-list-task me-1"></i> Daftar Perangkat TI</h5>
          <a href="{{ route('admin.devices.create') }}" class="btn btn-sm btn-light" style="color: #FF84BA !important; border: none !important; font-weight: 600;"><i class="bi bi-plus-circle-fill me-1"></i> Tambah Perangkat</a>
        </div>

        <div class="card-body pt-3">
          
          <!-- Search and Multi-Criteria Filters -->
          <form method="GET" action="{{ route('admin.devices.index') }}" class="row g-3 mb-4 p-3 bg-light rounded align-items-end">
            <div class="col-md-3">
              <label for="search" class="form-label small fw-bold text-dark">Cari Perangkat</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search text-secondary"></i></span>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Merek, Seri, BMN, S/N...">
              </div>
            </div>

            <div class="col-md-3">
              <label for="type_id" class="form-label small fw-bold text-dark">Tipe</label>
              <select name="type_id" id="type_id" class="form-select form-select-sm">
                <option value="">-- Semua Tipe --</option>
                @foreach($types as $type)
                  <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>
                    {{ $type->jenis }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-2">
              <label for="condition_id" class="form-label small fw-bold text-dark">Kondisi</label>
              <select name="condition_id" id="condition_id" class="form-select form-select-sm">
                <option value="">-- Semua Kondisi --</option>
                @foreach($conditions as $cond)
                  <option value="{{ $cond->id }}" {{ request('condition_id') == $cond->id ? 'selected' : '' }}>
                    {{ $cond->kondisi }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-2">
              <label for="room_id" class="form-label small fw-bold text-dark">Ruangan</label>
              <select name="room_id" id="room_id" class="form-select form-select-sm">
                <option value="">-- Semua Ruangan --</option>
                @foreach($rooms as $room)
                  <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                    {{ $room->ruang }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-2 d-flex gap-1">
              <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-funnel"></i> Filter</button>
              <a href="{{ route('admin.devices.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
            </div>
          </form>

          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th scope="col">Kode BMN</th>
                  <th scope="col">Kategori</th>
                  <th scope="col">Merek & Seri</th>
                  <th scope="col">No Seri</th>
                  <th scope="col">Kondisi</th>
                  <th scope="col">Pemilik</th>
                  <th scope="col" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($devices as $d)
                  <tr>
                    <td class="fw-bold text-dark small">{{ $d->id }}</td>
                    <td>
                      <span class="badge bg-light text-primary border border-primary-subtle px-2 py-1 small fw-bold">
                        {{ $d->type->jenis ?? 'Lainnya' }}
                      </span>
                    </td>
                    <td>
                      <div class="fw-bold text-dark small">{{ $d->brand }}</div>
                      <small class="text-muted" style="font-size: 0.75rem;">Seri: {{ $d->series }}</small>
                    </td>
                    <td class="small">{{ $d->serial_number ?: '-' }}</td>
                    <td>
                      <span class="badge bg-{{ $d->id_condition == 1 ? 'success' : ($d->id_condition == 2 ? 'warning text-dark' : 'danger') }} px-2.5 py-1 text-white">
                        {{ $d->condition->kondisi ?? 'N/A' }}
                      </span>
                    </td>
                    <td>
                      @if($d->user)
                        <div class="fw-bold" style="color: #FF84BA;">{{ $d->user->name }}</div>
                      @elseif($d->room)
                        <div class="fw-bold" style="color: #99C2FF;">{{ $d->room->ruang }}</div>
                      @else
                        <span class="text-muted small">Tidak Ada (Gudang)</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('admin.devices.edit', $d->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2.5">
                          <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <form action="{{ route('admin.devices.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perangkat TI ini?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2.5">
                            <i class="bi bi-trash-fill"></i> Hapus
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                      <i class="bi bi-laptop fs-2 d-block mb-2"></i>
                      Tidak ada perangkat TI yang cocok dengan kriteria pencarian.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-end mt-3">
            {{ $devices->links() }}
          </div>

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
