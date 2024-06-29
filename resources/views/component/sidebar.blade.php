@if (Auth::user()->role == 'admin')
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="/dashboard"> <img alt="image" src="{{ asset('admin/assets/img/logo.png') }}"
                        class="header-logo" />
                    <span class="logo-name">Share Novel</span>
                </a>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Main</li>
                <li class="dropdown {{ Request::path() === 'dashboard' ? 'active' : '' }}">
                    <a href="/dashboardAdmin" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
                </li>

                <li class="dropdown {{ Request::path() === 'terima-buku-admin' ? 'active' : '' }}"><a class="nav-link"
                        href="/terima-buku-admin"><i data-feather="book"></i><span>Terima Buku</span></a>
                </li>
                <li class="dropdown {{ Request::path() === 'terima-isi-admin' ? 'active' : '' }}"><a class="nav-link"
                        href="/terima-isi-admin"><i data-feather="file-text"></i><span>Terima
                            Isi</span></a>
                </li>
            </ul>
        </aside>
    </div>
@else
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="/dashboard"> <img alt="image" src="{{ asset('admin/assets/img/logo.png') }}"
                        class="header-logo" />
                    <span class="logo-name">Share Novel</span>
                </a>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Main</li>
                <li class="dropdown {{ Request::path() === 'dashboardUser' ? 'active' : '' }}">
                    <a href="/dashboardUser" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
                </li>
                <li class="dropdown {{ Request::path() === 'tambahkan-buku' ? 'active' : '' }}"><a class="nav-link"
                        href="/tambahkan-buku"><i data-feather="clipboard"></i><span>Tambahkan Buku</span></a>
                </li>
            </ul>
        </aside>
    </div>
@endif
