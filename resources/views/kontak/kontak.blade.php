<x-layout>
    <x-header></x-header>
<!-- Header Berita -->
    <section class="container-fluid mt-4">
        <div id="berita" class="hero">
            <div class="col-12 px-0">
                <div class="card-utama">
                    <img src="assets/img/banner-berita.jpg" class="d-block w-lg-100 w-100" alt="..." height="500px">
                    <div class="container card-img-overlay d-flex align-items-center justify-content-star">
                        <h1 class="card-title text-light">HUBUNGI KAMI</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Akhir Header Berita -->

    <!-- Akhir Kontak -->
    <div class="container" style="margin-top: 100px;">
        <span class="text-center">
            <h1>Hubungi Kami</h1>
        </span>
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-auto mt-5 mb-4">
                <img src="assets/img/contact.webp" class="d-block w-lg-100 w-100" alt="..." height="500px">
            </div>
            <div class="col-auto">
                <div class="mb-3">
                    <label for="username" class="form-label">Nama Anda :</label>
                    <input type="text" id="username" class="form-control rounded rounded-5 " aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">No Telp :</label>
                    <input type="text" id="username" class="form-control rounded rounded-5" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Pos-el/Email :</label>
                    <input type="text" id="username" class="form-control rounded rounded-5" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Judul :</label>
                    <input type="text" id="username" class="form-control rounded rounded-5" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label ">Pesan :</label>
                    <textarea class="form-control rounded rounded-5" aria-label="With textarea"></textarea>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                    <label class="form-check-label" for="flexCheckChecked">
                        Syarat dan Ketentuan
                    </label>
                </div>
                <div class="submit mt-3">
                    <button type="button" class="btn btn-outline-primary w-100 rounded rounded-5">Kirim</button>
                </div>
            </div>
        </div>


    </div>
    <!-- Akhir Kontak -->

    <!-- Map  -->
    <section class="map-section">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6 col-lg-5 col-xl-4">
                    <div class="contacts-banner position-absolute p-3 bg-white rounded rounded-3 shadow" style="top: 250px; left: 150px; z-index: 2;">
                        <p>Lok :Jl.Medan-Banda Aceh</p>
                        <p>Telp :0273-7685</p>
                        <p>Fax :03654-747466</p>
                        <p>Email :infobzoo@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="map position-relative mt-5">
            <div class="ratio ratio-21x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3975.618738171783!2d97.88209867578261!3d4.835348045140209!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3037f0ba9333de6d%3A0x2330867c97f23611!2sMasjid%20Manzilul%20Minan!5e0!3m2!1sid!2sid!4v1712833960795!5m2!1sid!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
    <style>
        .map .ratio {
            width: 99vw;
            height: 50vh;
        }
    </style>
</x-layout>