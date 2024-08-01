<x-layout>
    <x-header></x-header>
<!-- Header Berita -->
    <section class="container-fluid mt-4">
        <div id="berita">
            <div class="col-12 px-0">
                <div class="card-utama">
                    <img src="assets/img/banner-berita.jpg" class="d-block w-lg-100 w-100" alt="..." height="500px">
                    <div class="container card-img-overlay d-flex align-items-center justify-content-star">
                        <h1 class="card-title text-light">PEMBELIAN TIKET</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Akhir Header Berita -->

    <!-- Image Hero -->
    <section class="container mt-4">
        <div class="row ">
            <div class="col-lg-7 mt-2 mb-2 ">
                <img src="assets/img/banner_ticket.jpg" class="d-block w-lg-100 w-100 rounded rounded-4" alt="..." height="500px">
            </div>
            <div class="col-lg-5  mt-2 mb-2 ">
                <div class="col-lg">
                    <img src="assets/img/harimau.jpg" class="d-block w-lg-100 w-100 rounded rounded-4" alt="..." height="245px">
                </div>
                <div class="col-lg  mt-2 ">
                    <img src="assets/img/singa.jpg" class="d-block w-lg-100 w-100 rounded rounded-4" alt="..." height="245px">
                </div>
            </div>


        </div>
    </section>

    <!-- Akhir Image hero -->

    <!-- Beli Tiket -->

    <section class="container mt-4">
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col-lg-7 mt-2 mb-2 p-2 ps-3">
                <h1 class="fw-semibold fs-3 text-uppercase">Detail </h1>
                <p class="mt-5">
                    Taman zoologi pertama dan satu-satunya di Bali adalah tempat menakjubkan di mana Anda bisa pelajari perilaku lebih dari 500 hewan langka dan eksotis di lingkungan tropis yang rimbun.
                    Taman tempat
                    Anda dapat berpartisipasi dalam aktivitas petualangan hewan yang menarik, beberapa di antaranya merupakan pengalaman unik sejenis di Indonesia.
                </p>
                <p>
                    <strong>Catatan : tiket tidak dapat dikembalikan</strong>
                </p>
            </div>

            <div class="col-lg mt-2 mb-2 p-2 ps-3 ">
                <form id="payment-form" action="detail_tiket.php" method="post">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal: </label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control rounded rounded-5" aria-label="tanggal" aria-describedby="basic-addon1">
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah :</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control rounded rounded-5" aria-label="jumlah" aria-describedby="basic-addon1">
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga :</label>
                        <input type="number" name="harga" id="harga" class="form-control rounded rounded-5" aria-label="harga" aria-describedby="basic-addon1" value="60000" readonly>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                        <label class="form-check-label" for="flexCheckChecked">
                            Syarat dan Ketentuan
                        </label>
                    </div>
                    <div class="submit mt-3">
                        <button id="pay-button" type="submit" class="btn btn-outline-primary w-25 rounded rounded-5">Pesan</a></button>
                    </div>
                </form>

            </div>
        </div>

    </section>

    <!-- Akhir Beli Tiket -->
</x-layout>