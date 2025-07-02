<section class=" d-flex justify-content-center">

    <!-- Navbar -->
    <nav class="container-lg navbar navbar-expand-lg bg-light fs-6  position-fixed shadow p-2 mb-2 py-0 bg-body-tertiary rounded">
        <div class="container">
            <a class="navbar-brand fs-5 font" href="{{ url('/') }}"><img class="m-2" src="{{ asset('assets/img/logo_cluster.png') }}" height="70px" width="70px" alt="" /> UPA-CLUSTER</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    {{-- <li class="nav-item px-3">
                        <button type="button" class="btn btn-custom "><a href="{{ route('register') }}" class="text-decoration-none text-dark">Register</a></button>
                    </li> --}}
                    <li class="nav-item px-3">
                        <button type="button" class="btn btn-custom "><a href="{{ route('login') }}" class="text-decoration-none text-dark">Login</a></button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</section>