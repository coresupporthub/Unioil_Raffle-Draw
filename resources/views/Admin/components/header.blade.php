    <!-- Navbar -->
    <header class="navbar navbar-expand-md d-print-none">
        <div class="container-xl">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href=".">
                    <img src="{{ asset('unioil_images/unioil_logo.png') }}" width="110" height="32" alt="Unioil"
                        class="navbar-brand-image">
                </a>
            </h1>

            <div class="navbar-nav flex-row order-md-last">

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                        aria-label="Open user menu">
                        <span class="avatar avatar-sm"
                            style="background-image: url({{ asset('unioil_images/unioil.png') }})"></span>
                        <div class="d-none d-xl-block ps-2">
                            <div id="administrator_name">Loading.....</div>
                            <div class="mt-1 small text-secondary">Administrator</div>
                        </div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <a href="/activity-logs" class="dropdown-item"> Activity Log</a>
                        <a href="{{ route('accountsettings') }}" class="dropdown-item"> Settings</a>
                        <button onclick="adminLogout()" class="dropdown-item">Logout</button>

                    </div>
                </div>
            </div>
        </div>
    </header>

    <header class="navbar-expand-md">
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="navbar">
                <div class="container-xl">
                    <ul class="navbar-nav">
                        <li class="nav-item {{ $active === 'dashboard' ? 'active' : '' }}">

                            <a class="nav-link" href="{{ route('index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-chart-histogram">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 3v18h18" />
                                        <path d="M20 18v3" />
                                        <path d="M16 16v5" />
                                        <path d="M12 13v8" />
                                        <path d="M8 16v5" />
                                        <path d="M3 11c6 0 5 -5 9 -5s3 5 9 5" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Analytics Dashboard
                                </span>
                            </a>
                        </li>

                        {{-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                        <path d="M12 12l8 -4.5" />
                                        <path d="M12 12l0 9" />
                                        <path d="M12 12l-8 -4.5" />
                                        <path d="M16 5.25l-8 4.5" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Regional Clusters
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        <a class="dropdown-item" href="#">
                                            region 1
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            region 2
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            region 3
                                        </a>
                                    </div>
                                </div>
                        </li> --}}
                        <li class="nav-item {{ $active === 'qrgenerator' ? 'active' : '' }}">

                            <a class="nav-link" href="{{ route('qrgenerator') }}">
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-qrcode">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                        <path d="M7 17l0 .01" />
                                        <path
                                            d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                        <path d="M7 7l0 .01" />
                                        <path
                                            d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                        <path d="M17 7l0 .01" />
                                        <path d="M14 14l3 0" />
                                        <path d="M20 14l0 .01" />
                                        <path d="M14 14l0 3" />
                                        <path d="M14 20l3 0" />
                                        <path d="M17 17l3 0" />
                                        <path d="M20 17l0 3" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    QR Management
                                </span>
                            </a>
                        </li>

                        <li class="nav-item {{ $active === 'retailoutlets' ? 'active' : '' }}">

                            <a class="nav-link" href="{{ route('retailoutlets') }}">
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-building-warehouse">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 21v-13l9 -4l9 4v13" />
                                        <path d="M13 13h4v8h-10v-6h6" />
                                        <path d="M13 21v-9a1 1 0 0 0 -1 -1h-2a1 1 0 0 0 -1 1v3" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Retail Outlets
                                </span>
                            </a>
                        </li>

                        <li class="nav-item {{ $active === 'raffledraw' ? 'active' : '' }}">

                            <a class="nav-link" href="{{ route('raffledraw') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-gift">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M3 8m0 1a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1z" />
                                        <path d="M12 8l0 13" />
                                        <path d="M19 12v7a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-7" />
                                        <path
                                            d="M7.5 8a2.5 2.5 0 0 1 0 -5a4.8 8 0 0 1 4.5 5a4.8 8 0 0 1 4.5 -5a2.5 2.5 0 0 1 0 5" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Raffle Draw
                                </span>
                            </a>
                        </li>

                        <li class="nav-item {{ $active === 'raffleevents' ? 'active' : '' }}">

                            <a class="nav-link" href="{{ route('raffleevents') }}">
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-report-analytics">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                        <path
                                            d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                        <path d="M9 17v-5" />
                                        <path d="M12 17v-1" />
                                        <path d="M15 17v-3" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Raffle Events & Results
                                </span>
                            </a>
                        </li>

                        <li class="nav-item {{ $active === 'raffleentries' ? 'active' : '' }}">

                            <a class="nav-link" href="{{ route('raffleentries') }}">
                                <span
                                    class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-ticket">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M15 5l0 2" />
                                        <path d="M15 11l0 2" />
                                        <path d="M15 17l0 2" />
                                        <path
                                            d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Raffle Entries
                                </span>
                            </a>
                        </li>

                        <li class="nav-item {{ $active === 'productreports' ? 'active' : '' }}">

                            <a class="nav-link" href="{{ route('productreports') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart-dollar">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                        <path d="M13 17h-7v-14h-2" />
                                        <path d="M6 5l14 1l-.575 4.022m-4.925 2.978h-8.5" />
                                        <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                        <path d="M19 21v1m0 -8v1" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    Product Reports
                                </span>
                            </a>
                        </li>

                    </ul>

                </div>
            </div>
        </div>
    </header>
