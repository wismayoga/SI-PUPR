<div class="app-sidebar">
    <div class="logo">
        <a href="#" class="logo-icon"><span class="logo-text">PUTR</span></a>
        <div class="sidebar-user-switcher">
            <a href="#">
                <span class="activity-indicator"></span>
                <span class="user-info-text" style="font-size: 16px;">
                    SISTEM INFORMASI
                    <br>
                    <span class="user-state-info" style="font-size: 10px;">DINAS PEKERJAAN UMUM
                        DAN<br> TATA
                        RUANG</span>
                </span>
            </a>
        </div>
    </div>
    <div class="app-menu">
        <ul class="accordion-menu">
            <li class="sidebar-title">
                MENU
            </li>
            {{-- <li class="active-page">
                <a href="index.html" class="active"><i class="material-icons-two-tone">dashboard</i>Dashboard</a>
            </li> --}}
            @if (auth()->user()->role == 'user')
                <li class="{{ request()->routeIs('user.reports', 'user.reports.show') ? 'active-page' : '' }}">
                    <a href="{{ route('user.reports') }}">
                        <i class="material-icons-two-tone">move_to_inbox</i>
                        Laporan Masuk
                    </a>
                </li>
            @elseif(auth()->user()->role == 'admin')
                <li
                    class="{{ request()->routeIs('admin.reportsin', 'admin.reportsin.show', 'admin.reportsin.show.details') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.reportsin') }}">
                        <i class="material-icons-two-tone">move_to_inbox</i>
                        Laporan Masuk
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.reports', 'admin.reports.show') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.reports') }}">
                        <i class="material-icons-two-tone">unarchive</i>
                        Laporan Keluar
                    </a>
                </li>
            @endif

            <li class="sidebar-title">
                OTHER
            </li>
            {{-- <li class="active-page">
                <a href="index.html" class="active"><i class="material-icons-two-tone">dashboard</i>Dashboard</a>
            </li> --}}
            <li
                class="{{ request()->routeIs('settings.admin') || request()->routeIs('settings.user') ? 'active-page' : '' }}">
                <a href="{{ Auth::user()->hasRole('admin') ? route('settings.admin') : route('settings.user') }}">
                    <i class="material-icons-two-tone">settings</i>Pengaturan
                </a>
            </li>
        </ul>
    </div>
</div>
