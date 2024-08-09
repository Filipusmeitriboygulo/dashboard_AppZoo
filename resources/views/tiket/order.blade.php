
<x-layout>
<!-- Header Berita -->
    <section class="container-fluid mt-4">
        <div id="berita">
            <div class="col-12 px-0">
                <div class="card-utama">
                    <img src="{{ asset('assets/img/banner-berita.jpg') }}" class="d-block w-lg-100 w-100" alt="..." height="500px">
                    <div class="container card-img-overlay d-flex align-items-center justify-content-star">
                        <h1 class="card-title text-light">PEMBELIAN TIKET</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Akhir Header Berita -->

    <!-- Menu Beli Tiket -->
    <section class="container-fluid bg-secondary p-2 text-dark bg-opacity-10  mt-5 d-flex justify-content-center">
        <div class="row row-cols-3">
            <div class="col-lg-4  d-flex flex-column align-items-center justify-content-center cursor-pointer">
                <h5 class="font-light fs-6 mb-1 uppercase text-primary-dark">Tiket</h5>
                <h6 class="font-bold fs-2 d-flex align-items-center justify-content-center text-gray-500 bg-gray-200 border border-primary rounded rounded-circle  w-75 h-75">1</h6>
            </div>
            <div class="col-lg-4  d-flex flex-column align-items-center justify-content-center cursor-pointer">
                <h5 class="font-light fs-6 mb-1 uppercase text-gray-500">Detail</h5>
                <h6 class="font-bold fs-2 d-flex align-items-center justify-content-center text-gray-500 bg-gray-200 border border-primary rounded rounded-circle  w-75 h-75">2</h6>
            </div>
            <div class=" col-lg-4 d-flex flex-column align-items-center justify-content-center cursor-pointer">
                <h5 class="font-light fs-6 mb-1 uppercase text-gray-500">Pembayaran</h5>
                <h6 class="font-bold fs-2 d-flex align-items-center justify-content-center text-gray-500 bg-gray-200 border border-primary rounded rounded-circle  w-75 h-75">3</h6>
            </div>
        </div>


    </section>

    <!-- Akhir Menu -->

    <!-- Beli Tiket -->

    <section class="container mt-5">
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col-lg-7 mt-2 mb-2 p-2 ps-3">
                <h1 class="fw-semibold fs-3 text-uppercase">Detail Kontak </h1>
                <p class="mt-5">
                    Nama Lengkap
                </p>
                <p>
                    {{ $pembeli->nama }}
                </p>
                <p>
                    Email
                </p>
                <p>
                    filipusmeitrioygulo@gmail.com
                </p>
                <p>
                    Nomor Kontak
                </p>
                <p>
                    +6270664532
                </p>
            </div>
            <div class="col-lg-5 mt-2 mb-2 p-2 ps-3">
                <div class="d-flex align-items-center">
                    <h1 class="fw-semibold fs-3 text-uppercase">Detail Pembelian</h1>
                </div>
                <div class="harga-tiket d-flex align-items-center justify-content-between mt-4 mb-2">
                    <h1 class="fw-normal fs-5">Tiket</h1>
                    <div class="d-flex justify-content-end align-items-end">
                        <h1 class="fw-normal fs-5 mt-2">Rp.60.000</h1>
                    </div>
                </div>
                <div class="harga-tiket d-flex align-items-center justify-content-between">
                    <h1 class="fw-normal fs-5">Total</h1>
                    <div class="d-flex justify-content-end align-items-end">
                        <h1 class="fw-normal fs-5 mt-2">Rp.60.000</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="submit d-flex justify-content-end" style="margin-top: 70px;">
            <button type="button" class="btn btn-outline-warning  me-5"><a href="detail_tiket.php" class="text-decoration-none text-warning">Kembali</a></button>
            <button type="button" class="btn btn-outline-primary "><a href="pembayaran.php" class="text-decoration-none text-primary">Checkout</a></button>
        </div>
    </section>
</x-layout>