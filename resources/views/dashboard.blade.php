@extends('layouts.app')

@section('title', 'Dashboard Perangkat TI | MLTI-Report')

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3">
  <div>
    <h1 class="fw-bold" style="color: var(--color-primary);">Perangkat TI Saya</h1>
    <nav>
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
        <li class="breadcrumb-item active">Perangkat Saya</li>
      </ol>
    </nav>
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
                Berikut adalah daftar seluruh perangkat TI yang berada dalam penguasaan Anda. Anda dapat melaporkan kerusakan, memantau perbaikan, mengubah, atau melepaskan perangkat langsung dari kartu masing-masing.
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
            <div>
              <div class="d-flex align-items-start mb-3">
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
                    @default
                      <i class="bi bi-cpu-fill text-secondary"></i>
                  @endswitch
                </div>
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center justify-content-between">
                    <span class="badge bg-light text-primary border border-primary-subtle small fw-bold px-2 py-1 mb-1">{{ $device->type->jenis ?? 'Lainnya' }}</span>
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
                  <i class="bi bi-exclamation-triangle-fill me-1"></i> Laporkan Kerusakan
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
                Mengganti perangkat akan otomatis **melepaskan** perangkat *{{ $device->brand }} - {{ $device->series }}* (BMN: {{ $device->id }}) kembali ke Gudang, dan menggantinya dengan perangkat baru yang Anda pilih di bawah.
              </div>

              <h6 class="fw-bold mb-2">Pilih Perangkat Pengganti (Tipe: {{ $device->type->jenis ?? 'Lainnya' }})</h6>
              
              <div class="mb-3 position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" class="form-control ps-5 swap-search-input" placeholder="Cari perangkat berdasarkan nama, merek, atau BMN..." data-device-id="{{ $device->id }}">
              </div>
              
              <div class="list-group shadow-sm" style="max-height: 350px; overflow-y: auto;">
                @php
                  $availableOfType = $availableDevices->where('id_type', $device->id_type);
                @endphp

                @forelse($availableOfType as $avail)
                  <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 swap-item" data-search-text="{{ strtolower($avail->brand) }} {{ strtolower($avail->series) }} {{ strtolower($avail->id) }}">
                    <div>
                      <div class="fw-bold text-dark">{{ $avail->brand }} - {{ $avail->series }}</div>
                      <small class="text-muted">Kode BMN: {{ $avail->id }} | S/N: {{ $avail->serial_number ?: '-' }}</small>
                      <div>
                        <span class="badge bg-{{ $avail->id_condition == 1 ? 'success' : ($avail->id_condition == 2 ? 'warning text-dark' : 'danger') }} py-1 small">
                          Kondisi: {{ $avail->condition->kondisi ?? 'N/A' }}
                        </span>
                      </div>
                    </div>
                    <form action="{{ route('dashboard.devices.swap') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengganti ke perangkat ini?');">
                      @csrf
                      <input type="hidden" name="old_device_id" value="{{ $device->id }}">
                      <input type="hidden" name="new_device_id" value="{{ $avail->id }}">
                      <button type="submit" class="btn btn-sm btn-success text-white fw-bold">
                        Pilih & Ganti
                      </button>
                    </form>
                  </div>
                @empty
                  <div class="list-group-item text-center py-4 text-muted">
                    Tidak ada perangkat dengan kategori ini yang tersedia di Gudang untuk saat ini.
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
          <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
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
                  <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 add-item" data-search-text="{{ strtolower($avail->brand) }} {{ strtolower($avail->series) }} {{ strtolower($avail->id) }}">
                    <div>
                      <div class="fw-bold text-dark">{{ $avail->brand }} - {{ $avail->series }}</div>
                      <small class="text-muted">Kode BMN: {{ $avail->id }} | S/N: {{ $avail->serial_number ?: '-' }}</small>
                      <div>
                        <span class="badge bg-{{ $avail->id_condition == 1 ? 'success' : ($avail->id_condition == 2 ? 'warning text-dark' : 'danger') }} py-1 small">
                          Kondisi: {{ $avail->condition->kondisi ?? 'N/A' }}
                        </span>
                      </div>
                    </div>
                    <form action="{{ route('dashboard.devices.assign') }}" method="POST" onsubmit="return confirm('Kuasai perangkat ini?');">
                      @csrf
                      <input type="hidden" name="device_id" value="{{ $avail->id }}">
                      <button type="submit" class="btn btn-sm btn-success text-white fw-bold">
                        <i class="bi bi-plus-circle me-1"></i> Kuasai
                      </button>
                    </form>
                  </div>
                @empty
                  <div class="list-group-item text-center py-4 text-muted empty-db-msg">
                    Tidak ada perangkat untuk kategori ini yang tersedia di Gudang.
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
@endsection

@section('scripts')
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
});
</script>
@endsection
