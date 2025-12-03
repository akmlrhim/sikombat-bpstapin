<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="{{ route('home') }}" class="brand-link">
    <img src="{{ asset('img/logo_bps.webp') }}" loading="lazy" title="Logo" alt="Logo"
      class="brand-image img-circle" />
    <span class="brand-text font-weight-bold text-md">SIKOMBAT</span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-header text-muted">Utama</li>

        <li class="nav-item">
          <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard nav-icon">
              <rect width="7" height="9" x="3" y="3" rx="1" />
              <rect width="7" height="5" x="14" y="3" rx="1" />
              <rect width="7" height="9" x="14" y="12" rx="1" />
              <rect width="7" height="5" x="3" y="16" rx="1" />
            </svg>
            <p>
              Home
            </p>
          </a>
        </li>

        @if (Auth::user()->role == 'ketua_tim' || Auth::user()->role == 'umum')
          <li class="nav-item">
            <a href="{{ route('mitra.index') }}" class="nav-link {{ request()->routeIs('mitra.*') ? 'active' : '' }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-user-check-icon lucide-user-check nav-icon">
                <path d="m16 11 2 2 4-4" />
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
              </svg>
              <p>Mitra</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('kegiatan.index') }}" class="nav-link {{ request()->routeIs('akun.*') ? 'active' : '' }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-circuit-board-icon lucide-circuit-board nav-icon">
                <rect width="18" height="18" x="3" y="3" rx="2" />
                <path d="M11 9h4a2 2 0 0 0 2-2V3" />
                <circle cx="9" cy="9" r="2" />
                <path d="M7 21v-4a2 2 0 0 1 2-2h4" />
                <circle cx="15" cy="15" r="2" />
              </svg>
              <p>Kegiatan </p>
            </a>
          </li>
        @endif

        <li class="nav-item">
          <a href="{{ route('kontrak.index') }}" class="nav-link {{ request()->routeIs('kontrak.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-receipt-text-icon lucide-receipt-text nav-icon">
              <path d="M13 16H8" />
              <path d="M14 8H8" />
              <path d="M16 12H8" />
              <path
                d="M4 3a1 1 0 0 1 1-1 1.3 1.3 0 0 1 .7.2l.933.6a1.3 1.3 0 0 0 1.4 0l.934-.6a1.3 1.3 0 0 1 1.4 0l.933.6a1.3 1.3 0 0 0 1.4 0l.933-.6a1.3 1.3 0 0 1 1.4 0l.934.6a1.3 1.3 0 0 0 1.4 0l.933-.6A1.3 1.3 0 0 1 19 2a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1 1.3 1.3 0 0 1-.7-.2l-.933-.6a1.3 1.3 0 0 0-1.4 0l-.934.6a1.3 1.3 0 0 1-1.4 0l-.933-.6a1.3 1.3 0 0 0-1.4 0l-.933.6a1.3 1.3 0 0 1-1.4 0l-.934-.6a1.3 1.3 0 0 0-1.4 0l-.933.6a1.3 1.3 0 0 1-.7.2 1 1 0 0 1-1-1z" />
            </svg>
            <p>Kontrak</p>
          </a>
        </li>

        <li class="nav-header text-muted">Tambahan</li>

        @if (Auth::user()->role == 'ketua_tim' || Auth::user()->role == 'umum' || Auth::user()->role == 'admin')
          <li class="nav-item">
            <a href="{{ route('user.index') }}" class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-users-icon lucide-users nav-icon">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                <circle cx="9" cy="7" r="4" />
              </svg>
              <p>User</p>
            </a>
          </li>
        @endif

        <li class="nav-item">
          <a href="{{ route('tambahan.index') }}"
            class="nav-link {{ request()->routeIs('tambahan.*') ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-badge-plus-icon lucide-badge-plus nav-icon">
              <path
                d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
              <line x1="12" x2="12" y1="8" y2="16" />
              <line x1="8" x2="16" y1="12" y2="12" />
            </svg>
            <p>
              Lainnya
            </p>
          </a>
        </li>

        @if (Auth::user()->role == 'admin')
          <li class="nav-item">
            <a href="{{ route('visit') }}" class="nav-link {{ request()->routeIs('visit.*') ? 'active' : '' }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-activity-icon lucide-activity nav-icon">
                <path
                  d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2" />
              </svg>
              <p>
                Pengunjung Web
              </p>
            </a>
          </li>
        @endif

        <li class="nav-item">
          <a href="#" class="nav-link"
            onclick="event.preventDefault(); document.getElementById('logout').submit();">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-log-out-icon lucide-log-out nav-icon text-danger">
              <path d="m16 17 5-5-5-5" />
              <path d="M21 12H9" />
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            </svg>
            <p class="text-danger">
              Logout
            </p>
          </a>

          <form id="logout" method="POST" action="{{ route('logout') }}" class="d-none">
            @csrf
          </form>
        </li>
      </ul>
    </nav>
  </div>
</aside>
