@extends('layouts.app')

@section('title', 'Open Ticket | Lapor Kendala Ruangan')

@section('content')
<div class="pagetitle d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
  <div>
    <h1 class="fw-bold" style="color: var(--color-primary);">Open Ticket Pelaporan Kendala</h1>
    <nav>
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
        <li class="breadcrumb-item active">Open Ticket</li>
      </ol>
    </nav>
  </div>
  <div>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 fw-semibold">
      <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
    </a>
  </div>
</div>

<section class="section">
  <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">

      <!-- Header Info Card -->
      <div class="card border-0 shadow-sm overflow-hidden mb-4" style="background: linear-gradient(135deg, var(--color-primary) 0%, #1e293b 100%); border-radius: 16px;">
        <div class="card-body p-4 ps-4 text-white">
          <div class="d-flex align-items-center">
            <div class="p-3 bg-white bg-opacity-10 rounded-3 text-white fs-2 me-3">
              <i class="bi bi-ticket-detailed-fill text-warning"></i>
            </div>
            <div>
              <h4 class="fw-bold mb-1 text-white">Lapor Kendala Perangkat &amp; Jaringan Ruangan</h4>
              <p class="mb-0 text-white-50 small">
                Gunakan Open Ticket untuk melaporkan kerusakan pada perangkat atau gangguan jaringan internet di ruangan umum, gudang, aula, atau ruangan lain.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Open Ticket Form Container -->
      <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center">
          <span class="badge rounded-circle p-2 me-2 text-white" style="background: linear-gradient(135deg, #FF84BA, #99C2FF); width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">1</span>
          <h5 class="card-title my-0 text-dark fw-bold">Lokasi Ruangan &amp; Kategori Kendala</h5>
        </div>

        <div class="card-body p-4">
          <form action="{{ route('report.store') }}" method="POST" id="openTicketForm">
            @csrf

            <!-- 1. Room Selection -->
            <div class="mb-4">
              <label for="room_selector" class="form-label fw-bold text-dark">
                <i class="bi bi-house-door-fill text-primary me-1"></i> Pilih Lokasi Ruangan <span class="text-danger">*</span>
              </label>
              <select class="form-select form-select-lg" id="room_selector" name="room_id" required>
                <option value="" disabled selected>-- Pilih Ruangan / Lokasi --</option>
                @foreach($rooms as $room)
                  @php
                    $deviceCount = $room->devices->count();
                  @endphp
                  <option value="{{ $room->id }}" data-room-name="{{ $room->ruang }}" {{ ($selectedRoomId == $room->id) ? 'selected' : '' }}>
                    {{ $room->ruang }} ({{ $deviceCount }} Perangkat)
                  </option>
                @endforeach
              </select>
              <div class="form-text small text-muted">Pilih ruangan tempat perangkat yang bermasalah atau jaringan yang terkendala berada.</div>
            </div>

            <!-- 2. Issue Type Select -->
            <div class="mb-4">
              <label for="issue_type" class="form-label fw-bold text-dark">
                <i class="bi bi-tag-fill text-primary me-1"></i> Jenis Kendala <span class="text-danger">*</span>
              </label>
              <select class="form-select form-select-lg @error('issue_type') is-invalid @enderror" id="issue_type" name="issue_type" required>
                <option value="" disabled selected>-- Pilih Kategori Kendala --</option>
                <option value="hardware" {{ old('issue_type') == 'hardware' ? 'selected' : '' }}>Hardware (Perangkat Keras: Monitor rusak, RAM, Harddisk, Mati Total)</option>
                <option value="software" {{ old('issue_type') == 'software' ? 'selected' : '' }}>Software (Perangkat Lunak: OS Lambat, Blue Screen, Aplikasi Error/Crash, Lisensi)</option>
                <option value="jaringan" {{ old('issue_type') == 'jaringan' ? 'selected' : '' }}>Jaringan (Koneksi WiFi Putus, Kabel LAN Rusak, Internet Lambat / Seluruh Ruangan)</option>
              </select>
              @error('issue_type')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- 3. Network Room-wide Info Banner (shown only when issue_type is 'jaringan') -->
            <div id="network_info_banner" class="alert alert-light border border-primary-subtle d-none mb-4 p-3 rounded-3" style="background: rgba(153, 194, 255, 0.08);">
              <div class="d-flex align-items-start">
                <i class="bi bi-wifi text-primary fs-3 me-3"></i>
                <div>
                  <h6 class="text-dark fw-bold mb-1">Kendala Jaringan / Koneksi Internet Ruangan</h6>
                  <p class="text-muted small mb-0" style="line-height: 1.5;">
                    Pemilihan perangkat bersifat <strong>opsional</strong> untuk kendala jaringan. Jika masalah dialami oleh seluruh area ruangan atau access point WiFi, Anda dapat <strong>melewati pemilihan perangkat</strong> di bawah dan langsung mengisi deskripsi kendala.
                  </p>
                </div>
              </div>
            </div>

            <!-- 4. Device Selection Area -->
            <div id="device_selection_section" class="mb-4" style="display: none;">
              <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-2">
                <label class="form-label fw-bold text-dark mb-0">
                  <i class="bi bi-cpu-fill text-primary me-1"></i> Pilih Perangkat di <span id="selected_room_title" class="text-primary"></span> 
                  <span id="device_required_badge" class="text-danger">*</span>
                </label>
                <div class="d-flex gap-2 w-100 w-sm-auto justify-content-end">
                  <button type="button" class="btn btn-outline-secondary btn-sm d-none" id="btn_skip_device">
                    <i class="bi bi-x-circle me-1"></i> Tanpa Perangkat Spesifik
                  </button>
                  <div class="input-group input-group-sm" style="max-width: 250px;">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="filter_device_input" class="form-control border-start-0" placeholder="Cari merk / BMN...">
                  </div>
                </div>
              </div>

              <!-- List of devices rendered dynamically -->
              <div class="border rounded-3 p-2 bg-light overflow-auto" id="devices_list_container" style="max-height: 280px;">
                <!-- Dynamically populated via JS -->
              </div>
              <input type="hidden" name="device_id" id="selected_device_id">
            </div>

            <!-- Selected Device Summary Badge -->
            <div id="selected_device_preview" class="alert alert-info border-0 shadow-sm p-3 mb-4 d-none" style="background: linear-gradient(135deg, rgba(255, 132, 186, 0.12), rgba(153, 194, 255, 0.15)); border-radius: 14px;">
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                  <div class="device-icon-wrapper p-2 bg-white rounded-3 shadow-sm me-3 text-secondary fs-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;" id="preview_device_icon">
                    <i class="bi bi-pc-display text-primary"></i>
                  </div>
                  <div>
                    <span class="badge bg-light border border-primary-subtle small fw-bold px-2 py-1 mb-1" style="color: #000 !important;" id="preview_device_type">Tipe</span>
                    <h6 class="mb-0 text-dark fw-bold" id="preview_device_title">Nama Perangkat</h6>
                    <small class="text-muted" id="preview_device_bmn">BMN: - | S/N: -</small>
                  </div>
                </div>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" id="btn_remove_selected_device" title="Hapus Pilihan">
                  <i class="bi bi-x-circle-fill fs-4"></i>
                </button>
              </div>
            </div>

            <!-- Step 2 Divider -->
            <hr class="my-4" style="border-color: var(--border-color);">

            <div class="d-flex align-items-center mb-3">
              <span class="badge rounded-circle p-2 me-2 text-white" style="background: linear-gradient(135deg, #FF84BA, #99C2FF); width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">2</span>
              <h5 class="my-0 text-dark fw-bold">Detail &amp; Deskripsi Kendala</h5>
            </div>

            <!-- Description Textarea -->
            <div class="mb-4">
              <label for="description" class="form-label fw-bold text-dark">Deskripsi Kendala <span class="text-danger">*</span></label>
              <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Jelaskan secara mendetail kendala atau kerusakan yang dialami..." required>{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Submit buttons -->
            <div class="d-flex justify-content-end gap-2 border-top pt-3">
              <a href="{{ route('dashboard') }}" class="btn btn-secondary px-4">Batal</a>
              <button type="submit" class="btn btn-primary px-4 btn-accent" id="submitBtn" disabled>
                <i class="bi bi-send-fill me-1"></i> Kirim Laporan Open Ticket
              </button>
            </div>

          </form>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
