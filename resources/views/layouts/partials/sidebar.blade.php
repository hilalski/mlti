<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar shadow-sm">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-heading text-secondary mb-2 small fw-bold">Pengguna</li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <i class="bi bi-grid-fill"></i>
        <span>Perangkat Saya</span>
      </a>
    </li>

    @auth
      @if(auth()->user()->is_jarkom == 1)
        <li class="nav-heading text-secondary my-3 small fw-bold">Tim Jarkom</li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.reports.*') ? '' : 'collapsed' }}" data-bs-target="#reports-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-file-earmark-text-fill"></i><span>Laporan Masuk</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="reports-nav" class="nav-content collapse {{ request()->routeIs('admin.reports.*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.index') && !request()->filled('status') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Semua Laporan</span>
              </a>
            </li>
            <li>
              <a href="{{ route('admin.reports.index', ['status' => 'menunggu']) }}" class="{{ request('status') === 'menunggu' ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Laporan Baru</span>
              </a>
            </li>
            <li>
              <a href="{{ route('admin.reports.index', ['status' => 'diproses']) }}" class="{{ request('status') === 'diproses' ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Dalam Proses</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}" href="{{ route('admin.notifications.index') }}">
            <i class="bi bi-bell-fill"></i>
            <span>Notifikasi</span>
          </a>
        </li>
      @endif
    @endauth

  </ul>

</aside><!-- End Sidebar-->
