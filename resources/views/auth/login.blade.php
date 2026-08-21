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
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #000957 0%, #172554 50%, #0c1236 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow-x: hidden;
    }

    /* Abstract shapes background */
    .bg-circle-1 {
      position: absolute;
      width: 500px;
      height: 500px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(52, 76, 183, 0.15) 0%, rgba(52, 76, 183, 0) 70%);
      top: -10%;
      left: -10%;
      z-index: 0;
    }

    .bg-circle-2 {
      position: absolute;
      width: 600px;
      height: 600px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255, 235, 0, 0.05) 0%, rgba(255, 235, 0, 0) 70%);
      bottom: -15%;
      right: -10%;
      z-index: 0;
    }

    .login-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 520px;
      padding: 15px;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(15px);
      border: none;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .login-card-header {
      background-color: var(--color-primary);
      padding: 30px 20px;
      text-align: center;
      border-bottom: 5px solid var(--color-accent);
      position: relative;
    }

    .login-card-header::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 100%;
      height: 5px;
      background: linear-gradient(90deg, var(--color-accent) 0%, #ffd700 100%);
    }

    .logo-title {
      font-size: 2.2rem;
      font-weight: 850;
      letter-spacing: 1px;
      background: linear-gradient(135deg, #ffffff 0%, var(--color-accent) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 5px;
    }

    .scanner-wrapper {
      position: relative;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 24px rgba(0, 9, 87, 0.15);
      border: 3px solid var(--color-primary);
      transition: border-color 0.3s ease;
    }

    .scanner-wrapper.scanning {
      border-color: var(--color-secondary);
    }

    /* Laser scan line animation */
    .scan-line {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, transparent, var(--color-accent), transparent);
      z-index: 100;
      animation: scan 2s linear infinite;
      box-shadow: 0 0 8px var(--color-accent);
    }

    @keyframes scan {
      0% { top: 0%; }
      50% { top: 100%; }
      100% { top: 0%; }
    }

    .accordion-button:not(.collapsed) {
      background-color: #f0f4ff;
      color: var(--color-secondary);
    }

    .form-control:focus {
      border-color: var(--color-secondary);
      box-shadow: 0 0 0 0.25rem rgba(52, 76, 183, 0.25);
    }

    .btn-manual-verify {
      background-color: var(--color-secondary);
      border: none;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .btn-manual-verify:hover {
      background-color: var(--color-primary);
      color: var(--color-accent);
    }
  </style>
</head>

<body>

  <div class="bg-circle-1"></div>
  <div class="bg-circle-2"></div>

  <div class="login-container">

    <div class="card login-card">
      
      <!-- Premium Header -->
      <div class="login-card-header">
        <h1 class="logo-title">MLTI-REPORT</h1>
        <p class="text-white-50 mb-0 small uppercase fw-bold" style="letter-spacing: 2px;">BPS Provinsi Jambi</p>
      </div>

      <div class="card-body p-4 pt-5">
        
        <div class="text-center mb-4">
          <h5 class="fw-bold text-dark mb-1">Masuk dengan QR Code</h5>
          <p class="text-muted small">Posisikan QR Code pegawai Anda pada kamera scanner</p>
        </div>

        <!-- QR Scanner Region -->
        <div class="mb-4 position-relative">
          <div class="scan-line"></div>
          <div class="scanner-wrapper">
            <div id="reader" style="width: 100%;"></div>
          </div>
        </div>

        <!-- Alert messages -->
        <div id="scan-message" class="alert alert-info text-center d-none border-0 shadow-sm transition" role="alert">
          <div class="d-flex align-items-center justify-content-center">
            <span class="spinner-border spinner-border-sm me-2 d-none" id="scan-spinner" role="status"></span>
            <span id="scan-message-text" class="fw-bold small">Memproses data QR...</span>
          </div>
        </div>

        <!-- Manual Login Entry -->
        <div class="accordion border-0 shadow-sm rounded-3 mt-4" id="manualLoginAccordion">
          <div class="accordion-item border-0">
            <h2 class="accordion-header" id="headingManual">
              <button class="accordion-button collapsed text-secondary fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseManual" aria-expanded="false" aria-controls="collapseManual" style="font-size: 0.85rem;">
                <i class="bi bi-keyboard me-2 fs-5"></i> Masukkan NIP Lama Secara Manual
              </button>
            </h2>
            <div id="collapseManual" class="accordion-collapse collapse" aria-labelledby="headingManual" data-bs-parent="#manualLoginAccordion">
              <div class="accordion-body bg-light">
                <form id="manual-login-form">
                  <div class="mb-3">
                    <label for="manual_nip" class="form-label small fw-bold text-dark">NIP Lama Pegawai</label>
                    <div class="input-group">
                      <span class="input-group-text bg-white"><i class="bi bi-person text-secondary"></i></span>
                      <input type="text" class="form-control" id="manual_nip" placeholder="Contoh: 340014249" required>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary btn-manual-verify w-100 py-2">
                    <i class="bi bi-shield-check me-1"></i> Verifikasi Pegawai
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="text-center mt-3 text-white-50 small">
      Sistem Pelaporan Kerusakan TI &copy; 2026. BPS Provinsi Jambi
    </div>

  </div>

  <!-- JS Files -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- HTML5 QR Code CDN -->
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

  <script>
    const messageDiv = document.getElementById('scan-message');
    const messageText = document.getElementById('scan-message-text');
    const spinner = document.getElementById('scan-spinner');

    function onScanSuccess(decodedText, decodedResult) {
      console.log(`Scan result: ${decodedText}`);
      
      // Stop scanner
      html5QrcodeScanner.clear();

      verifyNip(decodedText);
    }

    function onScanFailure(error) {
      // Ignored to avoid log spamming
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
      "reader", 
      { fps: 15, qrbox: { width: 250, height: 250 } },
      /* verbose= */ false
    );
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);

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
          html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }, 3500);
      });
    }
  </script>

</body>

</html>
