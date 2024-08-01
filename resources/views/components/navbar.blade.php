<section class=" d-flex justify-content-center">

    <!-- Navbar -->
    <nav class="container-lg navbar navbar-expand-lg bg-light fs-6 font position-fixed shadow p-2 mb-2 py-0 bg-body-tertiary rounded">
        <div class="container">
            <a class="navbar-brand fs-5 font" href="{{ url('/') }}"><img class="m-2" src="assets/img/logo.jpg" height="70px" width="70px" alt="" /> B-ZOO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item px-3">
                        <a class="nav-link active" aria-current="page" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="{{ route('berita') }}">Berita</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="{{ route('profil') }}">Profil</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="{{ route('satwa') }}">Satwa</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="{{ route('peta') }}">Peta</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link" href="{{ route('kontak') }}">Kontak</a>
                    </li>
                    <li class="nav-item px-3">
                        <button type="button" class="btn btn-warning"><a href="{{ route('tiket') }}" class="text-decoration-none">Tiket</a></button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</section>