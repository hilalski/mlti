@extends('layouts.app')

@section('title', 'Dashboard Perangkat TI | MLTI-Report')

@section('content')
<div class="pagetitle d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
  <div>
    <h1 class="fw-bold" style="color: var(--color-primary);">Perangkat TI Saya</h1>
    <nav>
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
        <li class="breadcrumb-item active">Perangkat Saya</li>
      </ol>
    </nav>
  </div>
  <div class="d-flex gap-2">
    <button type="button" class="btn btn-outline-primary btn-sm px-3 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#quickScanModal">
      <i class="bi bi-qr-code-scan me-1"></i> Pindai BMN
    </button>
    <button type="button" class="btn btn-primary btn-sm px-3 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#openTicketModal">
      <i class="bi bi-ticket-detailed-fill me-1"></i> Open Ticket
    </button>
  </div>
</div><!-- End Page Title -->

<section class="section dashboard">
  <div class="row">

    <!-- Welcoming banner -->
    <div class="col-12 mb-4">
      <div class="card border-0 shadow-sm overflow-hidden position-relative" style="background: linear-gradient(135deg, var(--color-primary) 0%, #1e293b 100%);">
        <!-- Accent indicator line -->
        <div style="position: absolute; top: 0; left: 0; width: 6px; height: 100%; background-color: var(--color-accent);"></div>
        
        <div class="card-body p-4 ps-5 text-white">
          <div class="row align-items-center">
            <div class="col-md-9">
              <h4 class="fw-bold mb-2 text-white" style="font-size: 1.6rem; letter-spacing: 0.5px;">Selamat Datang Kembali, {{ auth()->user()->name }}!</h4>
              <p class="mb-0 text-white-50 small" style="line-height: 1.6; font-size: 0.95rem;">
                Kelola dan pantau seluruh perangkat TI Anda dengan mudah dalam satu tempat.
              </p>
            </div>
            <div class="col-md-3 d-none d-md-flex justify-content-end align-items-center">
              <div class="p-3 bg-white bg-opacity-10 rounded-circle text-white fs-1">
                <i class="bi bi-shield-lock-fill text-warning"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Devices Grid -->
    @foreach($devices as $device)
      <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card h-100 card-device position-relative">
          
          <!-- Delete button in the corner -->
          <form action="{{ route('dashboard.devices.unassign') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin melepaskan perangkat ini?');" style="position: absolute; top: 15px; right: 15px; z-index: 10;">
            @csrf
            <input type="hidden" name="device_id" value="{{ $device->id }}">
            <button type="submit" class="btn btn-link text-danger p-0 border-0" title="Lepaskan Perangkat">
              <i class="bi bi-trash-fill fs-5"></i>
            </button>
          </form>

          <div class="card-body pt-4 d-flex flex-column justify-content-between">
              <div class="d-flex align-items-start mb-1">
                <div class="device-icon-wrapper p-3 bg-light rounded-3 me-3 text-secondary fs-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 58px; height: 58px;">
                  @switch(strtolower($device->type->jenis ?? ''))
                    @case('pc')
                      <i class="bi bi-pc-display text-primary"></i>
                      @break
                    @case('laptop')
                      <i class="bi bi-laptop text-primary"></i>
                      @break
                    @case('printer')
                      <i class="bi bi-printer text-success"></i>
                      @break
                    @case('ups')
                      <i class="bi bi-lightning-charge-fill text-warning"></i>
                      @break
                    @case('scanner')
                      <i class="bi bi-camera text-info"></i>
                      @break
                    @case('tablet')
                      <i class="bi bi-tablet text-primary"></i>
                      @break
                    @case('smartphone')
                      <i class="bi bi-phone text-info"></i>
                      @break
                    @case('viewer')
                      <i class="bi bi-projector text-secondary"></i>
                      @break
                    @default
                      <i class="bi bi-cpu-fill text-secondary"></i>
                  @endswitch
                </div>
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center justify-content-between">
                    <span class="badge bg-light border border-primary-subtle small fw-bold px-2 py-1 mb-1" style="color: #000 !important;">{{ $device->type->jenis ?? 'Lainnya' }}</span>
                  </div>
                  <h6 class="mb-0 text-dark fw-bold" style="font-size: 1.05rem; padding-right: 25px;">{{ $device->brand }} | <span class="text-muted small" style="font-size: 0.8rem;">{{ $device->series }}</span></h6>
                  
                </div>
              </div>

              <!-- Device Specifications Table -->
              <div class="bg-light p-3 rounded-3 mb-3 border">
                <table class="table table-sm table-borderless small mb-0">
                  <tbody>
                    <tr class="pb-1">
                      <td class="text-muted p-0" style="width: 90px;">Kode BMN</td>
                      <td class="p-0 text-dark fw-semibold">: {{ $device->id }}</td>
                    </tr>
                    <tr class="pb-1">
                      <td class="text-muted p-0">No Seri</td>
                      <td class="p-0 text-dark">: {{ $device->serial_number ?: '-' }}</td>
                    </tr>
                    <tr class="pb-1">
                      <td class="text-muted p-0">Tahun</td>
                      <td class="p-0 text-dark">: {{ $device->year ?: '-' }}</td>
                    </tr>
                    <tr>
                      <td class="text-muted p-0">Kondisi</td>
                      <td class="p-0">
                        : <span class="badge bg-{{ $device->id_condition == 1 ? 'success' : ($device->id_condition == 2 ? 'warning text-dark' : 'danger') }} px-2 py-1 small">
                          {{ $device->condition->kondisi ?? 'Tidak Diketahui' }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

            <!-- Card footer actions -->
            <div class="pt-3 border-top d-flex flex-column gap-2">
              <a href="{{ route('dashboard.devices.show', $device->id) }}" class="btn btn-light btn-sm w-100 py-2 fw-semibold text-dark border">
                <i class="bi bi-info-circle me-1"></i> Detail & Riwayat Perbaikan
              </a>

              <!-- Swap/Change Device Button -->
              <button type="button" class="btn btn-outline-secondary btn-sm w-100 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#swapModal-{{ $device->id }}">
                <i class="bi bi-arrow-left-right me-1"></i> Ganti Perangkat
              </button>

              @php
                $activeReport = $device->activeReport();
              @endphp

              @if($activeReport)
                <div class="d-flex align-items-center justify-content-between pt-1">
                  <span class="badge bg-warning-subtle text-warning border border-warning-subtle py-2 px-3 fw-bold rounded-pill">
                    <span class="spinner-grow spinner-grow-sm me-1 text-warning" role="status" style="width: 10px; height: 10px;"></span>
                    {{ ucfirst($activeReport->status) }}
                  </span>
                  <a href="{{ route('report.status', $device->id) }}" class="btn btn-primary btn-sm px-3 py-1.5 fw-semibold shadow-sm">
                    <i class="bi bi-eye-fill me-1"></i> Monitor
                  </a>
                </div>
              @else
                <a href="{{ route('report.create', $device->id) }}" class="btn btn-outline-danger btn-sm w-100 py-2 fw-semibold">
                  <i class="bi bi-exclamation-triangle-fill me-1"></i> Laporkan Kendala
                </a>
              @endif
            </div>

          </div>
        </div>
      </div>

      <!-- Swap Modal for this device -->
      <div class="modal fade" id="swapModal-{{ $device->id }}" tabindex="-1" aria-labelledby="swapModalLabel-{{ $device->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title fw-bold" id="swapModalLabel-{{ $device->id }}"><i class="bi bi-arrow-left-right me-1"></i> Ganti Perangkat: {{ $device->brand }}</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="alert alert-warning small border-0 shadow-sm mb-3">
                <i class="bi bi-exclamation-triangle me-1"></i> 
                Mengganti perangkat berarti melepaskan {{ $device->brand }} - {{ $device->series }} (BMN: {{ $device->id }}), dan menggantinya dengan perangkat yang dipilih.
              </div>

              <h6 class="fw-bold mb-2">Pilih Perangkat Pengganti (Tipe: {{ $device->type->jenis ?? 'Lainnya' }})</h6>
              
              <div class="mb-3 position-relative">
                <!-- <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i> -->
                <input type="text" class="form-control ps-5 swap-search-input" placeholder="Cari perangkat berdasarkan nama, merek, atau BMN..." data-device-id="{{ $device->id }}">
              </div>
              
              <div class="list-group shadow-sm" style="max-height: 350px; overflow-y: auto;">
                @php
                  $availableOfType = $availableDevices->where('id_type', $device->id_type);
                @endphp

                @forelse($availableOfType as $avail)
                  <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 swap-item" data-search-text="{{ strtolower($avail->brand) }} {{ strtolower($avail->series) }} {{ strtolower($avail->id) }} {{ $avail->user ? strtolower($avail->user->name) : ($avail->room ? strtolower($avail->room->ruang) : 'gudang') }}">
                    <div>
                      <div class="fw-bold text-dark">{{ $avail->brand }} - {{ $avail->series }}</div>
                      <small class="text-muted">Kode BMN: {{ $avail->id }}
                        <span class="badge bg-{{ $avail->id_condition == 1 ? 'success' : ($avail->id_condition == 2 ? 'warning text-dark' : 'danger') }} py-1 small">
                          Kondisi: {{ $avail->condition->kondisi ?? 'N/A' }}
                        </span>
                      </small>
                      <div class="mt-1 small">
                        <span class="text-muted" style="font-size: 0.8rem;">Pemilik saat ini:</span>
                        @if($avail->user)
                          <span class="fw-semibold" style="font-size: 0.8rem; color: #FF84BA;"><i class="bi bi-person-fill"></i> {{ $avail->user->name }}</span>
                        @elseif($avail->room)
                          <span class="fw-semibold" style="font-size: 0.8rem; color: #99C2FF;"><i class="bi bi-house-door-fill"></i> {{ $avail->room->ruang }}</span>
                        @else
                          <span class="fw-semibold" style="font-size: 0.8rem;"><i class="bi bi-box-seam-fill"></i> Gudang</span>
                        @endif
                      </div>
                    </div>
                    <form action="{{ route('dashboard.devices.swap') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengganti ke perangkat ini?');">
                      @csrf
                      <input type="hidden" name="old_device_id" value="{{ $device->id }}">
                      <input type="hidden" name="new_device_id" value="{{ $avail->id }}">
                      <button type="submit" class="btn btn-sm btn-primary text-white fw-bold">
                        Pilih
                      </button>
                    </form>
                  </div>
                @empty
                  <div class="list-group-item text-center py-4 text-muted">
                    Tidak ada perangkat lain dengan kategori ini untuk saat ini.
                  </div>
                @endforelse
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
          </div>
        </div>
      </div>
    @endforeach

    <!-- Add Device Card (Always visible at the end of the grid) -->
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
      <div class="card h-100 card-device border-dashed d-flex align-items-center justify-content-center bg-light" style="border: 2px dashed var(--color-secondary) !important; min-height: 250px; cursor: pointer; transition: all 0.3s;" data-bs-toggle="modal" data-bs-target="#addDeviceModal" onmouseover="this.style.borderColor='var(--color-primary)'" onmouseout="this.style.borderColor='var(--color-secondary)'">
        <div class="card-body text-center d-flex flex-column align-items-center justify-content-center py-5">
          <i class="bi bi-plus-circle-fill text-primary fs-1 mb-2"></i>
          <h5 class="fw-bold text-dark mb-1">Tambah Perangkat</h5>
          <p class="text-muted small mb-0">Kuasai perangkat baru (PC, Laptop, UPS, dsb)</p>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- Room Devices Title -->
<div class="pagetitle d-flex justify-content-between align-items-center mb-3 mt-4">
  <div>
    <h1 class="fw-bold" style="color: var(--color-primary);"><i class="bi bi-house-door-fill me-1"></i> Perangkat {{ auth()->user()->room->ruang ?? 'Tidak Diketahui' }}</h1>
  </div>
</div>

<section class="section dashboard">
  <div class="card border-0 shadow-sm" style="border-radius: 18px !important; overflow: hidden !important; padding: 0 !important;">

    @if($roomDevices->isEmpty())
      <div class="p-5 text-center text-muted">
        <i class="bi bi-inbox fs-1 mb-3 d-block" style="color: var(--color-secondary);"></i>
        <p class="mb-0 small fw-semibold">Tidak ada perangkat lain di ruangan ini.</p>
      </div>
    @else
      @php
        /* Group room devices by type name */
        $roomByType = $roomDevices->groupBy(fn($d) => $d->type->jenis ?? 'Lainnya');
        $roomTypeKeys = $roomByType->keys();
      @endphp

      {{-- ── Tab Nav (Responsive Horizontal Scroll on Mobile) ── --}}
      <div class="px-3 px-md-4 pt-3 pt-md-4 pb-0" style="border-bottom: 1px solid var(--border-color);">
        <ul class="nav nav-tabs border-0 gap-1 tab-scroll-mobile" id="roomTypeTabs" role="tablist">
          @foreach($roomTypeKeys as $idx => $typeName)
            @php
              $tabId = 'roomtab-' . Str::slug($typeName);
              $count = $roomByType[$typeName]->count();
            @endphp
            <li class="nav-item" role="presentation">
              <button
                class="nav-link fw-semibold px-3 px-md-4 py-2 text-nowrap {{ $idx === 0 ? 'active' : '' }}"
                id="{{ $tabId }}-btn"
                data-bs-toggle="tab"
                data-bs-target="#{{ $tabId }}-pane"
                type="button" role="tab"
                style="border-radius: 10px 10px 0 0 !important; font-size: 0.85rem; border: 1px solid transparent; border-bottom: none; transition: all .2s;"
              >
                @switch(strtolower($typeName))
                  @case('pc') <i class="bi bi-pc-display me-1"></i> @break
                  @case('laptop') <i class="bi bi-laptop me-1"></i> @break
                  @case('printer') <i class="bi bi-printer me-1"></i> @break
                  @case('ups') <i class="bi bi-lightning-charge-fill me-1"></i> @break
                  @case('scanner') <i class="bi bi-camera me-1"></i> @break
                  @case('tablet') <i class="bi bi-tablet me-1"></i> @break
                  @case('smartphone') <i class="bi bi-phone me-1"></i> @break
                  @case('viewer') <i class="bi bi-projector me-1"></i> @break
                  @default <i class="bi bi-cpu-fill me-1"></i>
                @endswitch
                {{ $typeName }}
                <span class="badge ms-1 rounded-pill" style="font-size: 0.7rem; background: linear-gradient(135deg, #FF84BA, #99C2FF); color: #fff;">{{ $count }}</span>
              </button>
            </li>
          @endforeach
        </ul>
      </div>

      {{-- ── Tab Content ── --}}
      <div class="tab-content p-4" id="roomTypeTabsContent">
        @foreach($roomTypeKeys as $idx => $typeName)
          @php
            $tabId = 'roomtab-' . Str::slug($typeName);
            $roomTypeDevices = $roomByType[$typeName];
            $roomTypeId = $roomTypeDevices->first()->id_type;
          @endphp
          <div
            class="tab-pane fade {{ $idx === 0 ? 'show active' : '' }}"
            id="{{ $tabId }}-pane"
            role="tabpanel"
            aria-labelledby="{{ $tabId }}-btn"
          >
            <div class="row">
              @foreach($roomTypeDevices as $device)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                  <div class="card h-100 card-device position-relative" style="border-radius: 16px !important;">

                    {{-- ── Trash / Pindah ke Gudang button ── --}}
                    <button
                      type="button"
                      class="btn btn-link p-0 border-0 position-absolute"
                      style="top: 14px; right: 14px; z-index: 10; line-height: 1;"
                      title="Pindah ke Gudang"
                      onclick="confirmMoveGudang('{{ $device->id }}', '{{ addslashes($device->brand . ' ' . $device->series) }}')"
                    >
                      <i class="bi bi-trash3-fill fs-5" style="color: #e11d48;"></i>
                    </button>

                    <div class="card-body pt-4 d-flex flex-column justify-content-between">
                      <div>
                        <div class="d-flex align-items-start mb-3">
                          <div class="device-icon-wrapper p-3 bg-light rounded-3 me-3 text-secondary fs-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 58px; height: 58px;">
                            @switch(strtolower($device->type->jenis ?? ''))
                              @case('pc')         <i class="bi bi-pc-display text-primary"></i>   @break
                              @case('laptop')     <i class="bi bi-laptop text-primary"></i>      @break
                              @case('printer')    <i class="bi bi-printer text-success"></i>   @break
                              @case('ups')        <i class="bi bi-lightning-charge-fill text-warning"></i> @break
                              @case('scanner')    <i class="bi bi-camera text-info"></i>       @break
                              @case('tablet')     <i class="bi bi-tablet text-primary"></i>      @break
                              @case('smartphone') <i class="bi bi-phone text-info"></i>     @break
                              @case('viewer')     <i class="bi bi-projector text-secondary"></i> @break
                              @default           <i class="bi bi-cpu-fill text-secondary"></i>
                            @endswitch
                          </div>
                          <div class="flex-grow-1">
                            <span class="badge bg-light border border-primary-subtle small fw-bold px-2 py-1 mb-1" style="color: #000 !important;">{{ $device->type->jenis ?? 'Lainnya' }}</span>
                            <h6 class="mb-0 text-dark fw-bold" style="font-size: 1.05rem; padding-right: 28px;">
                              {{ $device->brand }} | <span class="text-muted small" style="font-size: 0.8rem;">{{ $device->series }}</span>
                            </h6>
                          </div>
                        </div>

                        {{-- Specs --}}
                        <div class="bg-light p-3 rounded-3 mb-3 border">
                          <table class="table table-sm table-borderless small mb-0">
                            <tbody>
                              <tr><td class="text-muted p-0" style="width:90px;">Kode BMN</td><td class="p-0 text-dark fw-semibold">: {{ $device->id }}</td></tr>
                              <tr><td class="text-muted p-0">No Seri</td><td class="p-0 text-dark">: {{ $device->serial_number ?: '-' }}</td></tr>
                              <tr><td class="text-muted p-0">Tahun</td><td class="p-0 text-dark">: {{ $device->year ?: '-' }}</td></tr>
                              <tr>
                                <td class="text-muted p-0">Kondisi</td>
                                <td class="p-0">
                                  : <span class="badge bg-{{ $device->id_condition == 1 ? 'success' : ($device->id_condition == 2 ? 'warning text-dark' : 'danger') }} px-2 py-1 small">
                                      {{ $device->condition->kondisi ?? 'Tidak Diketahui' }}
                                    </span>
                                </td>
                              </tr>
                              <tr><td class="text-muted p-0">Keterangan</td><td class="p-0 text-dark">: {{ $device->keterangan ?: '-' }}</td></tr>
                            </tbody>
                          </table>
                        </div>
                      </div>

                      {{-- ── Footer actions ── --}}
                      <div class="pt-3 border-top d-flex flex-column gap-2">

                        {{-- 1. Detail --}}
                        <a href="{{ route('dashboard.devices.show', $device->id) }}" class="btn btn-light btn-sm w-100 py-2 fw-semibold text-dark border">
                          <i class="bi bi-info-circle me-1"></i> Detail &amp; Riwayat Perbaikan
                        </a>

                        {{-- 2. Kuasai (take personal ownership from room) --}}
                        <form action="{{ route('dashboard.devices.assign-from-room') }}" method="POST"
                              onsubmit="return confirm('Kuasai perangkat ini secara pribadi? Perangkat akan dipindah dari daftar ruangan ke daftar perangkat Anda.');">
                          @csrf
                          <input type="hidden" name="device_id" value="{{ $device->id }}">
                          <button type="submit" class="btn btn-sm w-100 py-2 fw-semibold"
                                  style="background: linear-gradient(135deg,rgba(255,132,186,.12),rgba(153,194,255,.12)); border: 1px solid rgba(255,132,186,.35); color: #000;">
                            <i class="bi bi-person-check-fill me-1"></i> Kuasai Perangkat Ini
                          </button>
                        </form>

                        {{-- 3. Lapor Kerusakan --}}
                        @php $activeReport = $device->activeReport(); @endphp
                        @if($activeReport)
                          <div class="d-flex align-items-center justify-content-between pt-1">
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle py-2 px-3 fw-bold rounded-pill">
                              <span class="spinner-grow spinner-grow-sm me-1 text-warning" role="status" style="width:10px;height:10px;"></span>
                              {{ ucfirst($activeReport->status) }}
                            </span>
                            <a href="{{ route('report.status', $device->id) }}" class="btn btn-primary btn-sm px-3 fw-semibold shadow-sm">
                              <i class="bi bi-eye-fill me-1"></i> Monitor
                            </a>
                          </div>
                        @else
                          <a href="{{ route('report.create', $device->id) }}" class="btn btn-outline-danger btn-sm w-100 py-2 fw-semibold">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Laporkan Kendala
                          </a>
                        @endif

                      </div>{{-- /footer --}}
                    </div>
                  </div>
                </div>
              @endforeach

              <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <button type="button" class="card h-100 w-100 card-device border-dashed d-flex align-items-center justify-content-center bg-light text-decoration-none"
                        style="border: 2px dashed var(--color-secondary) !important; min-height: 250px; cursor: pointer; transition: all 0.3s;"
                        data-bs-toggle="modal" data-bs-target="#addRoomDeviceModal">
                  <span class="card-body text-center d-flex flex-column align-items-center justify-content-center py-5">
                    <i class="bi bi-plus-circle-fill text-primary fs-1 mb-2"></i>
                    <span class="h5 fw-bold text-dark mb-1">Tambah Perangkat</span>
                    <span class="text-muted small">Tambahkan perangkat ke ruangan ini</span>
                  </span>
                </button>
              </div>
            </div>{{-- /row --}}
          </div>{{-- /tab-pane --}}
        @endforeach
      </div>{{-- /tab-content --}}
    @endif

  </div>{{-- /card --}}
</section>

{{-- Hidden forms for move-to-gudang (triggered by JS confirm) --}}
@foreach($roomDevices as $device)
  <form id="gudang-form-{{ $device->id }}" action="{{ route('dashboard.devices.move-to-gudang') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="device_id" value="{{ $device->id }}">
  </form>
@endforeach


{{-- Add device modals: one per room-device type tab --}}
@if(false) {{-- Replaced by the unified type-tab modal below. --}}
  @foreach($roomTypeKeys as $typeName)
    @php
      $roomTypeDevices = $roomByType[$typeName];
      $roomTypeId = $roomTypeDevices->first()->id_type;
      $roomTypeAvailable = $availableDevices
        ->where('id_type', $roomTypeId)
        ->reject(fn($device) => (string) $device->id_user === (string) auth()->user()->id_ruang);
    @endphp
    <div class="modal fade" id="addRoomDeviceModal-{{ $roomTypeId }}" tabindex="-1" aria-labelledby="addRoomDeviceModalLabel-{{ $roomTypeId }}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title fw-bold" id="addRoomDeviceModalLabel-{{ $roomTypeId }}"><i class="bi bi-plus-circle-fill me-1"></i> Tambah Perangkat {{ $typeName }} ke Ruangan</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <p class="text-muted small">Pilih perangkat {{ $typeName }} yang akan ditambahkan ke ruangan {{ auth()->user()->room->ruang ?? '' }}.</p>
            <div class="mb-3">
              <input type="search" class="form-control room-add-device-search" placeholder="Cari nama, merek, BMN, atau pemilik perangkat..." autocomplete="off">
            </div>
            <div class="list-group shadow-sm room-add-device-list" style="max-height: 350px; overflow-y: auto;">
              @forelse($roomTypeAvailable as $avail)
                <div class="list-group-item d-flex justify-content-between align-items-center py-3 gap-3 room-add-device-item"
                     data-search-text="{{ strtolower($avail->brand . ' ' . $avail->series . ' ' . $avail->id . ' ' . ($avail->user?->name ?? '') . ' ' . ($avail->room?->ruang ?? '') . ' gudang') }}">
                  <div>
                    <div class="fw-bold text-dark">{{ $avail->brand }} - {{ $avail->series }}</div>
                    <small class="text-muted d-block">Kode BMN: {{ $avail->id }} | S/N: {{ $avail->serial_number ?: '-' }}</small>
                    <span class="badge bg-{{ $avail->id_condition == 1 ? 'success' : ($avail->id_condition == 2 ? 'warning text-dark' : 'danger') }} py-1 small mt-1">Kondisi: {{ $avail->condition->kondisi ?? 'N/A' }}</span>
                    <div class="mt-2 small">
                      <span class="text-muted" style="font-size: 0.8rem;">Pemilik saat ini:</span>
                      @if($avail->user)
                        <span class="fw-semibold" style="font-size: 0.8rem; color: #FF84BA;"><i class="bi bi-person-fill"></i> {{ $avail->user->name }}</span>
                      @elseif($avail->room)
                        <span class="fw-semibold" style="font-size: 0.8rem; color: #99C2FF;"><i class="bi bi-house-door-fill"></i> {{ $avail->room->ruang }}</span>
                      @else
                        <span class="fw-semibold" style="font-size: 0.8rem;"><i class="bi bi-box-seam-fill"></i> Gudang</span>
                      @endif
                    </div>
                  </div>
                  <form action="{{ route('dashboard.devices.assign-to-room') }}" method="POST" onsubmit="return confirm('Tambahkan perangkat ini ke ruangan?');">
                    @csrf
                    <input type="hidden" name="device_id" value="{{ $avail->id }}">
                    <button type="submit" class="btn btn-sm btn-primary text-white fw-bold text-nowrap"><i class="bi bi-plus-circle me-1"></i> Tambahkan</button>
                  </form>
                </div>
              @empty
                <div class="list-group-item text-center py-4 text-muted">Tidak ada perangkat {{ $typeName }} yang tersedia.</div>
              @endforelse
            </div>
          </div>
          <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
        </div>
      </div>
    </div>
  @endforeach
@endif

<!-- Global Add Device Modal -->
{{-- Unified Add Device to Room Modal --}}
@php
  $roomDeviceAvailable = $availableDevices
    ->reject(fn($device) => (string) $device->id_user === (string) auth()->user()->id_ruang);
@endphp
<div class="modal fade" id="addRoomDeviceModal" tabindex="-1" aria-labelledby="addRoomDeviceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="addRoomDeviceModalLabel"><i class="bi bi-plus-circle-fill me-1"></i> Tambah Perangkat ke Ruangan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <input type="search" id="roomAddDeviceSearchInput" class="form-control ps-3" placeholder="Cari perangkat berdasarkan nama, merek, BMN, atau pemilik..." autocomplete="off">
        </div>

        <ul class="nav nav-pills mb-3 justify-content-center gap-2" id="roomAddTypeTabs" role="tablist">
          @foreach($types as $idx => $type)
            <li class="nav-item" role="presentation">
              <button class="nav-link {{ $idx === 0 ? 'active' : '' }} fw-semibold" id="room-add-tab-btn-{{ $type->id }}" data-bs-toggle="tab" data-bs-target="#room-add-tab-content-{{ $type->id }}" type="button" role="tab">
                {{ $type->jenis }}
              </button>
            </li>
          @endforeach
        </ul>

        <div class="tab-content" id="roomAddTypeTabsContent">
          @foreach($types as $idx => $type)
            @php $typeAvails = $roomDeviceAvailable->where('id_type', $type->id); @endphp
            <div class="tab-pane fade {{ $idx === 0 ? 'show active' : '' }}" id="room-add-tab-content-{{ $type->id }}" role="tabpanel">
              <div class="list-group shadow-sm room-add-tab-list" style="max-height: 350px; overflow-y: auto;">
                @forelse($typeAvails as $avail)
                  @php
                    $ownerName = $avail->user?->name ?? $avail->room?->ruang ?? 'Gudang';
                    $ownerIcon = $avail->user ? 'bi-person-fill' : ($avail->room ? 'bi-house-door-fill' : 'bi-box-seam-fill');
                  @endphp
                  <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 gap-3 room-add-tab-item"
                       data-search-text="{{ strtolower($avail->brand . ' ' . $avail->series . ' ' . $avail->id . ' ' . $ownerName) }}">
                    <div>
                      <div class="fw-bold text-dark">{{ $avail->brand }} - {{ $avail->series }}</div>
                      <small class="text-muted d-block">Kode BMN: {{ $avail->id }} | S/N: {{ $avail->serial_number ?: '-' }}</small>
                      <span class="badge bg-{{ $avail->id_condition == 1 ? 'success' : ($avail->id_condition == 2 ? 'warning text-dark' : 'danger') }} py-1 small mt-1">Kondisi: {{ $avail->condition->kondisi ?? 'N/A' }}</span>
                      <div class="mt-1 small"><span class="text-muted">Pemilik saat ini:</span> <span class="fw-semibold" style="font-size: .8rem;"><i class="bi {{ $ownerIcon }} me-1"></i>{{ $ownerName }}</span></div>
                    </div>
                    <form action="{{ route('dashboard.devices.assign-to-room') }}" method="POST" onsubmit="return confirm('Tambahkan perangkat ini ke ruangan?');">
                      @csrf
                      <input type="hidden" name="device_id" value="{{ $avail->id }}">
                      <button type="submit" class="btn btn-sm btn-primary text-white fw-bold text-nowrap"><i class="bi bi-plus-circle me-1"></i> Tambahkan</button>
                    </form>
                  </div>
                @empty
                  <div class="list-group-item text-center py-4 text-muted empty-db-msg">Tidak ada perangkat {{ $type->jenis }} yang tersedia.</div>
                @endforelse
              </div>
            </div>
          @endforeach
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
    </div>
  </div>
</div>

<!-- Global Add Device Modal -->
<div class="modal fade" id="addDeviceModal" tabindex="-1" aria-labelledby="addDeviceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="addDeviceModalLabel"><i class="bi bi-plus-circle-fill me-1"></i> Tambah Perangkat Dikuasai</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <!-- Search bar inside Add modal -->
        <div class="mb-3 position-relative">
          <!-- <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i> -->
          <input type="text" id="addDeviceSearchInput" class="form-control ps-5" placeholder="Cari perangkat berdasarkan nama, merek, atau BMN...">
        </div>
        
        <!-- Filter Tabs for categories inside the modal -->
        <ul class="nav nav-pills mb-3 justify-content-center gap-2" id="modalTypeTabs" role="tablist">
          @foreach($types as $idx => $type)
            <li class="nav-item" role="presentation">
              <button class="nav-link {{ $idx == 0 ? 'active' : '' }} fw-semibold" id="tab-btn-{{ $type->id }}" data-bs-toggle="tab" data-bs-target="#tab-content-{{ $type->id }}" type="button" role="tab" aria-controls="tab-content-{{ $type->id }}" aria-selected="{{ $idx == 0 ? 'true' : 'false' }}">
                {{ $type->jenis }}
              </button>
            </li>
          @endforeach
        </ul>

        <div class="tab-content" id="modalTypeTabsContent">
          @foreach($types as $idx => $type)
            <div class="tab-pane fade {{ $idx == 0 ? 'show active' : '' }}" id="tab-content-{{ $type->id }}" role="tabpanel" aria-labelledby="tab-btn-{{ $type->id }}">
              
              <div class="list-group shadow-sm mt-2" style="max-height: 350px; overflow-y: auto;">
                @php
                  $typeAvails = $availableDevices->where('id_type', $type->id);
                @endphp

                @forelse($typeAvails as $avail)
                  <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 add-item" data-search-text="{{ strtolower($avail->brand) }} {{ strtolower($avail->series) }} {{ strtolower($avail->id) }} {{ $avail->user ? strtolower($avail->user->name) : ($avail->room ? strtolower($avail->room->ruang) : 'gudang') }}">
                    <div>
                      <div class="fw-bold text-dark">{{ $avail->brand }} - {{ $avail->series }}</div>
                      <small class="text-muted">Kode BMN: {{ $avail->id }} | S/N: {{ $avail->serial_number ?: '-' }}</small>
                      <div class="mt-1">
                        <span class="badge bg-{{ $avail->id_condition == 1 ? 'success' : ($avail->id_condition == 2 ? 'warning text-dark' : 'danger') }} py-1 small">
                          Kondisi: {{ $avail->condition->kondisi ?? 'N/A' }}
                        </span>
                        <span class="ms-2 text-muted small" style="font-size: 0.8rem;">Pemilik saat ini:</span>
                        @if($avail->user)
                          <span class="fw-semibold small" style="font-size: 0.8rem; color: #FF84BA;"><i class="bi bi-person-fill"></i> {{ $avail->user->name }}</span>
                        @elseif($avail->room)
                          <span class="fw-semibold small" style="font-size: 0.8rem; color: #99C2FF;"><i class="bi bi-house-door-fill"></i> {{ $avail->room->ruang }}</span>
                        @else
                          <span class="fw-semibold small" style="font-size: 0.8rem;"><i class="bi bi-box-seam-fill"></i> Gudang</span>
                        @endif
                      </div>
                    </div>
                    <form action="{{ route('dashboard.devices.assign') }}" method="POST" onsubmit="return confirm('Kuasai perangkat ini?');">
                      @csrf
                      <input type="hidden" name="device_id" value="{{ $avail->id }}">
                      <button type="submit" class="btn btn-sm btn-primary text-white fw-bold">
                        <i class="bi bi-plus-circle me-1"></i> Kuasai
                      </button>
                    </form>
                  </div>
                @empty
                  <div class="list-group-item text-center py-4 text-muted empty-db-msg">
                    Tidak ada perangkat lain untuk kategori ini.
                  </div>
                @endforelse
              </div>

            </div>
          @endforeach
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Quick Device QR Scan Modal -->
<div class="modal fade" id="quickScanModal" tabindex="-1" aria-labelledby="quickScanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content overflow-hidden" style="border-radius: 18px;">
      <div class="modal-header bg-primary text-white" style="background: linear-gradient(135deg, #FF84BA 0%, #99C2FF 100%) !important;">
        <h5 class="modal-title fw-bold" id="quickScanModalLabel"><i class="bi bi-qr-code-scan me-2"></i>Pindai QR BMN</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body p-3">
        <p class="small text-muted mb-3">Arahkan kamera ke QR BMN perangkat.</p>
        <div class="rounded-3 overflow-hidden bg-dark">
          <video id="quick-scan-video" class="w-100 d-block" autoplay muted playsinline style="min-height: 250px; object-fit: cover;"></video>
        </div>
        <div id="quick-scan-message" class="alert d-none small mb-0 mt-3 py-2" role="alert"></div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
        <button type="button" id="quick-scan-toggle" class="btn btn-primary btn-sm">
          <i class="bi bi-camera me-1"></i> Mulai Kamera
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Global Open Ticket Modal -->
<div class="modal fade" id="openTicketModal" tabindex="-1" aria-labelledby="openTicketModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
    <div class="modal-content" style="border-radius: 18px; overflow: hidden;">
      <div class="modal-header bg-primary text-white" style="background: linear-gradient(135deg, #FF84BA 0%, #99C2FF 100%) !important;">
        <h5 class="modal-title fw-bold" id="openTicketModalLabel">
          <i class="bi bi-ticket-detailed-fill me-2"></i> Form Tiket Kendala
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3 p-md-4">
        
        <p class="text-muted small mb-3">
          Gunakan opsi ini untuk melaporkan kerusakan perangkat atau kendala jaringan internet di ruangan tertentu (misalnya ruang tanpa pegawai seperti Aula, Gudang, Server, dsb).
        </p>

        <form action="{{ route('report.store') }}" method="POST" id="modalOpenTicketForm">
          @csrf

          <!-- 1. Room Selection -->
          <div class="mb-3">
            <label for="modal_room_selector" class="form-label fw-bold text-dark">
              <i class="bi bi-house-door-fill text-primary me-1"></i> Pilih Ruangan / Lokasi <span class="text-danger">*</span>
            </label>
            <select class="form-select" id="modal_room_selector" name="room_id" required>
              <option value="" disabled selected>-- Pilih Ruangan / Lokasi --</option>
              @foreach($allRooms as $room)
                @php $dCount = $room->ticket_devices->count(); @endphp
                <option value="{{ $room->id }}" data-room-name="{{ $room->ruang }}">
                  {{ $room->ruang }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- 2. Issue Type -->
          <div class="mb-3">
            <label for="modal_issue_type" class="form-label fw-bold text-dark">
              <i class="bi bi-tag-fill text-primary me-1"></i> Jenis Kendala <span class="text-danger">*</span>
            </label>
            <select class="form-select" id="modal_issue_type" name="issue_type" required>
              <option value="" disabled selected>-- Pilih Kategori Kendala --</option>
              <option value="hardware">Hardware (Perangkat Keras: Monitor, RAM, Harddisk, Mati Total)</option>
              <option value="software">Software (Perangkat Lunak: OS Lambat, Aplikasi Crash, Lisensi)</option>
              <option value="jaringan">Jaringan (Koneksi WiFi Putus, Kabel LAN Rusak, Internet Lambat / Seluruh Ruangan)</option>
            </select>
          </div>

          <!-- 3. Network Room-wide Info Banner (shown only when issue_type is 'jaringan') -->
          <div id="modal_network_info_banner" class="alert alert-light border border-primary-subtle d-none mb-3 p-3 rounded-3" style="background: rgba(153, 194, 255, 0.08);">
            <div class="d-flex align-items-start">
              <i class="bi bi-wifi text-primary fs-4 me-2"></i>
              <div>
                <strong class="text-dark small d-block mb-1">Kendala Jaringan / Internet</strong>
                <span class="text-muted small" style="font-size: 0.825rem;">
                  Jika masalah dialami oleh seluruh ruangan, lewati pemilihan perangkat di bawah.
                </span>
              </div>
            </div>
          </div>

          <!-- 4. Device Selection -->
          <div id="modal_device_selection_section" class="mb-3" style="display: none;">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-2">
              <label class="form-label fw-bold text-dark mb-0">
                Pilih Perangkat di <span id="modal_selected_room_title" class="text-primary fw-bold"></span> 
                <span id="modal_device_required_badge" class="text-danger">*</span>
              </label>
              <div class="d-flex gap-2 w-100 w-sm-auto justify-content-end">
                <!-- <button type="button" class="btn btn-outline-secondary btn-sm d-none" id="modal_btn_skip_device" style="font-size: 0.75rem;">
                  <i class="bi bi-x-circle me-1"></i> Tanpa Perangkat Spesifik
                </button> -->
                <input type="text" id="modal_filter_device_input" class="form-control form-control-sm" placeholder="Cari merk / BMN...">
              </div>
            </div>

            <div class="border rounded-3 p-2 bg-light overflow-auto" id="modal_devices_list_container" style="max-height: 220px;">
              <!-- Populated via JS -->
            </div>
            <input type="hidden" name="device_id" id="modal_selected_device_id">
          </div>

          <!-- 5. Preview of Selected Device -->
          <div id="modal_selected_device_preview" class="alert alert-info border-0 shadow-sm p-3 mb-3 d-none" style="background: linear-gradient(135deg, rgba(255, 132, 186, 0.12), rgba(153, 194, 255, 0.15)); border-radius: 14px;">
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                <div class="device-icon-wrapper p-2 bg-white rounded-3 shadow-sm me-3 text-secondary fs-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px;" id="modal_preview_icon">
                  <i class="bi bi-pc-display text-primary"></i>
                </div>
                <div>
                  <span class="badge bg-light border border-primary-subtle small fw-bold px-2 py-1 mb-1" style="color: #000 !important;" id="modal_preview_type">Tipe</span>
                  <h6 class="mb-0 text-dark fw-bold" id="modal_preview_title">Nama Perangkat</h6>
                  <small class="text-muted" id="modal_preview_bmn">BMN: -</small>
                </div>
              </div>
              <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" id="modal_btn_remove_selected_device" title="Hapus Pilihan">
                <i class="bi bi-x-circle-fill fs-5"></i>
              </button>
            </div>
          </div>

          <!-- 6. Description -->
          <div class="mb-3">
            <label for="modal_description" class="form-label fw-bold text-dark">Deskripsi Kendala <span class="text-danger">*</span></label>
            <textarea class="form-control" id="modal_description" name="description" rows="3" placeholder="Jelaskan secara detail kendala yang dialami..." required></textarea>
          </div>

          <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary px-4 btn-accent" id="modal_submit_btn" disabled>
              <i class="bi bi-send-fill me-1"></i> Kirim Laporan Tiket
            </button>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/qr-scanner@1.4.2/qr-scanner.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('quickScanModal');
    const video = document.getElementById('quick-scan-video');
    const toggleButton = document.getElementById('quick-scan-toggle');
    const message = document.getElementById('quick-scan-message');
    let scanner = null;
    let isScanning = false;
    let isProcessing = false;

    function setButton(state) {
        const busy = state === 'busy';
        toggleButton.disabled = busy;
        toggleButton.innerHTML = busy
            ? '<span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span> Memproses...'
            : state === 'scanning'
                ? '<i class="bi bi-stop-circle me-1"></i> Stop Kamera'
                : '<i class="bi bi-camera me-1"></i> Mulai Kamera';
    }

    function showMessage(type, text) {
        message.className = `alert alert-${type} small mb-0 mt-3 py-2`;
        message.textContent = text;
    }

    function hideMessage() {
        message.className = 'alert d-none small mb-0 mt-3 py-2';
        message.textContent = '';
    }

    async function stopScanner(keepButtonBusy = false) {
        if (scanner && isScanning) await scanner.stop();
        isScanning = false;
        if (!keepButtonBusy) setButton('idle');
    }

    async function handleScanResult(result) {
        if (isProcessing) return;
        isProcessing = true;
        setButton('busy');
        showMessage('info', 'QR terbaca. Mencari perangkat...');

        try {
            await stopScanner(true);
            const response = await fetch("{{ route('dashboard.quick-scan') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                body: JSON.stringify({ qr_string: result.data }),
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Perangkat tidak ditemukan.');

            window.location.assign(data.redirect);
        } catch (error) {
            showMessage('danger', error.message || 'QR tidak dapat diproses.');
            isProcessing = false;
            setButton('idle');
        }
    }

    async function startScanner() {
        if (isScanning) return stopScanner();
        if (typeof QrScanner === 'undefined') {
            showMessage('danger', 'Pemindai QR tidak tersedia. Muat ulang halaman lalu coba lagi.');
            return;
        }

        isProcessing = false;
        setButton('busy');
        showMessage('info', 'Meminta izin akses kamera...');

        try {
            if (!scanner) {
                scanner = new QrScanner(video, handleScanResult, {
                    preferredCamera: 'environment',
                    maxScansPerSecond: 25,
                    highlightScanRegion: false,
                    highlightCodeOutline: false,
                    returnDetailedScanResult: true,
                });
            }
            // Dipanggil langsung dari klik tombol agar browser menampilkan prompt izin kamera.
            await scanner.start();
            isScanning = true;
            hideMessage();
            setButton('scanning');
        } catch (error) {
            isScanning = false;
            const denied = ['NotAllowedError', 'SecurityError', 'PermissionDeniedError'].includes(error && error.name);
            showMessage('danger', denied
                ? 'Kamera tidak dapat diakses. Periksa izin kamera Anda.'
                : 'Kamera gagal dinyalakan. Coba lagi.');
            setButton('idle');
        }
    }

    toggleButton.addEventListener('click', startScanner);
    modalElement.addEventListener('hidden.bs.modal', async function() {
        isProcessing = false;
        await stopScanner();
        hideMessage();
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Search in Swap Modal
    document.querySelectorAll('.swap-search-input').forEach(input => {
        input.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const deviceId = this.getAttribute('data-device-id');
            const items = document.querySelectorAll(`#swapModal-${deviceId} .swap-item`);
            let count = 0;
            
            items.forEach(item => {
                const text = item.getAttribute('data-search-text') || '';
                if (text.includes(query)) {
                    item.style.setProperty('display', 'flex', 'important');
                    count++;
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });
            
            const listGroup = document.querySelector(`#swapModal-${deviceId} .list-group`);
            let emptyMsg = listGroup.querySelector('.no-results-msg');
            if (count === 0) {
                if (!emptyMsg) {
                    emptyMsg = document.createElement('div');
                    emptyMsg.className = 'list-group-item text-center py-4 text-muted no-results-msg';
                    emptyMsg.innerText = 'Tidak ada perangkat yang cocok dengan pencarian.';
                    listGroup.appendChild(emptyMsg);
                }
            } else if (emptyMsg) {
                emptyMsg.remove();
            }
        });
    });

    // 2. Search in Add Device Modal
    const addSearchInput = document.getElementById('addDeviceSearchInput');
    if (addSearchInput) {
        addSearchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            
            document.querySelectorAll('#addDeviceModal .tab-pane').forEach(pane => {
                const items = pane.querySelectorAll('.add-item');
                let visibleCount = 0;
                
                items.forEach(item => {
                    const text = item.getAttribute('data-search-text') || '';
                    if (text.includes(query)) {
                        item.style.setProperty('display', 'flex', 'important');
                        visibleCount++;
                    } else {
                        item.style.setProperty('display', 'none', 'important');
                    }
                });
                
                const listGroup = pane.querySelector('.list-group');
                let emptyMsg = listGroup.querySelector('.no-results-msg');
                
                if (items.length > 0) {
                    if (visibleCount === 0) {
                        if (!emptyMsg) {
                            emptyMsg = document.createElement('div');
                            emptyMsg.className = 'list-group-item text-center py-4 text-muted no-results-msg';
                            emptyMsg.innerText = 'Tidak ada perangkat yang cocok dengan pencarian.';
                            listGroup.appendChild(emptyMsg);
                        }
                    } else if (emptyMsg) {
                        emptyMsg.remove();
                    }
                }
            });
        });
    }

    // 3. Search in unified Add Device to Room modal, across every type tab.
    const roomAddSearchInput = document.getElementById('roomAddDeviceSearchInput');
    if (roomAddSearchInput) {
        roomAddSearchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('#addRoomDeviceModal .tab-pane').forEach(pane => {
                const items = pane.querySelectorAll('.room-add-tab-item');
                const list = pane.querySelector('.room-add-tab-list');
                let visibleCount = 0;

                items.forEach(item => {
                    const isVisible = (item.getAttribute('data-search-text') || '').includes(query);
                    item.style.setProperty('display', isVisible ? 'flex' : 'none', 'important');
                    if (isVisible) visibleCount++;
                });

                let emptyMessage = list.querySelector('.no-room-tab-results-msg');
                if (items.length && visibleCount === 0 && !emptyMessage) {
                    emptyMessage = document.createElement('div');
                    emptyMessage.className = 'list-group-item text-center py-4 text-muted no-room-tab-results-msg';
                    emptyMessage.innerText = 'Tidak ada perangkat yang cocok dengan pencarian.';
                    list.appendChild(emptyMessage);
                } else if (visibleCount > 0 && emptyMessage) {
                    emptyMessage.remove();
                }
            });
        });
    }

    // Legacy per-tab modal search (kept for backwards-compatible markup).
    document.querySelectorAll('.room-add-device-search').forEach(input => {
        input.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const modal = this.closest('.modal');
            const list = modal.querySelector('.room-add-device-list');
            const items = list.querySelectorAll('.room-add-device-item');
            let visibleCount = 0;

            items.forEach(item => {
                const text = item.getAttribute('data-search-text') || '';
                const isVisible = text.includes(query);
                item.style.setProperty('display', isVisible ? 'flex' : 'none', 'important');
                if (isVisible) visibleCount++;
            });

            let emptyMessage = list.querySelector('.no-room-add-results-msg');
            if (items.length && visibleCount === 0 && !emptyMessage) {
                emptyMessage = document.createElement('div');
                emptyMessage.className = 'list-group-item text-center py-4 text-muted no-room-add-results-msg';
                emptyMessage.innerText = 'Tidak ada perangkat yang cocok dengan pencarian.';
                list.appendChild(emptyMessage);
            } else if (visibleCount > 0 && emptyMessage) {
                emptyMessage.remove();
            }
        });
    });
});

