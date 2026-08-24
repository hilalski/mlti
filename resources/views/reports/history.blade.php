@extends('layouts.app')

@section('title', 'Riwayat Laporan Kerusakan | MLTI-Report')

@section('content')
<div class="pagetitle">
  <h1 class="fw-bold" style="color: var(--color-primary);">Riwayat Laporan Kerusakan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
      <li class="breadcrumb-item active">Riwayat Laporan</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 15px 20px !important; margin: -24px -24px 20px -24px !important; border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white"><i class="bi bi-list-task me-1"></i> Daftar Laporan Kerusakan Saya</h5>
        </div>

        <div class="card-body pt-3">
          
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th scope="col">Perangkat</th>
                  <th scope="col">Jenis Kendala</th>
                  <th scope="col">Deskripsi Kendala</th>
                  <th scope="col">Tanggal Lapor</th>
                  <th scope="col">Status</th>
                  <th scope="col" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($reports as $report)
                  <tr>
                    <td>
                      <div class="fw-bold text-dark">{{ $report->device->brand ?? 'N/A' }} - {{ $report->device->series ?? 'N/A' }}</div>
                      <small class="text-muted" style="font-size: 0.75rem;">BMN: {{ $report->device_id }}</small>
                    </td>
                    <td>
                      <span class="badge badge-{{ $report->issue_type }}">{{ ucfirst($report->issue_type) }}</span>
                    </td>
                    <td>
                      <span class="small" title="{{ $report->description }}">{{ Str::limit($report->description, 60) }}</span>
                    </td>
                    <td class="small">
                      {{ $report->created_at->format('d M Y, H:i') }}
                    </td>
                    <td>
                      <span class="badge bg-{{ $report->status == 'menunggu' ? 'secondary' : ($report->status == 'diproses' ? 'primary' : ($report->status == 'selesai' ? 'success' : 'danger')) }} py-1 px-2 text-white">
                        {{ ucfirst($report->status) }}
                      </span>
                    </td>
                    <td class="text-center">
                      <a href="{{ route('reports.history.show', $report->id) }}" class="btn btn-sm btn-primary py-1 px-3">
                        <i class="bi bi-eye-fill"></i> Detail
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                      <i class="bi bi-clipboard-x fs-2 d-block mb-2"></i>
                      Anda belum pernah membuat laporan kerusakan perangkat.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-end mt-3">
            {{ $reports->links() }}
          </div>

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