// Data structure containing all rooms and their devices
const roomsData = @json($rooms);
const preselectedRoomId = '{{ $selectedRoomId ?? "" }}';
const preselectedDeviceId = '{{ $selectedDeviceId ?? "" }}';

document.addEventListener('DOMContentLoaded', function() {
    const roomSelector = document.getElementById('room_selector');
    const issueTypeSelector = document.getElementById('issue_type');
    const networkBanner = document.getElementById('network_info_banner');
    const deviceRequiredBadge = document.getElementById('device_required_badge');
    const btnSkipDevice = document.getElementById('btn_skip_device');
    const deviceSection = document.getElementById('device_selection_section');
    const devicesContainer = document.getElementById('devices_list_container');
    const selectedRoomTitle = document.getElementById('selected_room_title');
    const selectedDeviceIdInput = document.getElementById('selected_device_id');
    const filterInput = document.getElementById('filter_device_input');
    const previewBox = document.getElementById('selected_device_preview');
    const previewTitle = document.getElementById('preview_device_title');
    const previewBmn = document.getElementById('preview_device_bmn');
    const previewType = document.getElementById('preview_device_type');
    const previewIcon = document.getElementById('preview_device_icon');
    const btnRemoveDevice = document.getElementById('btn_remove_selected_device');
    const description = document.getElementById('description');
    const submitBtn = document.getElementById('submitBtn');

    let currentRoomDevices = [];

    function checkFormValidity() {
        const hasRoom = roomSelector && roomSelector.value !== '';
        const issueType = issueTypeSelector ? issueTypeSelector.value : '';
        const hasIssue = issueType !== '';
        const hasDesc = description && description.value.trim().length >= 5;
        const hasDevice = selectedDeviceIdInput && selectedDeviceIdInput.value !== '';

        if (!hasRoom || !hasIssue || !hasDesc) {
            submitBtn.disabled = true;
            return;
        }

        if (issueType === 'jaringan') {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = !hasDevice;
        }
    }

    function handleIssueTypeChange() {
        const issueType = issueTypeSelector.value;

        if (issueType === 'jaringan') {
            if (networkBanner) networkBanner.classList.remove('d-none');
            if (deviceRequiredBadge) {
                deviceRequiredBadge.innerHTML = '<span class="badge bg-secondary-subtle text-secondary small fw-normal ms-1">Opsional</span>';
            }
            if (btnSkipDevice) btnSkipDevice.classList.remove('d-none');
            if (description && (!description.value || description.placeholder.includes('perangkat'))) {
                description.placeholder = 'Contoh: Koneksi internet/WiFi di ruangan tidak bisa terhubung sejak pagi, lampu router berkedip merah...';
            }
        } else {
            if (networkBanner) networkBanner.classList.add('d-none');
            if (deviceRequiredBadge) {
                deviceRequiredBadge.innerHTML = '<span class="text-danger">* (Wajib)</span>';
            }
            if (btnSkipDevice) btnSkipDevice.classList.add('d-none');
            if (description && description.placeholder.includes('WiFi')) {
                description.placeholder = 'Jelaskan secara mendetail kendala yang dialami pada perangkat...';
            }
        }

        checkFormValidity();
    }

    function clearDeviceSelection() {
        selectedDeviceIdInput.value = '';
        previewBox.classList.add('d-none');
        document.querySelectorAll('.device-item').forEach(el => {
            el.classList.remove('border-primary', 'shadow-sm', 'bg-primary-subtle');
            const btn = el.querySelector('.select-device-btn');
            if (btn) {
                btn.className = 'btn btn-sm btn-outline-primary select-device-btn';
                btn.innerHTML = '<i class="bi bi-plus-circle me-1"></i> Pilih';
            }
        });
        checkFormValidity();
    }

    if (btnRemoveDevice) {
        btnRemoveDevice.addEventListener('click', clearDeviceSelection);
    }
    if (btnSkipDevice) {
        btnSkipDevice.addEventListener('click', clearDeviceSelection);
    }

    if (issueTypeSelector) {
        issueTypeSelector.addEventListener('change', handleIssueTypeChange);
    }

    if (description) {
        description.addEventListener('input', checkFormValidity);
    }

    function renderDevices(roomId) {
        const room = roomsData.find(r => r.id == roomId);
        if (!room) {
            deviceSection.style.display = 'none';
            return;
        }

        currentRoomDevices = room.devices || [];
        selectedRoomTitle.innerText = room.ruang;
        deviceSection.style.display = 'block';

        clearDeviceSelection();

        if (currentRoomDevices.length === 0) {
            devicesContainer.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-3 d-block mb-1 text-secondary"></i>
                    <small>Tidak ada perangkat terdaftar pada ${room.ruang}.</small>
                </div>`;
            checkFormValidity();
            return;
        }

        populateDeviceList(currentRoomDevices);
        checkFormValidity();
    }

    function populateDeviceList(devices) {
        devicesContainer.innerHTML = '';
        const selectedId = selectedDeviceIdInput.value;

        devices.forEach(dev => {
            const isSelected = (dev.id == selectedId);
            const typeName = dev.type ? dev.type.jenis : 'Lainnya';
            const condName = dev.condition ? dev.condition.kondisi : 'N/A';
            const condClass = dev.id_condition == 1 ? 'success' : (dev.id_condition == 2 ? 'warning text-dark' : 'danger');

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
            item.className = `p-3 mb-2 rounded-3 border bg-white cursor-pointer device-item d-flex justify-content-between align-items-center transition ${isSelected ? 'border-primary shadow-sm bg-primary-subtle' : ''}`;
            item.style.cursor = 'pointer';
            item.setAttribute('data-device-id', dev.id);
            item.setAttribute('data-search', `${dev.brand || ''} ${dev.series || ''} ${dev.id || ''} ${typeName}`.toLowerCase());

            item.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="device-icon-wrapper p-2 bg-light rounded-3 me-3 text-secondary fs-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; min-width: 48px;">
                        ${iconHtml}
                    </div>
                    <div>
                        <div class="fw-bold text-dark">${dev.brand || 'Perangkat'} - ${dev.series || ''}</div>
                        <small class="text-muted">Kode BMN: <span class="fw-semibold">${dev.id}</span> | S/N: ${dev.serial_number || '-'}</small>
                        <div class="mt-1">
                            <span class="badge bg-light border border-primary-subtle small py-1 px-2 me-1" style="color: #000 !important;">${typeName}</span>
                            <span class="badge bg-${condClass} py-1 px-2 small">${condName}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-sm ${isSelected ? 'btn-primary' : 'btn-outline-primary'} select-device-btn">
                        <i class="bi ${isSelected ? 'bi-check-circle-fill' : 'bi-plus-circle'} me-1"></i> ${isSelected ? 'Terpilih' : 'Pilih'}
                    </button>
                </div>
            `;

            item.addEventListener('click', function() {
                selectDevice(dev, typeName, iconHtml);
            });

            devicesContainer.appendChild(item);
        });
    }

    function selectDevice(dev, typeName, iconHtml) {
        selectedDeviceIdInput.value = dev.id;
        
        // Update Preview
        previewTitle.innerText = `${dev.brand || 'Perangkat'} - ${dev.series || ''}`;
        previewBmn.innerText = `Kode BMN: ${dev.id} | S/N: ${dev.serial_number || '-'}`;
        previewType.innerText = typeName;
        previewIcon.innerHTML = iconHtml;
        previewBox.classList.remove('d-none');

        // Re-render list highlights
        document.querySelectorAll('.device-item').forEach(el => {
            const isCurrent = (el.getAttribute('data-device-id') == dev.id);
            el.classList.toggle('border-primary', isCurrent);
            el.classList.toggle('shadow-sm', isCurrent);
            el.classList.toggle('bg-primary-subtle', isCurrent);
            const btn = el.querySelector('.select-device-btn');
            if (btn) {
                btn.className = `btn btn-sm ${isCurrent ? 'btn-primary' : 'btn-outline-primary'} select-device-btn`;
                btn.innerHTML = `<i class="bi ${isCurrent ? 'bi-check-circle-fill' : 'bi-plus-circle'} me-1"></i> ${isCurrent ? 'Terpilih' : 'Pilih'}`;
            }
        });

        checkFormValidity();
    }

    roomSelector.addEventListener('change', function() {
        renderDevices(this.value);
    });

    filterInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        document.querySelectorAll('.device-item').forEach(item => {
            const text = item.getAttribute('data-search') || '';
            if (text.includes(query)) {
                item.style.setProperty('display', 'flex', 'important');
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        });
    });

    // Auto-select if pre-filled in query string
    if (preselectedRoomId) {
        roomSelector.value = preselectedRoomId;
        renderDevices(preselectedRoomId);

        if (preselectedDeviceId) {
            const dev = currentRoomDevices.find(d => d.id == preselectedDeviceId);
            if (dev) {
                const typeName = dev.type ? dev.type.jenis : 'Lainnya';
                selectDevice(dev, typeName, '<i class="bi bi-cpu-fill"></i>');
            }
        }
    }
});
</script>
@endsection
