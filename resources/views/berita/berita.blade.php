<x-layout>
    <x-header></x-header>
    <!-- Header Berita -->
    <section class="container-fluid mt-4">
        <div id="berita" class="hero">
            <div class="col-12 px-0">
                <div class="card-utama">
                    <img src="assets/img/banner-berita.jpg" class="d-block w-lg-100 w-100" alt="..." height="500px">
                    <div class="container card-img-overlay d-flex align-items-center justify-content-star">
                        <h1 class="card-title text-light">BERITA TERKINI</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Akhir Header Berita -->

    <!-- Berita -->
    <section class="container" style="margin-top: 100px;">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
                <a href="{{ route('detail_berita') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <img src="assets/img/harimau.jpg" class="card-img-top w-100" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Harimau Sumtra Terancam Punah</h5>
                            <p class="card-text">Penyu laut adalah makhluk yang terancam punah, menghadapi ancaman dari perburuan, polusi plastik, dan perubahan iklim di habitat mereka.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/singa.jpg" class="card-img-top w-100" alt="..." height="260px">
                    <div class="card-body">
                        <h5 class="card-title">Penyu laut Terancam Punah</h5>
                        <p class="card-text">Penyu laut adalah makhluk yang terancam punah, menghadapi ancaman dari perburuan, polusi plastik, dan perubahan iklim di habitat mereka.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/harimau.jpg" class="card-img-top w-100" alt="..." height="260px">
                    <div class="card-body">
                        <h5 class="card-title">Penyu laut Terancam Punah</h5>
                        <p class="card-text">Penyu laut adalah makhluk yang terancam punah, menghadapi ancaman dari perburuan, polusi plastik, dan perubahan iklim di habitat mereka.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/primata.jpg" class="card-img-top w-100" alt="..." height="260px">
                    <div class="card-body">
                        <h5 class="card-title">Penyu laut Terancam Punah</h5>
                        <p class="card-text">Penyu laut adalah makhluk yang terancam punah, menghadapi ancaman dari perburuan, polusi plastik, dan perubahan iklim di habitat mereka.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/flamingo.jpg" class="card-img-top w-100" alt="..." height="260px">
                    <div class="card-body">
                        <h5 class="card-title">Penyu laut Terancam Punah</h5>
                        <p class="card-text">Penyu laut adalah makhluk yang terancam punah, menghadapi ancaman dari perburuan, polusi plastik, dan perubahan iklim di habitat mereka.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/banner2.jpg" class="card-img-top w-100" alt="..." height="260px">
                    <div class="card-body">
                        <h5 class="card-title">Penyu laut Terancam Punah</h5>
                        <p class="card-text">Penyu laut adalah makhluk yang terancam punah, menghadapi ancaman dari perburuan, polusi plastik, dan perubahan iklim di habitat mereka.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/zoo.jpg" class="card-img-top w-100" alt="..." height="260px">
                    <div class="card-body">
                        <h5 class="card-title">Penyu laut Terancam Punah</h5>
                        <p class="card-text">Penyu laut adalah makhluk yang terancam punah, menghadapi ancaman dari perburuan, polusi plastik, dan perubahan iklim di habitat mereka.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/banner_ticket.jpg" class="card-img-top w-100" alt="..." height="260px">
                    <div class="card-body">
                        <h5 class="card-title">Penyu laut Terancam Punah</h5>
                        <p class="card-text">Penyu laut adalah makhluk yang terancam punah, menghadapi ancaman dari perburuan, polusi plastik, dan perubahan iklim di habitat mereka.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="assets/img/burung.jpg" class="card-img-top w-100" alt="..." height="260px">
                    <div class="card-body">
                        <h5 class="card-title">Penyu laut Terancam Punah</h5>
                        <p class="card-text">Penyu laut adalah makhluk yang terancam punah, menghadapi ancaman dari perburuan, polusi plastik, dan perubahan iklim di habitat mereka.</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 50px;">
                <ul class="pagination justify-content-end">
                    <li class="page-item disabled me-3">
                        <a class="page-link">Kembali</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item"><a class="page-link" href="#">6</a></li>
                    <li class="page-item">
                        <a class="page-link disabled ms-3" href="#">Selanjutnya</a>
                    </li>
                </ul>
            </div>
    </section>
</x-layoout>