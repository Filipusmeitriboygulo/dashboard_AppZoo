<aside class="left-sidebar">
    <!-- Sidebar scroll-->

    {{-- Rule Route --}}
    @php
        $role = auth()->user()->role;
        $prefix = match ($role) {
            'admin' => 'admin',
            'kepala_upa' => 'kepala-upa',
            'wakil_direktur' => 'wakil-direktur',
            'ketua_jurusan' => 'ketua_jurusan',
            'ketua_prodi' => 'ketua_prodi',

            default => null,
        };

    @endphp
    <div>
        <!-- Logo -->
        <div>
            <div class="brand-logo d-flex align-items-center justify-content-between">
                <a href="./index.html" class="text-nowrap logo-img navbar-brand fs-6 fw-bold">
                    <img src="{{ asset('assets/img/logo_cluster.png') }}" width="70" alt="logo-bzoo" />
                    UPA-CLUSTER
                </a>
                <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                    <i class="ti ti-x fs-8"></i>
                </div>
            </div>


            <!-- Sidebar navigation-->
            <nav class="sidebar-nav scroll-sidebar" data-simplebar>
                <ul id="sidebarnav">
                    @auth
                        {{-- Tampilkan hanya jika user adalah admin --}}
                        @if (auth()->user()->role === 'admin')
                            <li class="sidebar-item mt-4">
                                <a class="sidebar-link" href="{{ route('home') }}">
                                    <span><i class="ti ti-layout-dashboard"></i></span>
                                    <span class="hide-menu">DATA</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('admin.users.index') }}">
                                    <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                                            <path
                                                d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                                        </svg></span>
                                    <span class="hide-menu">USER</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('admin.jurusan.index') }}">
                                    <span><i class="ti ti-building"></i></span>
                                    <span class="hide-menu">JURUSAN</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('admin.prodi.index') }}">
                                    <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-buildings" viewBox="0 0 16 16">
                                            <path
                                                d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z" />
                                            <path
                                                d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z" />
                                        </svg></span>
                                    <span class="hide-menu">PRODI</span>
                                </a>
                            </li>
                        @endif

                        {{-- Tampil untuk semua user login --}}
                        @if ($prefix)
                            <li class="sidebar-item mt-3">
                                <a class="sidebar-link" href="{{ route('klasterisasi.index') }}">
                                    <span><i class="ti ti-article"></i></span>
                                    <span class="hide-menu">KLASTERISASI</span>
                                </a>
                            </li>

                            @isset($upload)
                                <li class="sidebar-item">
                                    <a class="sidebar-link"
                                        href="{{ route('klasterisasi.result', ['upload_id' => $upload->id]) }}">
                                        <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-bar-chart-steps" viewBox="0 0 16 16">
                                                <path
                                                    d="M.5 0a.5.5 0 0 1 .5.5v15a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 .5 0M2 1.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-6a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5z" />
                                            </svg></span>
                                        <span class="hide-menu">DASHBOARD</span>
                                    </a>
                                </li>
                            @endisset
                        @endif
                    @endauth
                </ul>
            </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
</aside>
