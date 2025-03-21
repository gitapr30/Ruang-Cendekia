<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>RuangCendekia</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
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

  <!-- =======================================================
  * Template Name: eNno
  * Template URL: https://bootstrapmade.com/enno-free-simple-bootstrap-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      <a href="" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        {{-- <img src="{{ $change->logo }}" alt=""> <!-- perubahan vira --> --}}
        <h1 class="sitename">{{ $change->nama_website }}</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Beranda</a></li>
          <li><a href="#about">Tentang</a></li>
          <li><a href="#services">Rekomendasi</a></li>
          <li><a href="#testimonials">Ulasan</a></li>
          <li><a href="#contact">Informasi</a></li>
        </ul>
      </nav>

      <a class="btn-getstarted" href="{{ route('login') }}">Masuk</a>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="fade-up">
            <h1>Selamat Datang di {{ $change->nama_website }}</h1> <!-- perubahan vira -->
            <p>Mari baca buku untuk tingkatkan ilmu</p>
            <div class="d-flex">
              <a href="#about" class="btn-get-started">Mulai</a>
            </div>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="100">
            <img src="assets/img/ornamen.svg" class="img-fluid animated" alt="">
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Tentang Kami<br></span>
        <h2>Tentang</h2>
        <p>Pelajari lebih lanjut tentang siapa kami, apa yang kami lakukan, dan bagaimana kami dapat membantu Anda.</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">
          <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="100">
            <img src="{{ asset('storage/' . $change->image) }}" class="img-fluid" alt=""> <!-- perubahan vira -->
          </div>
          <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="200">
            <h3>{{ $change->tittle }}</h3> <!-- perubahan vira -->
            <p class="fst-italic">
              {{ $change->description }} <!-- perubahan vira -->
            </p>
            <ul>
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

    <!-- Stats Section -->
    <section id="stats" class="stats section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6 mx-auto">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="{{ $totalUsers }}" data-purecounter-duration="1"
                class="purecounter"></span>
              <p>Pengguna</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 mx-auto">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="{{ $totalBooks }}" data-purecounter-duration="1"
                class="purecounter"></span>
              <p>Koleksi Buku</p>
            </div>
          </div><!-- End Stats Item -->

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

    <!-- Services Section -->
    <section id="services" class="services section light-background">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <span>Rekomendasi</span>
            <h2>Rekomendasi</h2>
            <p>Temukan buku terbaik pilihan kami untuk menambah wawasan dan inspirasi Anda!</p>
        </div><!-- End Section Title -->

        <div class="container">
            <div class="row gy-4">
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


    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Ulasan</span>
        <h2>Ulasan</h2>
        <p>Apa kata mereka?</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

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
            @foreach($reviews as $review)
        <div class="swiper-slide">
          <div class="testimonial-item" "="">
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

    <!-- Contact Section -->
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
            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3>Alamat</h3>
                <p>{{ $change->alamat }}</p> <!-- perubahan vira -->
              </div>
            </div>

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Hubungi Kami</h3>
                <p>{{ $change->no_telp }}</p> <!-- perubahan vira -->
              </div>
            </div>

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

      <!-- </div> -->
      </div>

      <!-- <div class="col-lg-7">
            <form action="" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
              <div class="row gy-4"> -->

      <!-- <div class="col-md-6">
                  <label for="name-field" class="pb-2">Nama</label>
                  <input type="text" name="name" id="name-field" class="form-control" required="">
                </div>

                <div class="col-md-6">
                  <label for="email-field" class="pb-2">Email</label>
                  <input type="email" class="form-control" name="email" id="email-field" required="">
                </div>

                <div class="col-md-12">
                  <label for="subject-field" class="pb-2">Subjek</label>
                  <input type="text" class="form-control" name="subject" id="subject-field" required="">
                </div>

                <div class="col-md-12">
                  <label for="message-field" class="pb-2">Pesan</label>
                  <textarea class="form-control" name="message" rows="10" id="message-field" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Memuat</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>

                  <button type="submit">Kirim Pesan</button>
                </div> -->

      <!-- </div>
            </form>
          </div>End Contact Form -->

      </div>

      </div>

    </section><!-- /Contact Section -->

  </main>

  <!-- <footer id="footer" class="footer">
    <div class="footer-newsletter">
        <div class="container">
            <div class="row align-items-center text-center text-md-start"> -->
  <!-- Brand -->
  <!-- <div class="col-md-6 col-lg-6 mb-3 mb-md-0"> -->
  <!-- <h4 class="footer-brand">RuangCendekia</h4> -->
  <!-- </div> -->

  <!-- Links -->
  <!-- <div class="col-md-6 col-lg-6 text-md-end text-lg-center">
                    <ul class="menu-nav list-inline d-flex flex-wrap justify-content-center  justify-content-md-end">
                        <li class="list-inline-item"><a href="#hero">Beranda</a></li>
                        <li class="list-inline-item mx-2 d-none d-md-inline">|</li>
                        <li class="list-inline-item"><a href="#about">Tentang</a></li>
                        <li class="list-inline-item mx-2 d-none d-md-inline">|</li>
                        <li class="list-inline-item"><a href="#services">Rekomendasi</a></li>
                        <li class="list-inline-item mx-2 d-none d-md-inline">|</li>
                        <li class="list-inline-item"><a href="#testimonials">Ulasan</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">RuangCendekia</strong> <span>All Rights Reserved</span></p>
        </div>
    </div>
</footer> -->
  <footer class="bg-body-tertiary text-center text-lg-start">
    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05); color: rgba(68, 68, 68, 0.75); font-size: 15px;">
     <!-- <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="#hero" class="nav-link px-2 text-body-secondary">Beranda</a></li>
      <li class="nav-item"><a href="#about" class="nav-link px-2 text-body-secondary">Tentang</a></li>
      <li class="nav-item"><a href="#services" class="nav-link px-2 text-body-secondary">Rekomendasi</a></li>
      <li class="nav-item"><a href="#testimonials" class="nav-link px-2 text-body-secondary">Ulasan</a></li>
      <li class="nav-item"><a href="#contact" class="nav-link px-2 text-body-secondary">Informasi</a></li>
     </ul> -->
      © 2025 Copyright:
      <span class="text-body" style="font-size: 15px;">RuangCendekia</span>
    </div>
    <!-- Copyright -->
  </footer>


  <!-- Scroll Top -->
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
