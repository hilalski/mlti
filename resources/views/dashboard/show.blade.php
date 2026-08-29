@extends('layouts.app')

@section('title', 'Detail Perangkat & Riwayat | MLTI-Report')

@section('content')
<div class="pagetitle">
  <h1 class="fw-bold" style="color: var(--color-primary);">Detail Perangkat TI</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Perangkat Saya</a></li>
      <li class="breadcrumb-item active">Detail</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    
    <!-- Specs Column -->
    <div class="col-lg-5 col-md-12">
      <div class="card shadow-sm border-0 mb-4 h-100">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 15px 20px !important; margin: -24px -24px 20px -24px !important; border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white"><i class="bi bi-info-circle me-1"></i> Spesifikasi Perangkat</h5>
        </div>
        <div class="card-body pt-3">
          
          <div class="text-center py-3 border-bottom mb-3">
            <div class="p-3 rounded-circle fs-1 mx-auto d-flex align-items-center justify-content-center" style="width: 75px; height: 75px; background: #fff1f7; color: #FF84BA; border: 1px solid #ffd4e5;">
              @switch(strtolower($device->type->jenis ?? ''))
                @case('pc') <i class="bi bi-pc-display fs-2"></i> @break
                @case('laptop') <i class="bi bi-laptop fs-2"></i> @break
                @case('printer') <i class="bi bi-printer fs-2"></i> @break
                @case('ups') <i class="bi bi-lightning-charge fs-2"></i> @break
                @default <i class="bi bi-cpu fs-2"></i>
              @endswitch
            </div>
            <h5 class="fw-bold text-dark mt-2 mb-1">{{ $device->brand }}</h5>
            <span class="text-muted small">{{ $device->series }}</span>
          </div>

          <table class="table small mb-0">
            <tbody>
              <tr>
                <th style="width: 130px;">Kode BMN</th>
                <td class="fw-bold text-dark">{{ $device->id }}</td>
              </tr>
              <tr>
                <th>Kategori</th>
                <td>{{ $device->type->jenis ?? 'Lainnya' }}</td>
              </tr>
              <tr>
                <th>Nomor Seri</th>
                <td>{{ $device->serial_number ?: '-' }}</td>
              </tr>
              <tr>
                <th>Tahun</th>
                <td>{{ $device->year ?: '-' }}</td>
              </tr>
              <tr>
                <th>Kondisi</th>
                <td>
                  <span class="badge bg-{{ $device->id_condition == 1 ? 'success' : ($device->id_condition == 2 ? 'warning text-dark' : 'danger') }} px-2 py-1">
                    {{ $device->condition->kondisi ?? 'Tidak Diketahui' }}
                  </span>
                </td>
              </tr>
              <tr>
                <th>Asal Pengadaan</th>
                <td>{{ $device->source->asal ?? 'Tidak Diketahui' }}</td>
              </tr>
              <tr>
                <th>Status BMN</th>
                <td>{{ $device->statusBmn->status ?? 'Tidak Diketahui' }}</td>
              </tr>
              <tr>
                <th>Keterangan</th>
                <td>{{ $device->keterangan ?: '-' }}</td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>

    <!-- History Column -->
    <div class="col-lg-7 col-md-12">
      <div class="card shadow-sm border-0 mb-4 h-100">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 15px 20px !important; margin: -24px -24px 20px -24px !important; border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white"><i class="bi bi-journal-text me-1"></i> Riwayat Perbaikan</h5>
          <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light" style="color: #FF84BA !important; border: none !important; font-weight: 600;">Kembali</a>
        </div>
        <div class="card-body p-0 pt-3">

          @if($reports->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light border-bottom">
                  <tr>
                    <th class="ps-3">ID Tiket</th>
                    <th>Status</th>
                    <th class="text-center pe-3">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($reports as $report)
                    @php
                      $statusConf = match($report->status) {
                        'menunggu' => ['color' => 'warning text-dark', 'icon' => 'bi-clock', 'label' => 'Menunggu'],
                        'diproses' => ['color' => 'primary',           'icon' => 'bi-wrench-adjustable', 'label' => 'Diproses'],
                        'selesai'  => ['color' => 'success',           'icon' => 'bi-check-circle', 'label' => 'Selesai'],
                        'ditolak'  => ['color' => 'danger',            'icon' => 'bi-x-circle', 'label' => 'Ditolak'],
                        default    => ['color' => 'secondary',         'icon' => 'bi-circle', 'label' => ucfirst($report->status)],
                      };
                    @endphp
                    <tr>
                      <td class="ps-3">
                        <span class="text-primary fw-bold" style="font-size: 0.8rem;">{{ $report->ticket_id }}</span>
                        <small class="text-muted d-block" style="font-size: 0.7rem;"><i class="bi bi-calendar3 me-1"></i>{{ $report->created_at->format('d M Y, H:i') }}</small>
                      </td>

                      {{-- Status --}}
                      <td>
                        
                        <span class="badge bg-{{ $statusConf['color'] }} py-1 px-2">
                          {{ $statusConf['label'] }}
                        </span>
                      </td>

                      {{-- View detail button --}}
                      <td class="text-center pe-3">
                        <button type="button"
                          class="btn btn-sm btn-primary py-1 px-2 fw-semibold"
                          title="Lihat Detail"
                          data-bs-toggle="modal"
                          data-bs-target="#dashReportDetailModal{{ $report->ticket_id }}">
                          <i class="bi bi-eye-fill"></i><span class="d-none d-sm-inline ms-1">Detail</span>
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-5 text-muted">
              <i class="bi bi-clipboard-x fs-1 d-block mb-2 opacity-50"></i>
              <span class="small">Belum ada riwayat pelaporan kerusakan pada perangkat ini.</span>
            </div>
          @endif

        </div>
      </div>
    </div>

  </div>
</section>

{{-- ── Detail Modals for Each Report ── --}}
@foreach($reports as $report)
  @php
    $statusConf = match($report->status) {
      'menunggu' => ['color' => 'warning text-dark', 'icon' => 'bi-clock',            'label' => 'Menunggu Konfirmasi'],
      'diproses' => ['color' => 'primary',           'icon' => 'bi-wrench-adjustable','label' => 'Sedang Diproses'],
      'selesai'  => ['color' => 'success',           'icon' => 'bi-check-circle',     'label' => 'Perbaikan Selesai'],
      'ditolak'  => ['color' => 'danger',            'icon' => 'bi-x-circle',         'label' => 'Laporan Ditolak'],
      default    => ['color' => 'secondary',         'icon' => 'bi-circle',           'label' => ucfirst($report->status)],
    };
  @endphp

  <div class="modal fade" id="dashReportDetailModal{{ $report->ticket_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content" style="border-radius: 16px; overflow: hidden;">

        {{-- Modal Header --}}
        <div class="modal-header text-white" style="background: linear-gradient(135deg, #FF84BA, #99C2FF); border-bottom: none;">
          <div>
            <h5 class="modal-title fw-bold mb-0">
              <i class="bi bi-ticket-detailed-fill me-2"></i>Detail Tiket {{ $report->ticket_id }}
            </h5>
            <small class="opacity-75" style="font-size: 0.78rem;">{{ $report->created_at->format('d M Y, H:i') }}</small>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        {{-- Modal Body --}}
        <div class="modal-body p-4">

          {{-- Badges --}}
          <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge bg-{{ $statusConf['color'] }} py-2 px-3 fw-bold">
              <i class="bi {{ $statusConf['icon'] }} me-1"></i>{{ $statusConf['label'] }}
            </span>
            <span class="badge badge-{{ $report->issue_type }} py-2 px-3 fw-bold">
              {{ ucfirst($report->issue_type) }}
            </span>
          </div>

          {{-- Problem Description --}}
          <div class="mb-3 p-3 rounded-3 border-start border-4 border-danger-subtle small"
               style="background: #fffdfd; border-color: #FF84BA !important; border-top: 1px solid #fdf5f9; border-right: 1px solid #fdf5f9; border-bottom: 1px solid #fdf5f9;">
            <strong class="d-block mb-1 text-dark" style="font-size: 0.8rem;">
              <i class="bi bi-chat-left-text-fill me-1" style="color: #FF84BA;"></i> Kendala yang Dilaporkan:
            </strong>
            <p class="mb-0 text-dark" style="white-space: pre-line; line-height: 1.5;">{{ $report->description }}</p>
          </div>

          {{-- Technician Section --}}
          @if($report->status !== 'menunggu')
            <hr class="my-3" style="border-color: #fdf5f9;">
            <div class="row g-3">

              @if($report->handled_by)
                <div class="col-sm-6">
                  <div class="small text-muted mb-1"><i class="bi bi-person-workspace me-1" style="color: #FF84BA;"></i> Teknisi:</div>
                  <div class="fw-bold text-dark small">{{ $report->technician->name ?? 'N/A' }}</div>
                </div>
              @endif

              @if($report->id_vendor)
                <div class="col-sm-6">
                  <div class="small text-muted mb-1"><i class="bi bi-building me-1" style="color: #99C2FF;"></i> Vendor Rujukan</div>
                  <div class="fw-bold text-dark small">{{ $report->vendor->vendor_service ?? 'N/A' }}</div>
                </div>
              @endif

              <div class="col-12">
                <div class="small text-muted mb-1"><i class="bi bi-journal-text me-1" style="color: #FF84BA;"></i> Catatan:</div>
                <div class="p-2 rounded-2 bg-light border small text-dark" style="white-space: pre-line; min-height: 48px; line-height: 1.5;">
                  {{ $report->technician_notes ?: 'Belum ada catatan solusi.' }}
                </div>
              </div>

              @if($report->resolved_at)
                <div class="col-12 text-end">
                  <small class="text-muted"><i class="bi bi-check2-all me-1 text-success"></i>
                    Diselesaikan: <strong>{{ $report->resolved_at->format('d M Y, H:i') }}</strong>
                  </small>
                </div>
              @endif

            </div>
          @else
            <div class="alert alert-info py-2 px-3 mb-0 small border-0" style="background: rgba(153,194,255,0.12); border-radius: 10px;">
              <i class="bi bi-info-circle me-1 text-primary"></i>
              Laporan ini sedang dalam antrian penugasan teknisi.
            </div>
          @endif

        </div>

        {{-- Modal Footer --}}
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Tutup</button>
        </div>

      </div>
    </div>
  </div>
@endforeach
@endsection
