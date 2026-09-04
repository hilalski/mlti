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

    <!-- <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('report.open-ticket*') ? 'active' : '' }}" href="{{ route('report.open-ticket') }}">
        <i class="bi bi-ticket-detailed-fill"></i>
        <span>Open Ticket</span>
      </a>
    </li> -->

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('reports.history*') ? 'active' : '' }}" href="{{ route('reports.history') }}">
        <i class="bi bi-journal-text"></i>
        <span>Riwayat Laporan</span>
      </a>
    </li>

    @auth
      @if(auth()->user()->is_jarkom == 1)
        <li class="nav-heading text-secondary my-3 small fw-bold">Tim Jarkom</li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.reports.*') ? '' : 'collapsed' }}" data-bs-target="#reports-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-file-earmark-text-fill"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
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
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.devices.*') ? '' : 'collapsed' }}" data-bs-target="#mgmt-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-gear-fill"></i><span>Manajemen</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="mgmt-nav" class="nav-content collapse {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.devices.*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Akun</span>
              </a>
            </li>
            <li>
              <a href="{{ route('admin.devices.index') }}" class="{{ request()->routeIs('admin.devices.*') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Perangkat</span>
              </a>
            </li>
          </ul>
        </li>
      @endif
    @endauth

  </ul>

</aside><!-- End Sidebar-->
