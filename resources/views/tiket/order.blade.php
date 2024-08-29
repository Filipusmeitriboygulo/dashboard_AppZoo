<x-layout>
    <section class="container-fluid mt-4">
        <div id="berita">
            <div class="col-12 px-0">
                <div class="card-utama">
                    <img src="{{ asset('assets/img/banner-berita.jpg') }}" class="d-block w-lg-100 w-100" alt="..."
                        height="500px">
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
                    class="font-bold fs-2 d-flex align-items-center justify-content-center text-gray-500 bg-gray-200 border border-primary rounded rounded-circle  w-75 h-75  ">
                    <a class="nav-link " id="pills-home-tab" data-toggle="pill" href="detail_tiket.php" role="tab"
                        aria-controls="pills-home" aria-selected="true">1</a>
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
                    class="font-bold fs-2 d-flex align-items-center justify-content-center text-gray-500 bg-gray-200 border border-primary rounded rounded-circle  w-75 h-75 bg-primary">
                    <a class="nav-link active" id="pills-contact-tab" data-toggle="pill" href="pembayaran.php"
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

    <!-- Beli Tiket -->

    <section class="container mt-5">
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col-lg-7 mt-2 mb-2 p-2 ps-3">
                <h1 class="fw-semibold fs-3 text-uppercase">Detail Kontak </h1>
                <p class="mt-5">
                    Nama Lengkap :
                </p>
                <p>
                    {{ $pembeli->nama }}
                </p>
                <p>
                    Email :
                </p>
                <p>
                    {{ $pembeli->email }}
                </p>
                <p>
                    Nomor Kontak :
                </p>
                <p>
                    {{ $pembeli->nohp }}
                </p>
            </div>
            <div class="col-lg-5 mt-2 mb-2 p-2 ps-3">
                <div class="d-flex align-items-center">
                    <h1 class="fw-semibold fs-3 text-uppercase">Detail Pembelian</h1>
                </div>
                <div class="harga-tiket d-flex align-items-center justify-content-between mt-4 mb-2">
                    <h1 class="fw-normal fs-5">Tiket :</h1>
                    <div class="d-flex justify-content-end align-items-end">
                        <h1 class="fw-normal fs-5 mt-2">{{ $pembeli->pesanan->jumlah_tiket }}</h1>
                    </div>
                </div>
                <div>
                </div>
                <div class="harga-tiket d-flex align-items-center justify-content-between">
                    <h1 class="fw-normal fs-5">Total Harga :</h1>
                    <div class="d-flex justify-content-end align-items-end">
                        <h1 class="fw-normal fs-5 mt-2">Rp.
                            {{ number_format($pembeli->pesanan->jumlah_tiket * $pembeli->pesanan->harga, 0, ',', '.') }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="submit d-flex justify-content-end" style="margin-top: 70px;">

            <!-- <form action="{{ route('pembayaran') }}" method="POST">
                @csrf
                @method('POST')
                <div class="mb-3">
                    <input type="hidden" class="form-control" id="pembeli_id" name="pembeli_id"
                        value="{{ $pembeli->id }}" required>
                </div>
                <button type="button" class="btn btn-outline-warning me-5">
                    <a href="{{ url('/detail-tiket/{pesananId}') }}"
                        class="text-decoration-none text-warning">Kembali</a>
                </button>
                <button type="submit" id="pay-button" class="btn btn-outline-primary">Checkout</button>
            </form> -->

            <button id="pay-button" class="btn btn-outline-primary">Checkout</button>

            <form action="{{route('midtrans.callback')}}" id="submit_form" method="POST">
                @csrf
                <input type="hidden" name="json" id="json_callback">
            </form>

            <script type="text/javascript">
                document.getElementById('pay-button').onclick = function() {
                    // alert('Button clicked'); // Untuk memastikan tombol ditekan

                    // Kirim email sebelum pop-up Midtrans muncul
                    $.ajax({
                        url: "{{ route('send.email') }}",
                        method: "POST",
                        data: {
                            pembeli_id: "{{ $pembeli->id }}",
                            paymentUrl: "{{ $pembeli->paymentUrl->redirect_url }}",
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // alert('Email sent'); // Untuk memeriksa apakah email dikirim
                            if (response.success) {
                                // Jika email berhasil dikirim, munculkan pop-up Midtrans
                                window.snap.pay('{{ $snapToken }}', {
                                    onSuccess: function(result) {
                                        alert("Payment success!");
                                        console.log(result);
                                        send_response_to_form(result);
                                    },
                                    onPending: function(result) {
                                        alert("Waiting for your payment!");
                                        console.log(result);
                                        send_response_to_form(result);
                                    },
                                    onError: function(result) {
                                        alert("Payment failed!");
                                        console.log(result);
                                        send_response_to_form(result);
                                    },
                                });
                            } else {
                                alert('Failed to send email. Please try again.');
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred while sending email: ' + error);
                        }
                    });
                };

                function send_response_to_form(result){
                    document.getElementById('json_callback').value = JSON.stringify(result);
                    $('#submit_form').submit();
                }
            </script>
        </div>


    </section>
</x-layout>