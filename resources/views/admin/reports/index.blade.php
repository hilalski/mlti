@extends('layouts.app')

@title('Daftar Laporan Kerusakan | MLTI-Report')

@section('content')
<div class="pagetitle">
  <h1>Kelola Laporan Kerusakan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
      <li class="breadcrumb-item active">Daftar Laporan</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gap-2">
          <h5 class="card-title my-0 text-white"><i class="bi bi-list-task me-1"></i> Semua Laporan Kerusakan</h5>
          <a href="{{ route('admin.reports.export', request()->only(['status', 'issue_type'])) }}" class="btn btn-sm btn-light text-success text-nowrap">
            <i class="bi bi-file-earmark-excel me-1"></i> Unduh Excel
          </a>
        </div>

        <div class="card-body pt-3">

          <!-- Filters -->
          <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-2 mb-4 p-3 bg-light rounded align-items-end">
            <div class="col-6 col-md-4">
              <label for="status" class="form-label small fw-bold text-dark">Filter Status</label>
              <select name="status" id="status" class="form-select form-select-sm">
                <option value="">-- Semua Status --</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
              </select>
            </div>

            <div class="col-6 col-md-4">
              <label for="issue_type" class="form-label small fw-bold text-dark">Filter Kendala</label>
              <select name="issue_type" id="issue_type" class="form-select form-select-sm">
                <option value="">-- Semua Kendala --</option>
                <option value="hardware" {{ request('issue_type') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                <option value="software" {{ request('issue_type') == 'software' ? 'selected' : '' }}>Software</option>
                <option value="jaringan" {{ request('issue_type') == 'jaringan' ? 'selected' : '' }}>Jaringan</option>
              </select>
            </div>

            <div class="col-12 col-md-4 d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-funnel"></i> Filter</button>
              <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
            </div>
          </form>

          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th scope="col">Pelapor & Perangkat</th>
                  <th scope="col" class="d-none d-sm-table-cell">Jenis</th>
                  <th scope="col" class="d-none d-md-table-cell">Deskripsi</th>
                  <th scope="col" class="d-none d-lg-table-cell">Tanggal</th>
                  <th scope="col">Status</th>
                  <th scope="col" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($reports as $report)
                  <tr>
                    {{-- Reporter + Device (always visible, stacked) --}}
                    <td style="max-width: 200px;">
                      <small class="text-primary fw-bold d-block" style="font-size: 0.7rem;">{{ $report->ticket_id }}</small>
                      <div class="fw-semibold text-dark" style="font-size: 0.82rem;">{{ $report->reporter->name ?? 'N/A' }}</div>
                      @if($report->device)
                        <div class="fw-bold text-dark" style="font-size: 0.8rem; line-height: 1.2;">{{ $report->device->brand ?? '' }} {{ $report->device->series ?? '' }}</div>
                        <small class="text-muted d-block" style="font-size: 0.7rem;">{{ $report->device_id }}</small>
                      @else
                        <div class="fw-bold text-primary" style="font-size: 0.8rem;"><i class="bi bi-wifi me-1"></i>{{ $report->room->ruang ?? 'Ruangan' }}</div>
                        <small class="text-muted d-block" style="font-size: 0.7rem;">Seluruh Ruangan</small>
                      @endif
                      {{-- Mobile: show type + date inline --}}
                      <div class="d-flex flex-wrap gap-1 mt-1 d-sm-none">
                        <span class="badge badge-{{ $report->issue_type }}" style="font-size: 0.65rem;">{{ ucfirst($report->issue_type) }}</span>
                        <span class="text-muted" style="font-size: 0.68rem;"><i class="bi bi-calendar3 me-1"></i>{{ $report->created_at->format('d M Y') }}</span>
                      </div>
                    </td>

                    {{-- Issue type --}}
                    <td class="d-none d-sm-table-cell">
                      <span class="badge badge-{{ $report->issue_type }}">{{ ucfirst($report->issue_type) }}</span>
                    </td>

                    {{-- Description --}}
                    <td class="d-none d-md-table-cell" style="max-width: 220px;">
                      <span class="small" title="{{ $report->description }}">{{ Str::limit($report->description, 50) }}</span>
                    </td>

                    {{-- Date --}}
                    <td class="d-none d-lg-table-cell small text-nowrap">
                      {{ $report->created_at->format('d M Y, H:i') }}
                    </td>

                    {{-- Status --}}
                    <td class="text-nowrap">
                      <span class="badge bg-{{ $report->status == 'menunggu' ? 'secondary' : ($report->status == 'diproses' ? 'primary' : ($report->status == 'selesai' ? 'success' : 'danger')) }} py-1 px-2">
                        {{ ucfirst($report->status) }}
                      </span>
                    </td>

                    {{-- Action --}}
                    <td class="text-center text-nowrap">
                      <a href="{{ route('admin.reports.show', $report->ticket_id) }}" class="btn btn-sm btn-primary py-1 px-2">
                        <i class="bi bi-pencil-square"></i><span class="d-none d-sm-inline ms-1">Tangani</span>
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Tidak ada laporan kerusakan ditemukan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="mt-3">
            {{ $reports->links() }}
          </div>

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
