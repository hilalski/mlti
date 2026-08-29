<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
      <span class="d-none d-lg-block text-white" style="color: var(--color-accent) !important; font-size: 1.6rem; font-weight: 800; letter-spacing: 0.5px;">MLTI</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn text-white"></i>
  </div><!-- End Logo -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

      @auth
        @if(auth()->user()->is_jarkom == 1)
          <!-- ======= Notifications Dropdown ======= -->
          <li class="nav-item dropdown">
            <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
              <i class="bi bi-bell text-white"></i>
              @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="badge bg-danger badge-number" style="font-size: 0.65rem; padding: 3px 5px;">{{ auth()->user()->unreadNotifications->count() }}</span>
              @endif
            </a><!-- End Notification Icon -->

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications border-0 shadow-lg">
              <li class="dropdown-header py-3 bg-light text-dark">
                <span class="fw-bold">Notifikasi Laporan Baru</span>
                @if(auth()->user()->unreadNotifications->count() > 0)
                  <a href="{{ route('admin.notifications.index') }}"><span class="badge rounded-pill bg-primary p-2 ms-2">Lihat Semua</span></a>
                @endif
              </li>
              <li>
                <hr class="dropdown-divider my-0">
              </li>

              @forelse(auth()->user()->unreadNotifications->take(4) as $notification)
                <li class="notification-item py-3 px-4 hover-bg-light transition">
                  <div class="d-flex align-items-start">
                    <i class="bi bi-exclamation-circle text-warning fs-5 me-3"></i>
                    <div>
                      <h4 class="fs-6 fw-bold mb-1 text-dark">Laporan {{ ucfirst($notification->data['issue_type'] ?? 'Kerusakan') }}</h4>
                      <p class="text-muted mb-1 small">{{ Str::limit($notification->data['description'] ?? '', 45) }}</p>
                      <p class="text-secondary mb-0" style="font-size: 0.75rem;">
                        <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($notification->data['reported_at'] ?? now())->diffForHumans() }}
                      </p>
                    </div>
                  </div>
                </li>
                <li>
                  <hr class="dropdown-divider my-0">
                </li>
              @empty
                <li class="notification-item justify-content-center py-4">
                  <p class="text-muted text-center mb-0">Tidak ada notifikasi baru</p>
                </li>
              @endforelse

              <li class="dropdown-footer py-2 text-center">
                <a href="{{ route('admin.notifications.index') }}" class="small text-primary fw-bold">Tampilkan semua notifikasi</a>
              </li>
            </ul><!-- End Notification Dropdown Items -->
          </li><!-- End Notification Nav -->
        @endif

        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <div class="d-flex align-items-center justify-content-center bg-white rounded-circle text-primary fw-bold shadow-sm" style="width: 36px; height: 36px; border: 2px solid var(--color-accent);">
              {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <span class="d-none d-md-block dropdown-toggle ps-2 text-white fw-bold">{{ auth()->user()->name }}</span>
          </a><!-- End Profile Image Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile border-0 shadow-lg" style="min-width: 260px;">
            <li class="dropdown-header py-3 bg-light text-dark text-start rounded-top">
              <h6 class="fw-bold mb-2" style="font-size: 0.95rem; color: #99C2FF;">{{ auth()->user()->name }}</h6>
              <div class="d-flex flex-column gap-1 small text-muted" style="font-size: 0.8rem;">
                <div><span class="fw-bold" style="color: #FF84BA;">{{ auth()->user()->nip_lama }}</span></div>
                <div><span class="fw-bold" style="color: #99C2FF;">{{ auth()->user()->jabatan ?: '-' }}</span></div>
              </div>
            </li>
            <li>
              <hr class="dropdown-divider my-0">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center py-2 text-danger fw-bold" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right me-2 fs-5"></i>
                <span>Keluar</span>
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </li>
          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->
      @endauth

    </ul>
  </nav><!-- End Navigation -->

</header><!-- End Header -->
