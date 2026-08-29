@extends('layouts.app')

@title('Status Perbaikan Perangkat | MLTI-Report')

@section('content')
<div class="pagetitle d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
  <div>
    <h1 class="fw-bold" style="color: var(--color-primary);">Status Perbaikan Perangkat</h1>
    <nav>
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
        <li class="breadcrumb-item active">Riwayat Perbaikan</li>
      </ol>
    </nav>
  </div>
  <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 py-1 fw-semibold">
    <i class="bi bi-arrow-left me-1"></i> Kembali
  </a>
</div>

<section class="section">
  <div class="row justify-content-center">
    <div class="col-12">

      {{-- ── Device Identity Card ── --}}
      <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; border-left: 5px solid #FF84BA !important;">
        <div class="card-body py-3 px-4">
          <div class="d-flex align-items-center gap-3">
            <div class="device-icon-wrapper p-2 bg-light rounded-3 text-secondary fs-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; min-width: 50px;">
              @php $t = strtolower($device->type->jenis ?? ''); @endphp
              @if(str_contains($t,'pc'))                <i class="bi bi-pc-display text-primary"></i>
              @elseif(str_contains($t,'laptop'))         <i class="bi bi-laptop text-primary"></i>
              @elseif(str_contains($t,'printer'))        <i class="bi bi-printer text-success"></i>
              @elseif(str_contains($t,'ups'))            <i class="bi bi-lightning-charge-fill text-warning"></i>
              @elseif(str_contains($t,'scanner'))        <i class="bi bi-camera text-info"></i>
              @elseif(str_contains($t,'tablet'))         <i class="bi bi-tablet text-primary"></i>
              @elseif(str_contains($t,'smartphone') || str_contains($t,'phone')) <i class="bi bi-phone text-info"></i>
              @elseif(str_contains($t,'viewer') || str_contains($t,'proyektor')) <i class="bi bi-projector text-secondary"></i>
              @else                                      <i class="bi bi-cpu-fill text-secondary"></i>
              @endif
            </div>
            <div class="flex-grow-1">
              <div class="fw-bold text-dark" style="font-size: 1rem;">{{ $device->brand }} - {{ $device->series }}</div>
              <small class="text-muted d-block" style="font-size: 0.78rem;">
                BMN: <strong>{{ $device->id }}</strong>
                @if($device->serial_number) | S/N: {{ $device->serial_number }} @endif
                @if($device->year) | Tahun: {{ $device->year }} @endif
              </small>
            </div>
            <span class="badge bg-light text-secondary border d-none d-sm-inline">{{ $device->type->jenis ?? 'Lainnya' }}</span>
          </div>
        </div>
      </div>

      {{-- ── History Table Card ── --}}
      <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #FF84BA, #99C2FF) !important; padding: 14px 20px !important; border-bottom: none !important;">
          <h5 class="card-title my-0 text-white fw-bold">
            <i class="bi bi-clock-history me-2"></i> Riwayat Perbaikan
          </h5>
          <span class="badge bg-white text-dark fw-bold">{{ $reports->count() }} Tiket</span>
        </div>

        <div class="card-body p-0">
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
                          data-bs-target="#reportDetailModal{{ $report->ticket_id }}">
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
              <p class="mb-0 fw-semibold small">Belum ada riwayat laporan kerusakan untuk perangkat ini.</p>
            </div>
          @endif
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ── Detail Modals (one per report) ── --}}
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

  <div class="modal fade" id="reportDetailModal{{ $report->ticket_id }}" tabindex="-1" aria-hidden="true">
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

          {{-- Status + type badges --}}
          <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge bg-{{ $statusConf['color'] }} py-2 px-3 fw-bold">
              <i class="bi {{ $statusConf['icon'] }} me-1"></i>{{ $statusConf['label'] }}
            </span>
            <span class="badge badge-{{ $report->issue_type }} py-2 px-3 fw-bold">
              {{ ucfirst($report->issue_type) }}
            </span>
          </div>

          {{-- Problem description --}}
          <div class="mb-3 p-3 rounded-3 border-start border-4 border-danger-subtle small"
               style="background: #fffdfd; border-color: #FF84BA !important; border-top: 1px solid #fdf5f9; border-right: 1px solid #fdf5f9; border-bottom: 1px solid #fdf5f9;">
            <strong class="d-block mb-1 text-dark" style="font-size: 0.8rem;">
              <i class="bi bi-chat-left-text-fill me-1" style="color: #FF84BA;"></i> Kendala yang Dilaporkan:
            </strong>
            <p class="mb-0 text-dark" style="white-space: pre-line; line-height: 1.5;">{{ $report->description }}</p>
          </div>

          {{-- Technician section (only if not 'menunggu') --}}
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
                  <div class="small text-muted mb-1"><i class="bi bi-building me-1" style="color: #99C2FF;"></i> Dirujuk ke Vendor</div>
                  <div class="fw-bold text-dark small">{{ $report->vendor->vendor_service ?? 'N/A' }}</div>
                </div>
              @endif

              <div class="col-12">
                <div class="small text-muted mb-1"><i class="bi bi-journal-text me-1" style="color: #FF84BA;"></i> Catatan:</div>
                <div class="p-2 rounded-2 bg-light border small text-dark" style="white-space: pre-line; min-height: 48px; line-height: 1.5;">
                  {{ $report->technician_notes ?: 'Belum ada catatan dari tim teknis.' }}
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
