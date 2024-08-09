    <x-layout>
        <x-header></x-header>
        <!-- Header Berita -->
        <section class="container-fluid mt-4">
            <div id="berita">
                <div class="col-12 px-0">
                    <div class="card-utama">
                        <img src="{{ asset('assets/img/banner-berita.jpg') }}" class="d-block w-lg-100 w-100"
                            alt="..." height="500px">
                        <div class="container card-img-overlay d-flex align-items-center justify-content-star">
                            <h1 class="card-title text-light">PEMBELIAN TIKET</h1>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Akhir Header Berita -->


        <!-- Menu Beli Tiket -->

        <section
            class="container-fluid bg-secondary p-2 text-dark bg-opacity-10  mt-5 d-flex justify-content-center position-relative">
            <div class="row row-cols-3">
                <div class="col-lg-4  d-flex flex-column align-items-center justify-content-center cursor-pointer ">
                    <h5 class="font-light fs-6 mb-1 uppercase text-primary-dark">Tiket</h5>
                    <h6
                        class="font-bold fs-2 d-flex align-items-center justify-content-center text-gray-500 bg-gray-200 border border-primary rounded rounded-circle  w-75 h-75 bg-primary ">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="detail_tiket.php"
                            role="tab" aria-controls="pills-home" aria-selected="true">1</a>
                    </h6>
                </div>
                <div class="col-lg-4  d-flex flex-column align-items-center justify-content-center cursor-pointer ">
                    <h5 class="font-light fs-6 mb-1 uppercase text-gray-500">Detail</h5>
                    <h6
                        class="font-bold fs-2 d-flex align-items-center justify-content-center text-gray-500 bg-gray-200 border border-primary rounded rounded-circle  w-75 h-75 ">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="pesanan.php" role="tab"
                            aria-controls="pills-profile" aria-selected="false">2</a>
                    </h6>
                </div>
                <div class=" col-lg-4 d-flex flex-column align-items-center justify-content-center cursor-pointer ">
                    <h5 class="font-light fs-6 mb-1 uppercase text-gray-500">Pembayaran</h5>
                    <h6
                        class="font-bold fs-2 d-flex align-items-center justify-content-center text-gray-500 bg-gray-200 border border-primary rounded rounded-circle  w-75 h-75">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="pembayaran.php"
                            role="tab" aria-controls="pills-contact" aria-selected="false">3</a>
                    </h6>
                </div>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"></div>
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab"></div>
            </div>
        </section>


        <!-- Akhir Menu -->
        <!-- Kontak -->
        <section class=" container" style="margin-top: 100px;">
            <span class="text-center">
                <h1 class="fs-2">BERIKAN DETAIL KONTAK ANDA</h1>
            </span>
            <form action="{{ route('pembeli.input') }}" method="post">
                @csrf
                <div class="row d-flex justify-content-center mt-5">
                    <div class="w-50">
                        <div class="mb-3 ">
                            <label for="nama" class="form-label">Nama Anda :</label>
                            <input type="text" name="nama" id="nama" class="form-control" aria-label="nama"
                                aria-describedby="basic-addon1">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Pos-el/Email :</label>
                            <input type="email" name="email" id="email" class="form-control" aria-label="email"
                                aria-describedby="basic-addon1">
                        </div>
                        <div class="mb-3">
                            <label for="nohp" class="form-label">No.Kontak :</label>
                            <input type="text" name="nohp" id="nohp" class="form-control" aria-label="nohp"
                                aria-describedby="basic-addon1">
                        </div>
                        <div class="mb-3">
                        </div>
                    </div>
                </div>
                <div class="submit d-flex justify-content-end" style="margin-top: 70px;">
                    <button type="button" class="btn btn-outline-warning me-5"><a href="tiket.php"
                            class="text-decoration-none text-warning">Kembali</a></button>
                    <button type="submit" class="btn btn-outline-primary">Selanjutnya</button>
                </div>
            </form>

        </section>
    </x-layout>