// ── Move-to-Gudang custom confirm dialog ──────────────────────────────────
function confirmMoveGudang(deviceId, deviceName) {
    const overlay = document.createElement('div');
    overlay.className = 'custom-confirm-overlay';
    overlay.innerHTML = `
      <div class="custom-confirm-box">
        <div class="custom-confirm-icon">
          <i class="bi bi-trash3-fill" style="color:#e11d48;"></i>
        </div>
        <div class="custom-confirm-title">Pindah ke Gudang?</div>
        <div class="custom-confirm-text">
          Perangkat <strong>${deviceName}</strong> akan dipindahkan ke <strong>Gudang</strong>.<br>
          Perangkat tidak akan lagi muncul dalam daftar perangkat ruangan ini.
        </div>
        <div class="custom-confirm-buttons">
          <button class="btn-confirm-secondary" id="ccBtnCancel">Batal</button>
          <button class="btn-confirm-primary" id="ccBtnConfirm" style="background: linear-gradient(135deg,#e11d48,#FF84BA);">
            <i class="bi bi-trash3-fill me-1"></i> Ya, Pindahkan
          </button>
        </div>
      </div>`;
    document.body.appendChild(overlay);
    requestAnimationFrame(() => overlay.classList.add('show'));

    overlay.querySelector('#ccBtnCancel').addEventListener('click', () => {
        overlay.classList.remove('show');
        setTimeout(() => overlay.remove(), 260);
    });
    overlay.querySelector('#ccBtnConfirm').addEventListener('click', () => {
        const form = document.getElementById('gudang-form-' + deviceId);
        if (form) form.submit();
        overlay.classList.remove('show');
        setTimeout(() => overlay.remove(), 260);
    });
}
// ── Open Ticket Modal Handler ──────────────────────────────────────────
const modalRoomsData = @json($allRooms);

