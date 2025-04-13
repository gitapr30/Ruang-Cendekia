<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Meta tags dasar -->
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <!-- Judul website -->
  <title>RuangCendekia</title>

  <!-- Meta description dan keywords untuk SEO -->
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts dari Google Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/aos/aos.css" rel="stylesheet') }}">
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

  <!-- Template credits -->
  <!-- =======================================================
  * Template Name: eNno
  * Template URL: https://bootstrapmade.com/enno-free-simple-bootstrap-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">
  <!-- Header Section -->
  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
      <!-- Tombol toggle untuk menu mobile -->
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>

      <!-- Logo website -->
      <a href="" class="logo d-flex align-items-center me-auto">
        <!-- Opsi untuk menggunakan gambar logo -->
        {{-- <img src="{{ $change->logo }}" alt=""> <!-- perubahan vira --> --}}
        <h1 class="sitename">{{ $change->nama_website }}</h1>
      </a>

      <!-- Navigasi utama -->
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Beranda</a></li>
          <li><a href="#about">Tentang</a></li>
          <li><a href="#services">Rekomendasi</a></li>
          <li><a href="#testimonials">Ulasan</a></li>
          <li><a href="#contact">Informasi</a></li>
        </ul>
      </nav>

      <!-- Tombol login -->
      <a class="btn-getstarted" href="{{ route('login') }}">Masuk</a>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main">

    <!-- Hero Section - Bagian utama halaman -->
    <section id="hero" class="hero section light-background">
      <div class="container">
        <div class="row gy-4">
          <!-- Konten teks hero -->
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="fade-up">
            <h1>Selamat Datang di {{ $change->nama_website }}</h1> <!-- perubahan vira -->
            <p>Mari baca buku untuk tingkatkan ilmu</p>
            <div class="d-flex">
              <a href="#about" class="btn-get-started">Mulai</a>
            </div>
          </div>

          <!-- Gambar hero -->
          <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="100">
            <img src="assets/img/ornamen.svg" class="img-fluid animated" alt="">
          </div>
        </div>
      </div>
    </section><!-- /Hero Section -->

    <!-- About Section - Tentang kami -->
    <section id="about" class="about section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Tentang Kami<br></span>
        <h2>Tentang</h2>
        <p>Pelajari lebih lanjut tentang siapa kami, apa yang kami lakukan, dan bagaimana kami dapat membantu Anda.</p>
      </div><!-- End Section Title -->

      <div class="container">
        <div class="row gy-4">
          <!-- Gambar tentang kami -->
          <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="100">
            <img src="{{ asset('storage/' . $change->image) }}" class="img-fluid" alt=""> <!-- perubahan vira -->
          </div>

          <!-- Konten tentang kami -->
          <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="200">
            <h3>{{ $change->tittle }}</h3> <!-- perubahan vira -->
            <p class="fst-italic">
              {{ $change->description }} <!-- perubahan vira -->
            </p>
            <ul>
              <!-- Loop untuk menampilkan poin-poin konten -->
              @foreach (explode("\n", $change->content) as $item) <!-- perubahan vira -->
                <li><i class="bi bi-check2-all"></i> <span>{{ $item }}</span></li>
              @endforeach
            </ul>
            <p>
              {{ $change->footer }} <!-- perubahan vira -->
            </p>
          </div>
        </div>
      </div>
    </section><!-- /About Section -->

    <!-- Stats Section - Statistik -->
    <section id="stats" class="stats section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <!-- Statistik pengguna -->
          <div class="col-lg-3 col-md-6 mx-auto">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="{{ $totalUsers }}" data-purecounter-duration="1"
                class="purecounter"></span>
              <p>Pengguna</p>
            </div>
          </div><!-- End Stats Item -->

          <!-- Statistik koleksi buku -->
          <div class="col-lg-3 col-md-6 mx-auto">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="{{ $totalBooks }}" data-purecounter-duration="1"
                class="purecounter"></span>
              <p>Koleksi Buku</p>
            </div>
          </div><!-- End Stats Item -->

          <!-- Statistik kategori -->
          <div class="col-lg-3 col-md-6 mx-auto">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="{{ $totalCategories }}"
                data-purecounter-duration="1" class="purecounter"></span>
              <p>Kategori</p>
            </div>
          </div><!-- End Stats Item -->
        </div>
      </div>
    </section><!-- /Stats Section -->

    <!-- Services Section - Rekomendasi buku -->
    <section id="services" class="services section light-background">
        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <span>Rekomendasi</span>
            <h2>Rekomendasi</h2>
            <p>Temukan buku terbaik pilihan kami untuk menambah wawasan dan inspirasi Anda!</p>
        </div><!-- End Section Title -->

        <div class="container">
            <div class="row gy-4">
                <!-- Loop untuk menampilkan buku rekomendasi -->
                @foreach($recommendedBooks as $book)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item position-relative text-center">
                            <img src="{{ asset($book->image ?? 'images/default-book.jpg') }}" alt="{{ $book->title }}" class="img-fluid mb-3" style="max-height: 400px; object-fit: cover;">
                            <div class="stretched-link">
                                <h3>{{ $book->title }}</h3>
                            </div>
                            <p>{{ Str::limit($book->description, 100) }}</p>
                            <p><strong>Rating:</strong> {{ number_format($book->reviews_avg_rating, 1) }} ⭐</p>
                        </div>
                    </div><!-- End Service Item -->
                @endforeach
            </div>
        </div>
    </section><!-- /Services Section -->

    <!-- Testimonials Section - Ulasan pengguna -->
    <section id="testimonials" class="testimonials section light-background">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Ulasan</span>
        <h2>Ulasan</h2>
        <p>Apa kata mereka?</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <!-- Swiper untuk testimonial slider -->
        <div class="swiper init-swiper" data-speed="600" data-delay="5000"
          data-breakpoints="{ &quot;320&quot;: { &quot;slidesPerView&quot;: 1, &quot;spaceBetween&quot;: 40 }, &quot;1200&quot;: { &quot;slidesPerView&quot;: 3, &quot;spaceBetween&quot;: 40 } }">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 1,
                  "spaceBetween": 40
                },
                "1200": {
                  "slidesPerView": 3,
                  "spaceBetween": 20
                }
              }
            }
          </script>
          <div class="swiper-wrapper">
            <!-- Loop untuk menampilkan ulasan -->
            @foreach($reviews as $review)
              <div class="swiper-slide">
                <div class="testimonial-item">
                  <p>
                    <i class=" bi bi-quote quote-icon-left"></i>
                    <span>{{ $review->review }}</span>
                    <i class="bi bi-quote quote-icon-right"></i>
                  </p>
                  <img src="{{ asset('storage/' . $review->user->profile_image) }}" class="testimonial-img" alt="">
                  <h3>{{ $review->user->name }}</h3>
                  <h4>{{ $review->user->role }}</h4>
                </div>
              </div><!-- End testimonial item -->
            @endforeach
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section><!-- /Testimonials Section -->

    <!-- Contact Section - Informasi kontak -->
    <section id="contact" class="contact section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Informasi</span>
        <h2>Informasi</h2>
        <p>Hubungi kami untuk informasi lebih lanjut atau bantuan terkait layanan kami.</p>
      </div><!-- End Section Title -->

      <div class="container p-4 border rounded" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-start">
          <!-- Kolom Informasi -->
          <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
            <!-- Alamat -->
            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3>Alamat</h3>
                <p>{{ $change->alamat }}</p> <!-- perubahan vira -->
              </div>
            </div>

            <!-- Nomor telepon -->
            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Hubungi Kami</h3>
                <p>{{ $change->no_telp }}</p> <!-- perubahan vira -->
              </div>
            </div>

            <!-- Email -->
            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Email</h3>
                <p>{{ $change->email }}</p> <!-- perubahan vira -->
              </div>
            </div>
          </div>

          <!-- Kolom Maps -->
          <div class="col-lg-6 col-md-6 col-sm-12">
            <iframe
              src="{{ $change->maps }}"
              frameborder="0" style="border:0; width: 100%; height: 250px;" allowfullscreen="" loading="lazy"
              referrerpolicy="no-referrer-when-downgrade">
            </iframe> <!-- perubahan vira -->
          </div>
        </div>
      </div>
    </section><!-- /Contact Section -->

  </main>

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start">
    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05); color: rgba(68, 68, 68, 0.75); font-size: 15px;">
      © 2025 Copyright:
      <span class="text-body" style="font-size: 15px;">RuangCendekia</span>
    </div>
    <!-- Copyright -->
  </footer>

  <!-- Scroll Top Button -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

</body>
</html>
