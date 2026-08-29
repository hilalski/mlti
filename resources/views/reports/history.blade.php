@extends('layouts.app')

@section('title', 'Riwayat Laporan Kendala | MLTI-Report')

@section('content')
<div class="pagetitle d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
  <div>
    <h1 class="fw-bold" style="color: var(--color-primary);">Riwayat Laporan Kendala</h1>
    <nav>
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
        <li class="breadcrumb-item active">Riwayat Laporan</li>
      </ol>
    </nav>
  </div>
  <div>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 py-1 fw-semibold">
      <i class="bi bi-arrow-left me-1"></i> Ke Dashboard
    </a>
  </div>
</div>

<section class="section">
  <div class="row">
    <div class="col-12">

      <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 16px 20px !important; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white"><i class="bi bi-list-task me-2"></i> Daftar Laporan Kendala Saya</h5>
        </div>

        <div class="card-body p-2 p-md-4">

          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 report-history-table">
              <thead class="table-light">
                <tr>
                  <th scope="col">Perangkat / Lokasi</th>
                  <th scope="col" class="d-none d-sm-table-cell">Jenis</th>
                  <th scope="col" class="d-none d-md-table-cell">Deskripsi</th>
                  <th scope="col" class="d-none d-lg-table-cell">Tanggal</th>
                  <th scope="col">Status</th>
                  <th scope="col" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($reports as $report)
                  @php
                    $statusColor = match($report->status) {
                      'menunggu' => 'warning text-dark',
                      'diproses' => 'primary',
                      'selesai' => 'success',
                      'ditolak' => 'danger',
                      default => 'secondary'
                    };
                  @endphp
                  <tr>
                    {{-- Device / Location --}}
                    <td style="max-width: 200px;">
                      @if($report->device)
                        <div class="fw-bold text-dark" style="font-size: 0.875rem; line-height: 1.3;">
                          {{ $report->device->brand ?? 'Perangkat' }} {{ $report->device->series ?? '' }}
                        </div>
                        <small class="text-muted d-block" style="font-size: 0.72rem;">{{ $report->device_id }}</small>
                      @else
                        <div class="fw-bold text-primary" style="font-size: 0.875rem; line-height: 1.3;">
                          <i class="bi bi-wifi me-1"></i>{{ $report->room->ruang ?? 'Ruangan' }}
                        </div>
                        <small class="text-muted d-block" style="font-size: 0.72rem;">Jaringan Ruangan</small>
                      @endif
                      {{-- On mobile: show type + date inline below the name --}}
                      <div class="d-flex flex-wrap gap-1 mt-1 d-sm-none">
                        <span class="badge badge-{{ $report->issue_type }}" style="font-size: 0.65rem;">{{ ucfirst($report->issue_type) }}</span>
                        <span class="text-muted" style="font-size: 0.68rem;"><i class="bi bi-calendar3 me-1"></i>{{ $report->created_at->format('d M Y') }}</span>
                      </div>
                    </td>

                    {{-- Issue type – hidden on xs --}}
                    <td class="d-none d-sm-table-cell">
                      <span class="badge badge-{{ $report->issue_type }}">{{ ucfirst($report->issue_type) }}</span>
                    </td>

                    {{-- Description – hidden on xs & sm --}}
                    <td class="d-none d-md-table-cell" style="max-width: 220px;">
                      <span class="small text-secondary" title="{{ $report->description }}">{{ Str::limit($report->description, 55) }}</span>
                    </td>

                    {{-- Date – hidden on xs, sm, md --}}
                    <td class="d-none d-lg-table-cell small text-muted text-nowrap">
                      {{ $report->created_at->format('d M Y, H:i') }}
                    </td>

                    {{-- Status --}}
                    <td class="text-nowrap">
                      <span class="badge bg-{{ $statusColor }} py-1 px-2">{{ ucfirst($report->status) }}</span>
                    </td>

                    {{-- Action --}}
                    <td class="text-center text-nowrap">
                      <a href="{{ route('reports.history.show', $report->id) }}" class="btn btn-sm btn-primary py-1 px-2 fw-semibold">
                        <i class="bi bi-eye-fill"></i><span class="d-none d-sm-inline ms-1">Detail</span>
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                      <i class="bi bi-clipboard-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                      <p class="mb-0 fw-semibold">Anda belum pernah membuat laporan kendala perangkat.</p>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          @if($reports->hasPages())
            <div class="d-flex justify-content-center justify-content-md-end mt-3 pt-2 border-top">
              {{ $reports->links() }}
            </div>
          @endif

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
