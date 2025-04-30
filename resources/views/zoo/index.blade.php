  <x-layout>
      <x-header></x-header>
      <!-- Carousel/ Hero Image -->
      <section class="container-fluid mx-0 px-0" style="position: relative; overflow: hidden; background-color: #fff;">
          <div class="d-flex align-items-center justify-content-between"
              style="height: 90vh; padding-left: 100px; padding-right: 50px;">
              <!-- Bagian Teks -->
              <div>
                  <h2 style="font-weight :300; line-height: 1.2; font-size:23px">
                      SELAMAT DATANG DI
                  </h2>
                  <h1 style="font-weight: 700; line-height: 1.2; font-size:40px">
                      Sistem Klasterisasi <br> Nilai TOEFL <br> UPA Bahasa <br> Politeknik Negeri Lhokseumawe
                  </h1>

              </div>

              <!-- Bagian Gambar -->
              <div>
                  <img src="{{ asset('assets/img/hero.jpg') }}"alt="" width="650px" />
              </div>
          </div>

          <!-- Ombak bawah -->
          <div style="position: absolute; bottom: 0; left: 0; width: 100%; overflow: hidden; line-height: 0;">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                  <path fill="#4146E6" fill-opacity="1"
                      d="M0,128L48,160C96,192,192,256,288,250.7C384,245,480,171,576,133.3C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                  </path>
              </svg>
          </div>
      </section>



      <!-- Feature -->
      <section class="container-fluid mt-4">
          <div class="row card-feature">
              <div class="col-lg-6 px-0 pe-lg-3 pe-0 pt-2">
                  <div class="card">
                      <img src="assets/img/penyu.jpg" class="" alt="..." height="250px">
                      <div class="card-img-overlay">
                          <h1 class="card-title text-light">PEMBELIAN TIKET</h1>
                          <button type="button" class="btn btn-outline-dark text-light"><a href="{{ route('tiket') }}"
                                  class="text-decoration-none text-light">BELI
                                  TIKET</a></button>
                      </div>
                  </div>
              </div>
              <div class="col-lg-6 px-0 ps-lg-3 ps-0 pt-2">
                  <div class="card">
                      <img src="assets/img/primata.jpg" class="" alt="..." height="250px">
                      <div class="card-img-overlay">
                          <h1 class="card-title text-light">PETA B-ZOO</h1>
                          <button type="button" class="btn btn-outline-dark text-light"><a href="{{ route('peta') }}"
                                  class="text-decoration-none text-light">LIHAT
                                  PETA</a></button>
                      </div>
                  </div>
              </div>
          </div>
      </section>



      </html>
  </x-layout>
