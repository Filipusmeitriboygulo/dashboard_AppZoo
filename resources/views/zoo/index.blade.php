  <x-layout>
      <x-header></x-header>
      <!-- Carousel/ Hero Image -->
      <section class="container-fluid mx-0 px-0 font">
          <div id="carouselExampleControls" class="carousel slide hero" data-bs-ride="carousel">
              <div class="carousel-indicators">
                  <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="0" class="active"
                      aria-current="true" aria-label="Slide 1"></button>
                  <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="1"
                      aria-label="Slide 2"></button>
                  <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="2"
                      aria-label="Slide 3"></button>
                  <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="3"
                      aria-label="Slide 4"></button>
                  <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="4"
                      aria-label="Slide 5"></button>
                  <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="5"
                      aria-label="Slide 6"></button>
                  <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="6"
                      aria-label="Slide 7"></button>
              </div>
              <div class="carousel-inner " style="max-height: 910px ; height: 100%; ">
                  <div class="carousel-item active">
                      <img src="{{ asset('assets/img/banner.jpg') }}" class="d-block w-lg-100 w-100" alt="..." />
                  </div>
                  <div class="carousel-item ">
                      <div class="card-utama ">
                          <img src="assets/img/harimau.jpg" class="d-block w-lg-100 w-100" alt="..." />
                          <div class="card-img-overlay text-tengah ">
                              <h1 class="card-title text-light">HARIMAU</h1>
                              <p class=" text-light">Zona Cakar</p>
                          </div>
                      </div>

                  </div>
                  <div class="carousel-item">
                      <div class="card-utama ">
                          <img src="assets/img/primata.jpg" class="d-block w-lg-100 w-100" alt="..." />
                          <div class="card-img-overlay text-tengah ">
                              <h1 class="card-title text-light">PRIMATA</h1>
                              <p class=" text-light">Zona Primata</p>
                          </div>
                      </div>
                  </div>
                  <div class="carousel-item">

                      <div class="card-utama ">
                          <img src="assets/img/flamingo.jpg" class="d-block w-lg-100 w-100" alt="..." />
                          <div class="card-img-overlay text-tengah ">
                              <h1 class="card-title text-light">FLAMINGO</h1>
                              <p class=" text-light">Zona Burung</p>
                          </div>
                      </div>
                  </div>
                  <div class="carousel-item">
                      <div class="card-utama ">
                          <img src="assets/img/mamalia.jpg" class="d-block w-lg-100 w-100" alt="..." />
                          <div class="card-img-overlay text-tengah ">
                              <h1 class="card-title text-light">GAJAH</h1>
                              <p class=" text-light">Zona Mamalia</p>
                          </div>
                      </div>
                  </div>
                  <div class="carousel-item">
                      <div class="card-utama ">
                          <img src="assets/img/tiger.jpg" class="d-block w-lg-100 w-100" alt="..." />
                          <div class="card-img-overlay text-tengah ">
                              <h1 class="card-title text-light">HARIMAU SUMATRA</h1>
                              <p class=" text-light">Zona Cakar</p>
                          </div>
                      </div>
                  </div>
                  <div class="carousel-item">
                      <div class="card-utama ">
                          <img src="assets/img/bird.jpg" class="d-block w-lg-100 w-100" alt="..." />
                          <div class="card-img-overlay text-tengah ">
                              <h1 class="card-title text-light">BURUNG PIPIT</h1>
                              <p class=" text-light">Zona Burung</p>
                          </div>
                      </div>
                  </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                  data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                  data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
              </button>
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
                          <button type="button" class="btn btn-outline-dark text-light"><a
                                  href="{{ route('tiket') }}" class="text-decoration-none text-light">BELI
                                  TIKET</a></button>
                      </div>
                  </div>
              </div>
              <div class="col-lg-6 px-0 ps-lg-3 ps-0 pt-2">
                  <div class="card">
                      <img src="assets/img/primata.jpg" class="" alt="..." height="250px">
                      <div class="card-img-overlay">
                          <h1 class="card-title text-light">PETA B-ZOO</h1>
                          <button type="button" class="btn btn-outline-dark text-light"><a
                                  href="{{ route('peta') }}" class="text-decoration-none text-light">LIHAT
                                  PETA</a></button>
                      </div>
                  </div>
              </div>
          </div>
      </section>



      <!-- Satwa -->
      <section class="container mt-5">
          <div class="row text-center">
              <div class="col-12 mb-3">
                  <p class="mt-3">SATWA</p>
                  <h5>ZONA SATWA B-ZOO</h5>
              </div>

          </div>
          <div class="row row-cols-1 row-cols-md-6 g-4 justify-content-center mb-5">
              <a href="detail_satwa.php" class="text-decoration-none text-dark">
                  <div class="col">
                      <div class="card-style h-100">
                          <img src="assets/img/bird.jpg" class="card-img-top rounded-circle" alt="...">
                          <div class="card-body text-center">
                              <h5 class="card-title my-3">Zona Burung</h5>
                          </div>
                      </div>
                  </div>
              </a>
              <a href="detail_satwa.php" class="text-decoration-none text-dark">
                  <div class="col">
                      <div class="card-style h-100">
                          <img src="assets/img/tiger4.jpg" class="card-img-top rounded-circle" alt="...">
                          <div class="card-body text-center">
                              <h5 class="card-title my-3">Zona Cakar</h5>
                          </div>
                      </div>
                  </div>
              </a>
              <a href="detail_satwa.php" class="text-decoration-none text-dark">
                  <div class="col">
                      <div class="card-style h-100">
                          <img src="assets/img/mamalia.jpg" class="card-img-top rounded-circle" alt="...">
                          <div class="card-body text-center">
                              <h5 class="card-title my-3">Zona Mamalia</h5>
                          </div>
                      </div>
                  </div>
              </a>
              <a href="detail_satwa.php" class="text-decoration-none text-dark">
                  <div class="col">
                      <div class="card-style h-100">
                          <img src="assets/img/zebra2.jpg" class="card-img-top rounded-circle" alt="...">
                          <div class="card-body text-center">
                              <h5 class="card-title my-3">Zona Petting Zoo</h5>
                          </div>
                      </div>
                  </div>
              </a>
              <a href="detail_satwa.php" class="text-decoration-none text-dark">
                  <div class="col">
                      <div class="card-style h-100">
                          <img src="assets/img/monkey2.jpg" class="card-img-top rounded-circle" alt="...">
                          <div class="card-body text-center">
                              <h5 class="card-title my-3">Zona Primata</h5>
                          </div>
                      </div>
                  </div>
              </a>
              <a href="detail_satwa.php" class="text-decoration-none text-dark">
                  <div class="col">
                      <div class="card-style h-100">
                          <img src="assets/img/reptil.jpg" class="card-img-top rounded-circle" alt="...">
                          <div class="card-body text-center">
                              <h5 class="card-title my-3">Zona Reptil</h5>
                          </div>
                      </div>
                  </div>
              </a>
          </div>
      </section>

      <!-- Fasilitas -->
      <section class="container-fluid mt-4 bg-body-secondary">
          <div class="row text-center">
              <div class="col-12 mb-3">
                  <p class="mt-5">FASILITAS KAMI</p>
                  <h5>FASILITAS UMUM</h5>
              </div>
          </div>
          <div class="container-lg">
              <div class="row row-cols-1 row-cols-md-4 g-4">
                  <div class="col">
                      <div class="card h-100 bg-body-secondary" style="border: none;">
                          <img src="assets/img/wahana1.jpg" class="card-img-top mt-3 w-25 rounded-circle"
                              alt="...">
                          <div class="card-body text-center">
                              <h5 class="card-title">Gerai Zoovenir</h5>
                              <p class="card-text">Lengkapi kunjungan sobat satwa dengan koleksi suvenir yang bisa
                                  sobat satwa dapatkan di Gerai Zoovenir</p>
                          </div>
                      </div>
                  </div>
                  <div class="col">
                      <div class="card h-100 bg-body-secondary" style="border: none;">
                          <img src="assets/img/wahana1.jpg " class="card-img-top mt-3 w-25 rounded-circle"
                              alt="...">
                          <div class="card-body text-center ">
                              <h5 class="card-title">Prestasi Edukasi Aves</h5>
                              <p class="card-text">Presentasi dan Edukasi Satwa (PES) Aves adalah sarana edukasi
                                  melalui pengenalan perilaku satwa burung layaknya</p>
                          </div>
                      </div>
                  </div>
                  <div class="col">
                      <div class="card h-100 bg-body-secondary" style="border: none;">
                          <img src="assets/img/tiger2.jpg" class="card-img-top mt-3  w-25 rounded-circle"
                              alt="...">
                          <div class="card-body text-center ">
                              <h5 class="card-title">Presentasi Edukasi Mamalia</h5>
                              <p class="card-text">Presentasi dan Edukasi Satwa (PES) Mamalia adalah sarana edukasi
                                  melalui pengenalan perilaku satwa mamalia layaknya </p>
                          </div>
                      </div>
                  </div>
                  <div class="col">
                      <div class="card h-100 bg-body-secondary" style="border: none;">
                          <img src="assets/img/wahana1.jpg" class="card-img-top mt-3 w-25 rounded-circle"
                              alt="...">
                          <div class="card-body text-center ">
                              <h5 class="card-title">Avros</h5>
                              <p class="card-text">Avros menyediakan aneka olahan buah seperti jus, salad buah, sup
                                  buah, lotis, serta sandwich, dan sosis bakar.</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>

      <!-- Wahana -->
      <section class="container-fluid mt-5">
          <div class="row text-left ms-5">
              <p class="">----------- Destinasi </p>
              <h3 class="">Wahana B-ZOO</h3>
          </div>
          <div class="row d-flex flex-wrap">
              <div id="carouselExampleControls" class="carousel slide mt-5" data-bs-ride="carousel">
                  <div class="carousel-inner">
                      <div class="carousel-item active">
                          <div class="row row-cols-1 row-cols-md-4 g-4">
                              <div class="col">
                                  <div class="card h-100">
                                      <img src="assets/img/wahana1.jpg" class="card-img-top card-img-fixed w-100"
                                          alt="...">
                                      <div class="card-img-overlay">
                                          <h1 class="card-title text-light">1</h1>
                                      </div>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="card h-100">
                                      <img src="assets/img/wahana2.jpg" class="card-img-top card-img-fixed w-100"
                                          alt="...">
                                      <div class="card-img-overlay">
                                          <h1 class="card-title text-light">2</h1>
                                      </div>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="card h-100">
                                      <img src="assets/img/wahana3.jpg" class="card-img-top card-img-fixed w-100"
                                          alt="...">
                                      <div class="card-img-overlay">
                                          <h1 class="card-title text-light">3</h1>
                                      </div>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="card h-100">
                                      <img src="assets/img/wahana4.jpg" class="card-img-top card-img-fixed w-100"
                                          alt="...">
                                      <div class="card-img-overlay">
                                          <h1 class="card-title text-light">4</h1>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="carousel-item">
                          <div class="row row-cols-1 row-cols-md-4 g-4">
                              <div class="col">
                                  <div class="card h-100">
                                      <img src="assets/img/wahana5.jpg" class="card-img-top card-img-fixed w-100"
                                          alt="...">
                                      <div class="card-img-overlay">
                                          <h1 class="card-title text-light">5</h1>
                                      </div>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="card h-100">
                                      <img src="assets/img/wahana6.jpg" class="card-img-top card-img-fixed w-100"
                                          alt="...">
                                      <div class="card-img-overlay">
                                          <h1 class="card-title text-light">6</h1>
                                      </div>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="card h-100">
                                      <img src="assets/img/wahana7.jpg" class="card-img-top card-img-fixed w-100"
                                          alt="...">
                                      <div class="card-img-overlay">
                                          <h1 class="card-title text-light">7</h1>
                                      </div>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="card h-100">
                                      <img src="assets/img/wahana8.jpg" class="card-img-top card-img-fixed w-100"
                                          alt="...">
                                      <div class="card-img-overlay">
                                          <h1 class="card-title text-light">8</h1>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                      data-bs-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Previous</span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                      data-bs-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Next</span>
                  </button>
              </div>

          </div>
      </section>
      <!-- script -->

      </html>
  </x-layout>
