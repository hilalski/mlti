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
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title my-0 text-white"><i class="bi bi-list-task me-1"></i> Semua Laporan Kerusakan</h5>
        </div>

        <div class="card-body pt-3">
          
          <!-- Filters -->
          <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 mb-4 p-3 bg-light rounded align-items-end">
            <div class="col-md-4">
              <label for="status" class="form-label small fw-bold text-dark">Filter Status</label>
              <select name="status" id="status" class="form-select form-select-sm">
                <option value="">-- Semua Status --</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
              </select>
            </div>

            <div class="col-md-4">
              <label for="issue_type" class="form-label small fw-bold text-dark">Filter Kendala</label>
              <select name="issue_type" id="issue_type" class="form-select form-select-sm">
                <option value="">-- Semua Kendala --</option>
                <option value="hardware" {{ request('issue_type') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                <option value="software" {{ request('issue_type') == 'software' ? 'selected' : '' }}>Software</option>
                <option value="jaringan" {{ request('issue_type') == 'jaringan' ? 'selected' : '' }}>Jaringan</option>
              </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-funnel"></i> Filter</button>
              <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
            </div>
          </form>

          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th scope="col">Pelapor</th>
                  <th scope="col">Perangkat</th>
                  <th scope="col">Jenis</th>
                  <th scope="col">Deskripsi</th>
                  <th scope="col">Tanggal Lapor</th>
                  <th scope="col">Status</th>
                  <th scope="col" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($reports as $report)
                  <tr>
                    <td>
                      <div class="fw-bold text-dark small">{{ $report->reporter->name ?? 'N/A' }}</div>
                    </td>
                    <td>
                      <div class="small fw-bold text-dark">{{ $report->device->brand ?? 'N/A' }}</div>
                      <small class="text-muted" style="font-size: 0.75rem;">BMN: {{ $report->device_id }}</small>
                    </td>
                    <td>
                      <span class="badge badge-{{ $report->issue_type }}">{{ ucfirst($report->issue_type) }}</span>
                    </td>
                    <td>
                      <span class="small" title="{{ $report->description }}">{{ Str::limit($report->description, 50) }}</span>
                    </td>
                    <td class="small">
                      {{ $report->created_at->format('d M Y, H:i') }}
                    </td>
                    <td>
                      <span class="badge bg-{{ $report->status == 'menunggu' ? 'secondary' : ($report->status == 'diproses' ? 'primary' : ($report->status == 'selesai' ? 'success' : 'danger')) }} py-1 px-2">
                        {{ ucfirst($report->status) }}
                      </span>
                    </td>
                    <td class="text-center">
                      <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-primary py-1 px-2">
                        <i class="bi bi-pencil-square"></i> Tangani
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Tidak ada laporan kerusakan ditemukan.</td>
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
