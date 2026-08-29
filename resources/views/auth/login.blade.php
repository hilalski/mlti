<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Masuk | MLTI-Report</title>
  <meta content="Masuk ke portal pelaporan perangkat TI" name="description">

  <!-- Favicons -->
  <link href="{{ asset('favicon.ico') }}" rel="icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

  <style>
    body {
      font-family: 'Plus Jakarta Sans', 'Nunito', sans-serif;
      background-color: #f6f9fc;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow-x: hidden;
      overflow-y: auto;
      padding: 30px 10px;
    }

    /* Abstract soft blurred blobs background */
    .bg-blob-1 {
      position: fixed;
      width: 500px;
      height: 500px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255, 132, 186, 0.35) 0%, rgba(255, 132, 186, 0) 70%);
      top: -10%;
      left: -10%;
      z-index: 0;
      filter: blur(80px);
      animation: float-blob-1 25s ease-in-out infinite alternate;
    }

    .bg-blob-2 {
      position: fixed;
      width: 550px;
      height: 550px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(153, 194, 255, 0.4) 0%, rgba(153, 194, 255, 0) 70%);
      bottom: -15%;
      right: -10%;
      z-index: 0;
      filter: blur(90px);
      animation: float-blob-2 30s ease-in-out infinite alternate;
    }

    .bg-blob-3 {
      position: fixed;
      width: 400px;
      height: 400px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255, 132, 186, 0.2) 0%, rgba(255, 132, 186, 0) 75%);
      top: 50%;
      left: 60%;
      z-index: 0;
      filter: blur(70px);
      animation: float-blob-3 22s ease-in-out infinite alternate;
    }

    @keyframes float-blob-1 {
      0% { transform: translate(0, 0) scale(1); }
      50% { transform: translate(60px, 40px) scale(1.05); }
      100% { transform: translate(-20px, 60px) scale(0.95); }
    }

    @keyframes float-blob-2 {
      0% { transform: translate(0, 0) scale(1); }
      50% { transform: translate(-50px, -60px) scale(0.95); }
      100% { transform: translate(40px, -30px) scale(1.05); }
    }

    @keyframes float-blob-3 {
      0% { transform: translate(0, 0) scale(1); }
      50% { transform: translate(30px, -20px) scale(1.1); }
      100% { transform: translate(-30px, 30px) scale(0.95); }
    }

    .login-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 490px;
      padding: 20px;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.75);
      backdrop-filter: blur(35px) saturate(180%);
      -webkit-backdrop-filter: blur(35px) saturate(180%);
      border: 1px solid rgba(255, 255, 255, 0.6);
      border-radius: 24px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.06), 0 0 40px rgba(153, 194, 255, 0.12);
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-card:hover {
      box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.08), 0 0 50px rgba(255, 132, 186, 0.15);
    }

    .login-card-header {
      padding: 40px 30px 20px 30px;
      text-align: center;
      background: transparent;
      border-bottom: none;
    }

    .logo-title {
      font-size: 2.3rem;
      font-weight: 800;
      letter-spacing: -0.5px;
      background: linear-gradient(135deg, #FF84BA 0%, #99C2FF 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 6px;
    }

    .logo-subtitle {
      font-size: 0.8rem;
      font-weight: 700;
      letter-spacing: 2px;
      color: #718096;
      text-transform: uppercase;
      margin-bottom: 0;
    }

    .scanner-outer-wrapper {
      padding: 10px;
      background: rgba(255, 255, 255, 0.4);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.7);
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);
    }

    .scanner-wrapper {
      position: relative;
      border-radius: 14px;
      overflow: hidden;
      border: 1px solid rgba(255, 132, 186, 0.2);
      box-shadow: 0 10px 30px rgba(153, 194, 255, 0.05);
      transition: all 0.4s ease;
    }

    .scanner-wrapper.scanning {
      border-color: #FF84BA;
      box-shadow: 0 0 20px rgba(255, 132, 186, 0.2);
    }

    .scan-line {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, transparent, #FF84BA 30%, #99C2FF 70%, transparent);
      z-index: 100;
      animation: scan 3s ease-in-out infinite;
      box-shadow: 0 0 10px rgba(255, 132, 186, 0.4);
    }

    @keyframes scan {
      0% { top: 0%; }
      50% { top: 100%; }
      100% { top: 0%; }
    }

    .scanner-corner {
      position: absolute;
      width: 20px;
      height: 20px;
      border: 2px solid transparent;
      z-index: 99;
      pointer-events: none;
    }
    .corner-tl { top: 10px; left: 10px; border-top-color: #FF84BA; border-left-color: #FF84BA; border-top-left-radius: 6px; }
    .corner-tr { top: 10px; right: 10px; border-top-color: #99C2FF; border-right-color: #99C2FF; border-top-right-radius: 6px; }
    .corner-bl { bottom: 10px; left: 10px; border-bottom-color: #99C2FF; border-left-color: #99C2FF; border-bottom-left-radius: 6px; }
    .corner-br { bottom: 10px; right: 10px; border-bottom-color: #FF84BA; border-right-color: #FF84BA; border-bottom-right-radius: 6px; }

    .accordion-item {
      background: rgba(255, 255, 255, 0.4);
      border: 1px solid rgba(255, 255, 255, 0.6);
      border-radius: 14px !important;
      overflow: hidden;
      transition: all 0.3s ease;
    }
    
    .accordion-item:hover {
      background: rgba(255, 255, 255, 0.6);
    }

    .accordion-button {
      background: transparent !important;
      color: #4a5568 !important;
      box-shadow: none !important;
      font-size: 0.85rem;
      padding: 16px 20px;
      border: none;
      transition: color 0.3s ease;
    }

    .accordion-button:not(.collapsed) {
      color: #FF84BA !important;
      font-weight: 600;
    }

    .accordion-button::after {
      background-size: 0.85rem;
      filter: grayscale(1) opacity(0.6);
      transition: transform 0.2s ease;
    }

    .accordion-button:not(.collapsed)::after {
      filter: none;
    }

    .accordion-body {
      background: rgba(255, 255, 255, 0.3);
      border-top: 1px solid rgba(255, 255, 255, 0.5);
      padding: 20px;
    }

    .form-label {
      font-weight: 600;
      color: #4a5568;
      font-size: 0.8rem;
      margin-bottom: 6px;
      letter-spacing: 0.2px;
    }

    .input-group {
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid rgba(0, 0, 0, 0.08);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01);
      transition: all 0.3s ease;
    }

    .input-group:focus-within {
      border-color: #99C2FF;
      box-shadow: 0 0 0 4px rgba(153, 194, 255, 0.2);
    }

    .input-group-text {
      background-color: #fff !important;
      border: none;
      color: #a0aec0;
      padding-left: 16px;
    }

    .form-control {
      border: none !important;
      padding: 12px 16px 12px 8px;
      font-size: 0.9rem;
      font-weight: 500;
      color: #2d3748;
      background-color: #fff !important;
    }

    .form-control:focus {
      box-shadow: none !important;
    }

    .form-control::placeholder {
      color: #cbd5e0;
    }

    .btn-manual-verify {
      background: linear-gradient(135deg, #FF84BA 0%, #99C2FF 100%);
      border: none;
      border-radius: 12px;
      color: white;
      font-weight: 600;
      padding: 12px;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      box-shadow: 0 4px 15px rgba(255, 132, 186, 0.25);
    }

    .btn-manual-verify:hover {
      background: linear-gradient(135deg, #FF6EA7 0%, #76A6FF 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(255, 132, 186, 0.35);
      color: white;
    }

    .btn-manual-verify:active {
      transform: translateY(1px);
    }

    .alert {
      border-radius: 14px;
      border: none;
      font-size: 0.85rem;
      padding: 12px 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.01);
    }
    
    .alert-info {
      background-color: rgba(153, 194, 255, 0.15) !important;
      color: #2b6cb0;
    }

    .alert-success {
      background-color: rgba(72, 187, 120, 0.15) !important;
      color: #22543d;
    }

    .alert-danger {
      background-color: rgba(245, 101, 101, 0.15) !important;
      color: #742a2a;
    }

    #reader {
      border: none !important;
      background: #fafbfe !important;
    }
    
    #reader__dashboard {
      padding: 15px !important;
      background: #fafbfe !important;
      border-top: 1px solid rgba(0,0,0,0.03) !important;
    }

    #reader__camera_selection {
      border-radius: 8px;
      border: 1px solid rgba(0,0,0,0.08);
      padding: 6px 12px;
      font-size: 0.8rem;
      background-color: #fff;
      color: #4a5568;
    }

    #reader__dashboard_section_csr button {
      background: linear-gradient(135deg, #FF84BA 0%, #99C2FF 100%);
      border: none;
      border-radius: 8px;
      color: white;
      font-size: 0.8rem;
      font-weight: 600;
      padding: 8px 16px;
      transition: all 0.2s ease;
      box-shadow: 0 4px 10px rgba(255, 132, 186, 0.2);
    }

    #reader__dashboard_section_csr button:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 12px rgba(255, 132, 186, 0.3);
    }
    
    #reader video {
      object-fit: cover !important;
      border-radius: 14px;
      display: block;
      width: 100%;
      min-height: 250px;
      background: #101828;
    }

    .scanner-actions {
      padding: 12px 10px 0;
    }

    .camera-focus-controls {
      padding: 12px 10px 2px;
    }

    .btn-focus {
      border: 1px solid rgba(153, 194, 255, 0.75);
      border-radius: 8px;
      background: #fff;
      color: #4a6fa5;
      font-size: 0.78rem;
      font-weight: 600;
      padding: 7px 10px;
    }

    .btn-focus:hover:not(:disabled) {
      background: #eef5ff;
      color: #31598d;
    }

    .focus-status {
      color: #718096;
      font-size: 0.72rem;
      text-align: right;
    }

    .manual-focus-control {
      margin-top: 10px;
    }

    .manual-focus-control .form-label {
      font-size: 0.72rem;
    }

    .footer-text {
      color: #a0aec0;
      font-size: 0.75rem;
      font-weight: 500;
      margin-top: 24px;
      letter-spacing: 0.2px;
    }

    /* Responsiveness tweaks for smaller mobile screens and keyboard overlay compatibility */
    @media (max-width: 480px) {
      body {
        padding: 15px 5px;
      }
      .login-container {
        padding: 10px;
      }
      .login-card {
        border-radius: 20px;
      }
      .login-card-header {
        padding: 30px 20px 15px 20px;
      }
      .logo-title {
        font-size: 1.9rem;
      }
      .card-body {
        padding-left: 15px !important;
        padding-right: 15px !important;
        padding-bottom: 20px !important;
      }
      .scanner-outer-wrapper {
        padding: 6px;
        border-radius: 16px;
      }
      .scanner-wrapper {
        border-radius: 12px;
      }
      .scanner-corner {
        width: 15px;
        height: 15px;
      }
      .corner-tl { top: 6px; left: 6px; }
      .corner-tr { top: 6px; right: 6px; }
      .corner-bl { bottom: 6px; left: 6px; }
      .corner-br { bottom: 6px; right: 6px; }
    }
  </style>