document.addEventListener('DOMContentLoaded', function() {
    const mRoomSelector = document.getElementById('modal_room_selector');
    const mIssueTypeSelector = document.getElementById('modal_issue_type');
    const mNetworkBanner = document.getElementById('modal_network_info_banner');
    const mDeviceRequiredBadge = document.getElementById('modal_device_required_badge');
    const mBtnSkipDevice = document.getElementById('modal_btn_skip_device');
    const mDeviceSection = document.getElementById('modal_device_selection_section');
    const mDevicesContainer = document.getElementById('modal_devices_list_container');
    const mSelectedRoomTitle = document.getElementById('modal_selected_room_title');
    const mSelectedDeviceIdInput = document.getElementById('modal_selected_device_id');
    const mFilterInput = document.getElementById('modal_filter_device_input');
    const mPreviewBox = document.getElementById('modal_selected_device_preview');
    const mPreviewTitle = document.getElementById('modal_preview_title');
    const mPreviewBmn = document.getElementById('modal_preview_bmn');
    const mPreviewType = document.getElementById('modal_preview_type');
    const mPreviewIcon = document.getElementById('modal_preview_icon');
    const mBtnRemoveDevice = document.getElementById('modal_btn_remove_selected_device');
    const mDescription = document.getElementById('modal_description');
    const mSubmitBtn = document.getElementById('modal_submit_btn');

    function checkFormValidity() {
        const hasRoom = mRoomSelector && mRoomSelector.value !== '';
        const issueType = mIssueTypeSelector ? mIssueTypeSelector.value : '';
        const hasIssue = issueType !== '';
        const hasDesc = mDescription && mDescription.value.trim().length >= 5;
        const hasDevice = mSelectedDeviceIdInput && mSelectedDeviceIdInput.value !== '';

        if (!hasRoom || !hasIssue || !hasDesc) {
            mSubmitBtn.disabled = true;
            return;
        }

        if (issueType === 'jaringan') {
            // For network issue, device is optional!
            mSubmitBtn.disabled = false;
        } else {
            // For hardware & software, device is mandatory!
            mSubmitBtn.disabled = !hasDevice;
        }
    }

    function handleIssueTypeChange() {
        const issueType = mIssueTypeSelector.value;

        if (issueType === 'jaringan') {
            if (mNetworkBanner) mNetworkBanner.classList.remove('d-none');
            if (mDeviceRequiredBadge) {
                mDeviceRequiredBadge.innerHTML = '<span class="badge bg-secondary-subtle text-secondary small fw-normal ms-1">Opsional</span>';
            }
            if (mBtnSkipDevice) mBtnSkipDevice.classList.remove('d-none');
            if (mDescription && (!mDescription.value || mDescription.placeholder.includes('perangkat'))) {
                mDescription.placeholder = 'Contoh: Koneksi LAN/WiFi mati berjamaah...';
            }
        } else {
            if (mNetworkBanner) mNetworkBanner.classList.add('d-none');
            if (mDeviceRequiredBadge) {
                mDeviceRequiredBadge.innerHTML = '<span class="text-danger">* (Wajib)</span>';
            }
            if (mBtnSkipDevice) mBtnSkipDevice.classList.add('d-none');
            if (mDescription && mDescription.placeholder.includes('WiFi')) {
                mDescription.placeholder = 'Jelaskan secara detail kendala yang dialami pada perangkat...';
            }
        }

        checkFormValidity();
    }

    function clearDeviceSelection() {
        mSelectedDeviceIdInput.value = '';
        mPreviewBox.classList.add('d-none');
        document.querySelectorAll('.modal-device-item').forEach(el => {
            el.classList.remove('border-primary', 'bg-primary-subtle');
            const btn = el.querySelector('.select-modal-btn');
            if (btn) {
                btn.className = 'btn btn-sm btn-outline-primary py-0 px-2 select-modal-btn';
                btn.innerText = 'Pilih';
            }
        });
        checkFormValidity();
    }

    if (mBtnRemoveDevice) {
        mBtnRemoveDevice.addEventListener('click', clearDeviceSelection);
    }
    if (mBtnSkipDevice) {
        mBtnSkipDevice.addEventListener('click', clearDeviceSelection);
    }

    if (mIssueTypeSelector) {
        mIssueTypeSelector.addEventListener('change', handleIssueTypeChange);
    }

    if (mDescription) {
        mDescription.addEventListener('input', checkFormValidity);
    }

    if (mRoomSelector) {
        mRoomSelector.addEventListener('change', function() {
            const roomId = this.value;
            const room = modalRoomsData.find(r => r.id == roomId);
            if (!room) {
                mDeviceSection.style.display = 'none';
                return;
            }

            const devices = room.ticket_devices || [];
            mSelectedRoomTitle.innerText = room.ruang;
            mDeviceSection.style.display = 'block';

            clearDeviceSelection();

            if (devices.length === 0) {
                mDevicesContainer.innerHTML = `
                    <div class="text-center py-3 text-muted">
                        <small>Tidak ada perangkat terdaftar pada ${room.ruang}.</small>
                    </div>`;
                checkFormValidity();
                return;
            }

            mDevicesContainer.innerHTML = '';
            devices.forEach(dev => {
                const typeName = dev.type ? dev.type.jenis : 'Lainnya';
                const condName = dev.condition ? dev.condition.kondisi : 'N/A';
                const condClass = dev.id_condition == 1 ? 'success' : (dev.id_condition == 2 ? 'warning text-dark' : 'danger');
                const ownerName = dev.user ? dev.user.name : (dev.room ? dev.room.ruang : 'Gudang');
                const ownerIcon = dev.user ? 'bi-person-fill' : (dev.room ? 'bi-house-door-fill' : 'bi-box-seam-fill');

                let iconHtml = '<i class="bi bi-cpu-fill text-secondary"></i>';
                const lowerType = typeName.toLowerCase();
                if (lowerType.includes('pc')) iconHtml = '<i class="bi bi-pc-display text-primary"></i>';
                else if (lowerType.includes('laptop')) iconHtml = '<i class="bi bi-laptop text-primary"></i>';
                else if (lowerType.includes('printer')) iconHtml = '<i class="bi bi-printer text-success"></i>';
                else if (lowerType.includes('ups')) iconHtml = '<i class="bi bi-lightning-charge-fill text-warning"></i>';
                else if (lowerType.includes('scanner')) iconHtml = '<i class="bi bi-camera text-info"></i>';
                else if (lowerType.includes('tablet')) iconHtml = '<i class="bi bi-tablet text-primary"></i>';
                else if (lowerType.includes('smartphone') || lowerType.includes('phone') || lowerType.includes('hp')) iconHtml = '<i class="bi bi-phone text-info"></i>';
                else if (lowerType.includes('viewer') || lowerType.includes('proyektor')) iconHtml = '<i class="bi bi-projector text-secondary"></i>';

                const item = document.createElement('div');
                item.className = 'p-2 mb-2 rounded border bg-white cursor-pointer modal-device-item d-flex justify-content-between align-items-center';
                item.style.cursor = 'pointer';
                item.setAttribute('data-device-id', dev.id);
                item.setAttribute('data-search', `${dev.brand || ''} ${dev.series || ''} ${dev.id || ''} ${typeName} ${ownerName}`.toLowerCase());

                item.innerHTML = `
                    <div class="d-flex align-items-center">
                        <div class="device-icon-wrapper p-2 bg-light rounded-3 me-2 text-secondary fs-5 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; min-width: 40px;">
                            ${iconHtml}
                        </div>
                        <div>
                            <div class="fw-bold text-dark small">${dev.brand || 'Perangkat'} - ${dev.series || ''}</div>
                            <small class="text-muted" style="font-size: 0.75rem;">BMN: ${dev.id} | <span class="badge bg-${condClass} py-0 px-1" style="font-size:0.65rem;">${condName}</span></small>
                            <small class="text-muted d-block" style="font-size: 0.72rem;"><i class="bi ${ownerIcon} me-1"></i>Pemilik: ${ownerName}</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 select-modal-btn" style="font-size: 0.75rem;">
                        Pilih
                    </button>
                `;

                item.addEventListener('click', function() {
                    mSelectedDeviceIdInput.value = dev.id;
                    mPreviewTitle.innerText = `${dev.brand || 'Perangkat'} - ${dev.series || ''}`;
                    mPreviewBmn.innerText = `Kode BMN: ${dev.id} | S/N: ${dev.serial_number || '-'}`;
                    mPreviewType.innerText = typeName;
                    mPreviewIcon.innerHTML = iconHtml;
                    mPreviewBox.classList.remove('d-none');

                    document.querySelectorAll('.modal-device-item').forEach(el => {
                        const isCurrent = (el.getAttribute('data-device-id') == dev.id);
                        el.classList.toggle('border-primary', isCurrent);
                        el.classList.toggle('bg-primary-subtle', isCurrent);
                        const btn = el.querySelector('.select-modal-btn');
                        if (btn) {
                            btn.className = `btn btn-sm ${isCurrent ? 'btn-primary' : 'btn-outline-primary'} py-0 px-2 select-modal-btn`;
                            btn.innerText = isCurrent ? 'Terpilih' : 'Pilih';
                        }
                    });

                    checkFormValidity();
                });

                mDevicesContainer.appendChild(item);
            });

            checkFormValidity();
        });

        if (mFilterInput) {
            mFilterInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                document.querySelectorAll('.modal-device-item').forEach(item => {
                    const text = item.getAttribute('data-search') || '';
                    if (text.includes(query)) {
                        item.style.setProperty('display', 'flex', 'important');
                    } else {
                        item.style.setProperty('display', 'none', 'important');
                    }
                });
            });
        }
    }
});
</script>

<style>
/* ── Room-type tab active state ───────────────────────────────────────────── */
#roomTypeTabs .nav-link {
    color: var(--text-secondary);
    background: transparent;
}
#roomTypeTabs .nav-link:hover {
    color: #FF84BA;
    background: rgba(255,132,186,.06);
}
#roomTypeTabs .nav-link.active {
    color: #FF84BA !important;
    background: #fff !important;
    border-color: var(--border-color) var(--border-color) #fff !important;
    box-shadow: 0 -2px 0 0 #FF84BA inset;
}
</style>
@endsection
