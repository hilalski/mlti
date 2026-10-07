<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar shadow-sm">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="sidebar-mobile-logo d-xl-none" style="height:52px;overflow:hidden"><a href="{{ route('dashboard') }}" aria-label="Beranda MLTI"><img src="{{ asset('assets/img/logo.svg') }}" alt="MLTI" style="width:108px;height:36px;object-fit:contain"></a></li>

    <li class="nav-heading text-secondary mb-2 small fw-bold">Pengguna</li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard*') || request()->routeIs('reports.history*') || (request()->routeIs('general.booking.*') && request()->route('type') === 'zoom') ? '' : 'collapsed' }}" data-bs-target="#it-nav" data-bs-toggle="collapse" href="#"><i class="bi bi-pc-display"></i><span>IT</span><i class="bi bi-chevron-down ms-auto"></i></a>
      <ul id="it-nav" class="nav-content collapse {{ request()->routeIs('dashboard*') || request()->routeIs('reports.history*') || (request()->routeIs('general.booking.*') && request()->route('type') === 'zoom') ? 'show' : '' }}" data-bs-parent="#sidebar-nav"><li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard*') ? 'active' : '' }}"><i class="bi bi-circle"></i><span>Perangkat TI</span></a></li><li><a href="{{ route('general.booking.form', 'zoom') }}"><i class="bi bi-circle"></i><span>Zoom</span></a></li></ul>
    </li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('general.sigap') || (request()->routeIs('general.booking.*') && request()->route('type') === 'room') ? '' : 'collapsed' }}" data-bs-target="#general-nav" data-bs-toggle="collapse" href="#"><i class="bi bi-building"></i><span>Umum</span><i class="bi bi-chevron-down ms-auto"></i></a>
      <ul id="general-nav" class="nav-content collapse {{ request()->routeIs('general.sigap') || (request()->routeIs('general.booking.*') && request()->route('type') === 'room') ? 'show' : '' }}" data-bs-parent="#sidebar-nav"><li><a href="{{ route('general.sigap') }}" class="{{ request()->routeIs('general.sigap') ? 'active' : '' }}"><i class="bi bi-circle"></i><span>SIGAP</span></a></li><li><a href="{{ route('general.booking.form', 'room') }}"><i class="bi bi bi-circle"></i><span>Ruang Jambi</span></a></li></ul>
    </li>

    @auth
      @if(auth()->user()->is_jarkom == 1)
        <li class="nav-heading text-secondary my-3 small fw-bold">Tim Jarkom</li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.reports.*') || request()->routeIs('admin.zoom-reports') ? '' : 'collapsed' }}" data-bs-target="#reports-nav" data-bs-toggle="collapse" href="#"><i class="bi bi-file-earmark-text-fill"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i></a>
          <ul id="reports-nav" class="nav-content collapse {{ request()->routeIs('admin.reports.*') || request()->routeIs('admin.zoom-reports') ? 'show' : '' }}" data-bs-parent="#sidebar-nav"><li><a href="{{ route('admin.reports.index') }}"><i class="bi bi-circle"></i><span>Laporan Helpdesk</span></a></li><li><a href="{{ route('admin.zoom-reports') }}"><i class="bi bi-circle"></i><span>Pengajuan Zoom</span></a></li></ul></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.devices.*') ? '' : 'collapsed' }}" data-bs-target="#mgmt-nav" data-bs-toggle="collapse" href="#"><i class="bi bi-gear-fill"></i><span>Manajemen</span><i class="bi bi-chevron-down ms-auto"></i></a><ul id="mgmt-nav" class="nav-content collapse {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.devices.*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav"><li><a href="{{ route('admin.users.index') }}"><i class="bi bi-circle"></i><span>Akun</span></a></li><li><a href="{{ route('admin.devices.index') }}"><i class="bi bi-circle"></i><span>Perangkat</span></a></li></ul></li>
      @endif
      @if(auth()->user()->is_jarkom == 1 || auth()->user()->is_umum == 1)
        <li class="nav-heading text-secondary my-3 small fw-bold">Tim Umum</li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('umum.*') ? '' : 'collapsed' }}" data-bs-target="#umum-report-nav" data-bs-toggle="collapse" href="#"><i class="bi bi-clipboard-check"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i></a><ul id="umum-report-nav" class="nav-content collapse {{ request()->routeIs('umum.*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav"><li><a href="{{ route('umum.complaints') }}"><i class="bi bi-circle"></i><span>Laporan SIGAP</span></a></li><li><a href="{{ route('umum.bookings') }}"><i class="bi bi-circle"></i><span>Laporan Ruang Jambi</span></a></li></ul></li>
      @endif
    @endauth
  </ul>
</aside><!-- End Sidebar-->
