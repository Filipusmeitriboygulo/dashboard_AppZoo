  <x-layout>
      <x-header></x-header>
      <!-- Carousel/ Hero Image -->
      <section class="container-fluid mx-0 px-0" style="position: relative; overflow: hidden; background-color: #fff;">
          <div class="d-flex align-items-center justify-content-between"
              style="height: 90vh; padding-bottom:150px; padding-left: 100px; padding-right: 50px; ">
              <!-- Bagian Teks -->
              <div>
                  <h2 style="font-weight :300; line-height: 1.2; font-size:23px">
                      SELAMAT DATANG DI
                  </h2>
                  <h1 style="font-weight: 700; line-height: 1.2; font-size:40px">
                      Sistem Klasterisasi Menggunakan Algoritma Fuzzy C-Means <br> Nilai TOEFL <br> UPA Bahasa <br>
                      Politeknik Negeri Lhokseumawe
                  </h1>

              </div>

              <!-- Bagian Gambar -->
              <div>
                  <img src="{{ asset('assets/img/hero.jpg') }}"alt="" width="750px" />
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
      <section class="container-fluid mt-4 p-4">
          <div class="row card-feature">
              <div class="col-lg-8 px-0 pe-lg-3 pe-0 pt-2">
                  <div class="card">
                      <div class="card-img">
                          <h3 class="card-title p-3">SISTEM KLASTERISASI NILAI TOEFL BERBASIS FUZZY C-MEANS </h3>
                          <p style="margin: 20px; text-align: justify;">
                              Politeknik Negeri Lhokseumawe telah mengembangkan sistem analisis berbasis Fuzzy C-Means
                              untuk memetakan kemampuan bahasa Inggris mahasiswa melalui nilai TOEFL. Dengan teknik
                              klasterisasi ini, kami mampu mengelompokkan mahasiswa berdasarkan pola kemahiran di tiga
                              aspek utama: Listening, Structure, dan Reading.
                              <br>Sistem ini membantu UPA Bahasa untuk:
                          <ul>
                              <li>Membantu dan memudahkan UPA Bahasa Politeknik Negeri Lhokseumawe dalam merancang
                                  kegiatan
                                  peningkatan bahasa Inggris berdasarkan klasterisasi nilai TOEFL mahasiswa</li>.
                              <li>
                                  Membantu Jurusan yang ada di Politeknik Negeri Lhokseumawe mengidentifikasi pola
                                  distribusi Nilai TOEFL mahasiswa .
                              </li>
                          </ul>
                          </p>
                      </div>

                  </div>
              </div>
              <div class="col-lg-4 px-0 ps-lg-3 ps-0 pt-2">
                  <div class="card">
                      <div class="card-img">
                          <h3 class="card-title p-3">FUZZY C-MEANS</h3>
                          <p style="margin: 20px; text-align: justify;">
                              Fuzzy C-Means adalah teknik pengelompokan
                              data dimana keberadaan tiap-tiap titik data dalam
                              suatu cluster ditentukan oleh derajat keanggotaan.
                              Fuzzy C-Means adalah algoritma pengelompokan
                              yang terawasi, karena pada algoritma ini jumlah
                              cluster yang akan dibentuk perlu diketahui terlebih
                              dahulu. konsep dasarnya adalah menentukan pusat
                              kelompok yang akan menandai lokasi rata-rata
                              untuk tiap-tiap cluster
                          </p>
                      </div>
                  </div>
              </div>
          </div>
      </section>



      </html>
  </x-layout>
