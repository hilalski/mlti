<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>@yield('title', 'Sistem Pelaporan Kerusakan TI')</title>
  <meta content="Sistem Pelaporan Kerusakan TI dan Monitoring Perbaikan" name="description">
  <meta content="ti, kerusakan, BPS, jarkom" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('favicon.ico') }}" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
  <!-- Custom Overrides CSS File -->
  <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

  @yield('styles')
</head>

<body class="d-flex flex-column min-vh-100">

  <!-- Include Header navbar -->
  @include('layouts.partials.header')

  <!-- Include Sidebar -->
  @include('layouts.partials.sidebar')

  <!-- Main Content -->
  <main id="main" class="main flex-grow-1">
    
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-left: 4px solid #198754 !important;">
        <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-left: 4px solid #dc3545 !important;">
        <i class="bi bi-exclamation-octagon-fill me-2 fs-5 text-danger"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @yield('content')
  </main>

  <!-- Include Footer -->
  @include('layouts.partials.footer')

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <!-- Custom Modern Confirmation Modal -->
  <div id="custom-confirm-modal" class="custom-confirm-overlay" style="display: none;">
    <div class="custom-confirm-box">
      <div class="custom-confirm-icon">
        <i class="bi bi-exclamation-triangle-fill"></i>
      </div>
      <h5 class="custom-confirm-title">Konfirmasi Tindakan</h5>
      <p id="custom-confirm-message" class="custom-confirm-text">Apakah Anda yakin?</p>
      <div class="custom-confirm-buttons">
        <button id="custom-confirm-cancel-btn" class="btn-confirm-secondary">Batal</button>
        <button id="custom-confirm-ok-btn" class="btn-confirm-primary">Lanjutkan</button>
      </div>
    </div>
  </div>

  <script>
    // Global Confirmation Modal Functions
    function showCustomConfirm(message, callback) {
      const modal = document.getElementById('custom-confirm-modal');
      const messageEl = document.getElementById('custom-confirm-message');
      const okBtn = document.getElementById('custom-confirm-ok-btn');
      const cancelBtn = document.getElementById('custom-confirm-cancel-btn');
      
      if (!modal || !messageEl || !okBtn || !cancelBtn) return;
      
      messageEl.textContent = message;
      modal.style.display = 'flex';
      
      // Force reflow and add class for transitions
      setTimeout(() => {
        modal.classList.add('show');
      }, 10);
      
      // Clean up previous event listeners
      const newOkBtn = okBtn.cloneNode(true);
      const newCancelBtn = cancelBtn.cloneNode(true);
      okBtn.parentNode.replaceChild(newOkBtn, okBtn);
      cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
      
      newOkBtn.addEventListener('click', function() {
        closeCustomConfirm();
        if (callback) callback();
      });
      
      newCancelBtn.addEventListener('click', function() {
        closeCustomConfirm();
      });
      
      // Click outside overlay to close
      modal.onclick = function(e) {
        if (e.target === modal) {
          closeCustomConfirm();
        }
      };
    }

    function closeCustomConfirm() {
      const modal = document.getElementById('custom-confirm-modal');
      if (!modal) return;
      
      modal.classList.remove('show');
      setTimeout(() => {
        modal.style.display = 'none';
      }, 250);
    }

    // Automatically hijack all forms with standard onsubmit="return confirm('...')"
    document.addEventListener('DOMContentLoaded', function() {
      const bindConfirmForms = () => {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
          const onsubmitAttr = form.getAttribute('onsubmit');
          if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
            let message = "Apakah Anda yakin?";
            const match = onsubmitAttr.match(/confirm\(['"](.+?)['"]\)/);
            if (match && match[1]) {
              message = match[1];
            }
            
            // Remove the inline handler to prevent browser confirm popups
            form.removeAttribute('onsubmit');
            
            // Register custom submit listener
            form.addEventListener('submit', function(e) {
              if (form.dataset.confirmed === 'true') {
                return;
              }
              e.preventDefault();
              
              showCustomConfirm(message, function() {
                form.dataset.confirmed = 'true';
                form.submit();
              });
            });
          }
        });
      };
      
      bindConfirmForms();
      
      // Handle dynamic AJAX loads if any
      if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver(bindConfirmForms);
        observer.observe(document.body, { childList: true, subtree: true });
      }
    });
  </script>

  @yield('scripts')
</body>

</html>
