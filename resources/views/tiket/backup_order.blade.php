<?php
// session_start();
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $_SESSION['nama'] = $_POST['nama'];
//     $_SESSION['email'] = $_POST['email'];
//     $_SESSION['kontak'] = $_POST['kontak'];
// }

// $tanggal = $_SESSION['tanggal'] ?? '';
// $jumlah = isset($_SESSION['jumlah']) ? intval($_SESSION['jumlah']) : 0;
// $harga = isset($_SESSION['harga']) ? floatval($_SESSION['harga']) : 0.0;
// $nama = $_SESSION['nama'] ?? '';
// $email = $_SESSION['email'] ?? '';
// $kontak = $_SESSION['kontak'] ?? '';
// $total = $jumlah * $harga;

?>
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

    <!-- Menu Beli Tiket -->
    <section class="container-fluid bg-secondary p-2 text-dark bg-opacity-10  mt-5 d-flex justify-content-center position-relative">
        <div class="row row-cols-3" id="myButton">
            <div class="col-lg-4  d-flex flex-column align-items-center justify-content-center cursor-pointer ">
                <h5 class="font-light fs-6 mb-1 uppercase text-primary-dark">Tiket</h5>
                <h6 class="font-bold fs-2 d-flex align-items-center justify-content-center text-gray-500 bg-gray-200 border border-primary rounded rounded-circle  w-75 h-75 "><a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="detail_tiket.php" role="tab" aria-controls="pills-home" aria-selected="true">1</a></h6>
            </div>
            <div class="col-lg-4  d-flex flex-column align-items-center justify-content-center cursor-pointer ">
                <h5 class="font-light fs-6 mb-1 uppercase text-gray-500">Detail</h5>
                <h6 class="font-bold fs-2 d-flex align-items-center justify-content-center text-gray-500 bg-gray-200 border border-primary rounded rounded-circle  w-75 h-75 bg-primary"><a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="pesanan.php" role="tab" aria-controls="pills-profile" aria-selected="false">2</a></h6>
            </div>
            <div class=" col-lg-4 d-flex flex-column align-items-center justify-content-center cursor-pointer ">
                <h5 class="font-light fs-6 mb-1 uppercase text-gray-500">Pembayaran</h5>
                <h6 class="font-bold fs-2 d-flex align-items-center justify-content-center text-gray-500 bg-gray-200 border border-primary rounded rounded-circle  w-75 h-75"><a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="pembayaran.php" role="tab" aria-controls="pills-contact" aria-selected="false">3</a></h6>
            </div>
        </div>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"></div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"></div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab"></div>
        </div>
    </section>

    {{-- <script>
        // Add active class to the current button (highlight it)
        var header = document.getElementById("myButoon");
        var btns = header.getElementsByClassName("nav-link");
        for (var i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function() {
                var current = document.getElementsByClassName("active");
                current[0].className = current[0].className.replace(" active", "");
                this.className += " active";
            });
        }
    </script> --}}

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
                    <?php echo htmlspecialchars($nama) ?>
                </p>
                <p>
                    Email
                </p>
                <p>
                    <?php echo htmlspecialchars($email) ?>
                </p>
                <p>
                    Nomor Kontak
                </p>
                <p>
                    <?php echo htmlspecialchars($kontak) ?>
                </p>
            </div>
            <div class="col-lg-5 mt-2 mb-2 p-2 ps-3">
                <div class="d-flex align-items-center">
                    <h1 class="fw-semibold fs-3 text-uppercase">Detail Pembelian</h1>
                </div>
                <div class="harga-tiket d-flex align-items-center justify-content-between mt-4 mb-2">
                    <h1 class="fw-normal fs-5">Tiket</h1>
                    <div class="d-flex justify-content-end align-items-end">
                        <h1 class="fw-normal fs-5 mt-2">Rp. <?php echo number_format($harga, 0, ',', '.'); ?></h1>
                    </div>
                </div>
                <div class="harga-tiket d-flex align-items-center justify-content-between">
                    <h1 class="fw-normal fs-5">Total</h1>
                    <div class="d-flex justify-content-end align-items-end">
                        <h1 class="fw-normal fs-5 mt-2">Rp. <?php echo number_format($total, 0, ',', '.'); ?></h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="submit d-flex justify-content-end" style="margin-top: 70px;">
            <button type="button" class="btn btn-outline-warning  me-5"><a href="{{ route('detail_tiket') }}" class="text-decoration-none text-warning">Kembali</a></button>
            <button id="pay-button" type="submit" class="btn btn-outline-primary ">Checkout</button>
        </div>

        {{-- <script type="text/javascript">
            document.getElementById('pay-button').addEventListener('click', async function() {
                var formData = new FormData();
                formData.append('tanggal', "<?php echo $tanggal ?>");
                formData.append('jumlah', "<?php echo $jumlah ?>");
                formData.append('nama', "<?php echo $nama ?>");
                formData.append('email', "<?php echo $email ?>");
                formData.append('kontak', "<?php echo $kontak ?>");

                try {
                    const response = await fetch('placeOrder.php', {
                        method: 'POST',
                        body: formData,
                    });
                    const token = await response.text();
                    window.snap.pay(token);
                } catch (err) {
                    console.log(err.message);
                }
            });
        </script> --}}
    </section>

    <!-- Akhir Beli Tiket -->

</x-layout>