</head>

<body>

  <div class="bg-blob-1"></div>
  <div class="bg-blob-2"></div>
  <div class="bg-blob-3"></div>

  <div class="login-container">

    <div class="card login-card">
      
      <!-- Modern Minimalist Header -->
      <div class="login-card-header">
        <h1 class="logo-title">MLTI</h1>
        <p class="logo-subtitle">BPS Provinsi Jambi</p>
      </div>

      <div class="card-body px-4 pb-4 pt-2">
        
        <div class="text-center mb-4">
          <h5 class="fw-bold text-dark mb-1">Masuk dengan QR Code</h5>
          <p class="text-muted small">Posisikan QR Code pegawai Anda pada kamera scanner</p>
        </div>

        <!-- QR Scanner Region -->
        <div class="mb-4 scanner-outer-wrapper">
          <div class="position-relative">
            <div class="scan-line"></div>
            <div class="scanner-corner corner-tl"></div>
            <div class="scanner-corner corner-tr"></div>
            <div class="scanner-corner corner-bl"></div>
            <div class="scanner-corner corner-br"></div>
            <div class="scanner-wrapper">
              <div id="reader" style="width: 100%;"></div>
            </div>
            <div class="scanner-actions">
              <button id="scanner-toggle" type="button" class="btn btn-manual-verify w-100 py-2">
                <i class="bi bi-camera me-1"></i> Mulai Scanner
              </button>
            </div>
            <!-- <div id="camera-focus-controls" class="camera-focus-controls d-none" aria-live="polite">
              <div class="d-flex align-items-center justify-content-between gap-2">
                <button id="auto-focus-button" type="button" class="btn btn-focus">
                  <i class="bi bi-bullseye me-1"></i> Fokus otomatis
                </button>
                <small id="focus-status" class="focus-status">Kamera siap</small>
              </div>
              <div id="manual-focus-control" class="manual-focus-control d-none">
                <label for="focus-distance" class="form-label mb-1">Atur fokus</label>
                <input id="focus-distance" type="range" class="form-range" aria-label="Atur fokus kamera">
              </div>
            </div> -->
          </div>
        </div>

        <!-- Alert messages -->
        <div id="scan-message" class="alert alert-info text-center d-none transition" role="alert">
          <div class="d-flex align-items-center justify-content-center">
            <span class="spinner-border spinner-border-sm me-2 d-none" id="scan-spinner" role="status"></span>
            <span id="scan-message-text" class="fw-bold small">Memproses data QR...</span>
          </div>
        </div>

        <!-- Manual Login Entry -->
        <div class="accordion border-0 mt-4" id="manualLoginAccordion">
          <div class="accordion-item border-0">
            <h2 class="accordion-header" id="headingManual">
              <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseManual" aria-expanded="false" aria-controls="collapseManual">
                <i class="bi bi-keyboard me-2 fs-5"></i> Masukkan NIP Lama Secara Manual
              </button>
            </h2>
            <div id="collapseManual" class="accordion-collapse collapse" aria-labelledby="headingManual" data-bs-parent="#manualLoginAccordion">
              <div class="accordion-body">
                <form id="manual-login-form">
                  <div class="mb-3">
                    <label for="manual_nip" class="form-label">NIP Lama Pegawai</label>
                    <div class="input-group">
                      <span class="input-group-text bg-white"><i class="bi bi-person text-secondary"></i></span>
                      <input type="text" class="form-control" id="manual_nip" placeholder="Contoh: 340014249" required>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-manual-verify w-100 py-2.5">
                    <i class="bi bi-shield-check me-1"></i> Masuk
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="text-center footer-text">
      &copy; Tim IPDS BPS Provinsi Jambi {{ date('Y') }}
    </div>

  </div>

  <!-- JS Files -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- Fast QR scanner engine (uses Web Worker / Barcode Detector when available) -->
  <script src="https://unpkg.com/qr-scanner@1.4.2/qr-scanner.umd.min.js" type="text/javascript"></script>

  <script>
    const messageDiv = document.getElementById('scan-message');
    const messageText = document.getElementById('scan-message-text');
    const spinner = document.getElementById('scan-spinner');
    const reader = document.getElementById('reader');
    const scannerToggle = document.getElementById('scanner-toggle');
    const focusControls = document.getElementById('camera-focus-controls');
    const autoFocusButton = document.getElementById('auto-focus-button');
    const focusStatus = document.getElementById('focus-status');
    const manualFocusControl = document.getElementById('manual-focus-control');
    const focusDistanceInput = document.getElementById('focus-distance');
    let activeCameraTrack = null;
    let activeCameraCapabilities = null;
    let scannerRunning = false;
    let scannerTransitioning = false;
    let isVerifying = false;
    let focusDistanceTimer = null;

    reader.innerHTML = '<video id="qr-video" autoplay muted playsinline></video>';
    const qrVideo = document.getElementById('qr-video');

    function resetFocusControls() {
      activeCameraTrack = null;
      activeCameraCapabilities = null;
      focusControls.classList.add('d-none');
      manualFocusControl.classList.add('d-none');
    }

    function getFocusModeConstraint(capabilities) {
      const modes = capabilities.focusMode || [];
      return ['continuous', 'single-shot', 'auto'].find(mode => modes.includes(mode));
    }

    async function requestAutomaticFocus() {
      const focusMode = activeCameraCapabilities && getFocusModeConstraint(activeCameraCapabilities);
      if (!activeCameraTrack || !focusMode) return;

      autoFocusButton.disabled = true;
      focusStatus.textContent = 'Sedang memfokuskan...';
      try {
        await activeCameraTrack.applyConstraints({ advanced: [{ focusMode }] });
        focusStatus.textContent = 'Fokus otomatis aktif';
      } catch (error) {
        focusStatus.textContent = 'Fokus tidak dapat diatur';
      } finally {
        autoFocusButton.disabled = false;
      }
    }

    async function applyManualFocusDistance() {
      if (!activeCameraTrack || !activeCameraCapabilities) return;
      const constraint = { focusDistance: Number(focusDistanceInput.value) };
      if ((activeCameraCapabilities.focusMode || []).includes('manual')) constraint.focusMode = 'manual';

      try {
        await activeCameraTrack.applyConstraints({ advanced: [constraint] });
        focusStatus.textContent = 'Fokus manual diterapkan';
      } catch (error) {
        focusStatus.textContent = 'Fokus manual tidak tersedia';
      }
    }

    async function prepareFocusControls() {
      const stream = qrVideo.srcObject;
      const track = stream && stream.getVideoTracks()[0];
      if (!track || typeof track.getCapabilities !== 'function') return;

      const capabilities = track.getCapabilities();
      const automaticFocus = getFocusModeConstraint(capabilities);
      const focusDistance = capabilities.focusDistance;
      if (!automaticFocus && !focusDistance) return;

      activeCameraTrack = track;
      activeCameraCapabilities = capabilities;
      focusControls.classList.remove('d-none');
      autoFocusButton.classList.toggle('d-none', !automaticFocus);

      if (focusDistance) {
        const settings = track.getSettings();
        focusDistanceInput.min = focusDistance.min;
        focusDistanceInput.max = focusDistance.max;
        focusDistanceInput.step = focusDistance.step || 0.01;
        focusDistanceInput.value = settings.focusDistance ?? focusDistance.min;
        manualFocusControl.classList.remove('d-none');
      } else {
        manualFocusControl.classList.add('d-none');
      }

      focusStatus.textContent = automaticFocus ? 'Fokus kontinu aktif untuk QR jarak dekat' : 'Geser untuk mengatur fokus';
      if (automaticFocus) await requestAutomaticFocus();
    }

    function showCameraAccessError(error) {
      messageDiv.classList.remove('d-none', 'alert-info', 'alert-success');
      messageDiv.classList.add('alert-danger');
      spinner.classList.add('d-none');

      if (['NotAllowedError', 'SecurityError', 'PermissionDeniedError'].includes(error && error.name)) {
        messageText.innerText = 'Kamera tidak dapat diakses. Periksa izin kamera Anda.';
      } else if (error && error.name === 'NotFoundError') {
        messageText.innerText = 'Kamera tidak ditemukan pada perangkat ini.';
      } else {
        messageText.innerText = 'Kamera gagal dinyalakan. Coba lagi atau pilih perangkat lain.';
      }
    }

    function setScannerButton(isRunning, isBusy = false) {
      scannerToggle.disabled = isBusy;
      scannerToggle.innerHTML = isBusy
        ? '<span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span> Memproses...'
        : isRunning
          ? '<i class="bi bi-stop-circle me-1"></i> Stop Scanning'
          : '<i class="bi bi-camera me-1"></i> Mulai Scanner';
    }

    async function stopScanner() {
      if (!scannerRunning || scannerTransitioning) return;
      scannerTransitioning = true;
      setScannerButton(true, true);
      try {
        await qrScanner.stop();
      } finally {
        scannerRunning = false;
        scannerTransitioning = false;
        resetFocusControls();
        setScannerButton(false);
      }
    }

    async function startScanner() {
      if (scannerRunning || scannerTransitioning) return;
      scannerTransitioning = true;
      setScannerButton(false, true);
      messageDiv.classList.remove('d-none', 'alert-danger', 'alert-success');
      messageDiv.classList.add('alert-info');
      spinner.classList.remove('d-none');
      messageText.innerText = 'Meminta izin akses kamera...';
      try {
        // start() memanggil getUserMedia di dalam klik pengguna ini, sehingga browser meminta izin kamera.
        await qrScanner.start();
        scannerRunning = true;
        await prepareFocusControls();
        spinner.classList.add('d-none');
        messageDiv.classList.add('d-none');
      } catch (error) {
        scannerRunning = false;
        showCameraAccessError(error);
      } finally {
        scannerTransitioning = false;
        setScannerButton(scannerRunning);
      }
    }

    function toggleScanner() {
      return scannerRunning ? stopScanner() : startScanner();
    }

    function onScanSuccess(decodedText) {
      if (isVerifying || scannerTransitioning || !scannerRunning) return;
      isVerifying = true;
      stopScanner().finally(() => verifyNip(decodedText));
    }

    const qrScanner = new QrScanner(
      qrVideo,
      result => onScanSuccess(result.data),
      {
        preferredCamera: 'environment',
        maxScansPerSecond: 25,
        highlightScanRegion: false,
        highlightCodeOutline: false,
        returnDetailedScanResult: true,
      }
    );
    scannerToggle.addEventListener('click', toggleScanner);
    autoFocusButton.addEventListener('click', requestAutomaticFocus);
    focusDistanceInput.addEventListener('input', () => {
      clearTimeout(focusDistanceTimer);
      focusDistanceTimer = setTimeout(applyManualFocusDistance, 120);
    });

    // Manual Input Form
    document.getElementById('manual-login-form').addEventListener('submit', function(e) {
      e.preventDefault();
      const nip = document.getElementById('manual_nip').value.trim();
      if (nip) {
        verifyNip(nip);
      }
    });

    function verifyNip(nipString) {
      messageDiv.classList.remove('d-none', 'alert-danger', 'alert-info', 'alert-success');
      messageDiv.classList.add('alert-info');
      spinner.classList.remove('d-none');
      messageText.innerText = "Memverifikasi NIP Pegawai...";

      fetch("{{ route('login.verify') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ qr_string: nipString })
      })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => { throw err; });
        }
        return res.json();
      })
      .then(data => {
        if (data.success) {
          spinner.classList.add('d-none');
          messageDiv.classList.remove('alert-info');
          messageDiv.classList.add('alert-success');
          messageText.innerText = "Berhasil! Mengalihkan ke dashboard...";
          setTimeout(() => {
            window.location.href = data.redirect;
          }, 800);
        }
      })
      .catch(err => {
        spinner.classList.add('d-none');
        messageDiv.classList.remove('alert-info');
        messageDiv.classList.add('alert-danger');
        messageText.innerText = err.message || "Pegawai tidak terdaftar atau kode salah.";
        
        // Re-enable scanning after 3.5 seconds
        setTimeout(() => {
          messageDiv.classList.add('d-none');
          isVerifying = false;
          startScanner();
        }, 3500);
      });
    }
  </script>

</body>

</html>
