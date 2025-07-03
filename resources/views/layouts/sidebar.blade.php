<aside class="left-sidebar">
    <!-- Sidebar scroll-->

    {{-- Rule Route --}}
    @php
        $role = auth()->user()->role;
        $prefix = match ($role) {
            'admin' => 'admin',
            'kepala_upa' => 'kepala-upa',
            'wakil_direktur' => 'wakil-direktur',
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
                                    <span><i class="ti ti-file-description"></i></span>
                                    <span class="hide-menu">USER</span>
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
                                        <span><i class="ti ti-cards"></i></span>
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
