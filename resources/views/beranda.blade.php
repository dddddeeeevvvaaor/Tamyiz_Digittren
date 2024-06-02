<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akademi Tamyiz Online</title>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/slider.css')}}">
    {{-- slider --}}
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- bootstrap 5 css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous" />
    <!-- custom css -->
    <!-- <link rel="stylesheet" href="style.css" /> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/img/icon.png') }}">

<body>
    <div class="navflex mx-0">
        <nav>
            <div class="logo">
                <h4>Akademi Tamyiz Online</h4>
            </div>

            <ul>
                <li class=""><a href="#beranda">Beranda</a></li>
                <li><a href="#tentang-kami">Tentang Kami</a></li>
                <li><a href="#jurusan">Jurusan</a></li>
                <li><a href="#testimoni">Testimoni</a></li>
                <li><a href="#hubungi-kami">Hubungi Kami</a></li>
                <li class="login-nav"><a class="text-white" href="{{ route('login') }}">Login</a></li>

            </ul>

            <div class="menu-toggle">
                <input type="checkbox">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </div>
    <div class="beranda" id="beranda">
        <div class="daftar">
            <div class="kosong" style="width: 100%; height: 150px;"></div>
            <div class="text-beranda bg-succss d-flex justify-content-center">
                <div class="text-center">
                    <h1 style="font-weight: bold"><br> Akademi Tamyiz Online</h1>
                    <p>Ayo! Segera daftarkan dirimu ke Akademi Tamyiz Online dengan cara <br> klik <b>Pendaftaran PPDB</b>
                        di
                        bawah ini!
                    </p>
                    <form action="{{ route('daftar') }}" method="">
                        <button class="daftar-btn">Pendaftaran Akademi Tamyiz Online</button>
                    </form>
                </div>
            </div>
            <div class="bg-wave">
                <img src="{{ asset('assets/img/waves.png') }}" alt="">
            </div>
        </div>
        <section class="pt-0 position-relative pull-top mb-5" id="jumbotron-card">
            <div class="container">
                <div class="rounded shadow mt-4 p-5 bg-white">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mt-0 text-center">
                            <h3 class="font-weight-bold text-capitalize h5 ">MOTTO</h3>
                            <p class="regular text-muted">Ilmu yang Amaliah, Amal yang Ilmiah, Akhlaqul Karimah</p>
                        </div>
                        <div class="col-lg-4 col-md-6 mt-5 mt-md-0 text-center">
                            <h3 class="font-weight-bold text-capitalize h5 ">AFIRMASI</h3>
                            <p class="regular text-muted">Padamu negeri - kami berjanji - lulus Wikrama siap membangun
                                negeri</p>
                        </div>
                        <div class="col-lg-4 col-md-12 mt-5 mt-lg-0 text-center">
                            <h3 class="font-weight-bold text-capitalize h5 ">ATITUDE</h3>
                            <p class="regular text-muted">Aku ada lingkunganku <br> bahagia</p>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <div class="tentang-kami d-flex align-items-center" id="tentang-kami">
            <img src="{{ asset('assets/img/ppdb.png') }}" alt="" class="image mx-auto">
            <div class="layout4">
                <div class="item p-4 d-flex align-items-center">
                    <div>
                        <h4>SMK Unggul dan Berprestasi Nasional</h4>
                        <p>SMK Wikrama Bogor Satu dari 20 SMK Penerima Penghargaan "SMK Unggul dan Berprestasi" di
                            Indonesia dari KEMENDIKBUD</p>
                    </div>

                </div>
                <div class="item">
                    <iframe width="100%" height="100%" class="" src="https://www.youtube.com/embed/_V8ZWxAcGY4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="item">
                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/_KYS5MT48tM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="item p-4 d-flex align-items-center">
                    <div>
                        <h4>Pembelajaran Tatap Muka Terbatas</h4>
                        <p>SMK Wikrama selalu menerapkan protokol kesehatan dengan ketat, jadi kamu gak perlu khawatir lagi !</p>
                    </div>
                </div>
            </div>
        </div>

        <div style="  background: #ebecff;">
            <h1 class="text-center pt-5 ">Jurusan</h1>
            <div class="jurusan-slider" id="jurusan">
                <section class="section-jurusan-slider">
                    <div class="swiper mySwiper container">
                        <div class="swiper-wrapper content">
                            <div class="swiper-slide card-custom">
                                <div class="card-content">
                                    <div class="image">
                                        <img src="{{ asset('assets/img/pplg.JPG') }}" alt="" width="40%" height="180px">
                                    </div>
                                    <div class="name-profession">
                                        <span class="name">PPLG</span>
                                        <span class="profession">Pengembangan Perangkat Lunak dan Gim</span>
                                    </div>
                                    <p>Kompetensi keahlian Manajemen Perkantoran Lembaga Binis memiliki keunggulan seperti
                                        juara
                                        II lomba keterampilan siswa bidang lomba sekretaris tingkat provinsi 2016 dan juara
                                        I
                                        lomba olimpiade sekretaris tingkat nasional 2017

                                </div>
                            </div>

                            <div class="swiper-slide card-custom">
                                <div class="card-content">
                                    <div class="image">
                                        <img src="{{ asset('assets/img/dkv.JPG') }}" alt="" width="40%" height="180px">

                                    </div>
                                    <div class="name-profession">
                                        <span class="name">DKV</span>
                                        <span class="profession">Desain Komunikasi Visual</span>
                                    </div>
                                    <p> Lulusan dapat memiliki kesempatan kerja yang luas dibidang periklanan, production
                                        house, radio & televisi, studio foto, percetakan grafis, corporate brand identity,
                                        penerbit majalan/Koran, dll.

                                </div>
                            </div>

                            <div class="swiper-slide card-custom">
                                <div class="card-content">
                                    <div class="image">
                                        <img src="{{ asset('assets/img/tjkt.JPG') }}" alt="" width="40%" height="180px">
                                    </div>
                                    <div class="name-profession">
                                        <span class="name">TJKT</span>
                                        <span class="profession">Teknik Jaringan dan Komputer</span>
                                    </div>
                                    <p> Kompetensi keahlian Teknik Komputer dan Jaringan sudah memiliki sertifikasi
                                        internasional seperti CNAP (Cisco Networking Academy Program) dan MCNA (Mikrotik
                                        Certified Network Associate).

                                </div>
                            </div>

                            <div class="swiper-slide card-custom">
                                <div class="card-content">
                                    <div class="image">
                                        <img src="{{ asset('assets/img/pmn.png') }}" alt="" width="40%" height="180px">
                                    </div>
                                    <div class="name-profession">
                                        <span class="name">PMN</span>
                                        <span class="profession">Pemasaran</span>
                                    </div>
                                    <p> Kompetensi keahlian Bisnis Daring dan Pemasaran memiliki kompetensi yang mirip
                                        dengan program Multimedia dan Perkantoran. Lulusan program ini diharuskan mampu
                                        membuat foto produk, desain, copy writing, dll.

                                </div>
                            </div>

                            <div class="swiper-slide card-custom">
                                <div class="card-content">
                                    <div class="image">
                                        <img src="{{ asset('assets/img/MPLB.JPG') }}" alt="" width="40%" height="180px">
                                    </div>
                                    <div class="name-profession">
                                        <span class="name">MPLB</span>
                                        <span class="profession">Manajemen Perkantoran dan Layanan Bisnis</span>
                                    </div>
                                    <p>Kompetensi keahlian Manajemen Perkantoran Lembaga Binis memiliki keunggulan seperti
                                        juara II lomba keterampilan siswa bidang lomba sekretaris tingkat provinsi 2016 dan
                                        juara I lomba olimpiade sekretaris tingkat nasional 2017</p>
                                </div>
                            </div>

                            <div class="swiper-slide card-custom">
                                <div class="card-content">
                                    <div class="image">
                                        <img src="{{ asset('assets/img/kln.jpg') }}" alt="" width="40%" height="180px">
                                    </div>
                                    <div class="name-profession">
                                        <span class="name">KLN</span>
                                        <span class="profession">Kuliner</span>
                                    </div>
                                    <p> Siswa jurusan Tata Boga mampu bekerja diberbagai bidang jasa boga seperti restoran,
                                        hotel, rumah sakit, katering sesuai dengan minat dan bakat yang telah dipelajari.
                                    </p>
                                </div>
                            </div>

                            <div class="swiper-slide card-custom">
                                <div class="card-content">
                                    <div class="image">
                                        <img src="{{ asset('assets/img/htl.JPG') }}" alt="" width="40%" height="180px">
                                    </div>


                                    <div class="name-profession">
                                        <span class="name">HTL</span>
                                        <span class="profession">Hotel</span>
                                    </div>


                                    <p>Siswa jurusan Perhotelan akan mampu bekerja di departemen yang ada di hotel dengan
                                        kompetensi yang mereka pelajari.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </section>
            </div>
        </div>

        {{-- testimoni --}}
        <div id="testimoni" style="background-color: #1e1e2f; padding-bottom:50px">
            <h1 class="text-center pt-5 text-white">Testimoni</h1>
            <div class="container-timeline">
                <div class="timeline">
                    <div class="timeline-container primary">
                        <div class="timeline-icon">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="timeline-body">
                            <h4 class="timeline-title"><span class="badge">Akhmad Munito</span></h4>
                            <p>Maju Terus Wikrama, dengan sekolah di Wikrama saya dibekali ilmu yg sangat bermanfaat dan
                                akhlakul karimah bisa langsung bisa bersaing ke dunia kerja di era modern ini</p>
                            </p>
                            <p class="timeline-subtitle">2000</p>
                        </div>
                    </div>
                    <div class="timeline-container primary">
                        <div class="timeline-icon">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="timeline-body">
                            <h4 class="timeline-title"><span class="badge">Lutfi Hakim</span></h4>
                            <p>TSMK Wikrama bukan hanya menjadikan siswanya untuk masuk ke dunia kerja, melainkan
                                melebihi dari apa yang dibutuhkan di dunia kerja pada umumnya.</p>
                            <p class="timeline-subtitle">2011</p>
                        </div>
                    </div>
                    <div class="timeline-container primary">
                        <div class="timeline-icon">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="timeline-body">
                            <h4 class="timeline-title"><span class="badge">Kamaludin</span></h4>
                            <p>Menerapkan sistem pembelajaran yang baik, efektif dan berbasis TI yang sangat memudahkan
                                siswa.</p>
                            <p class="timeline-subtitle">2016</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="background-color:#; padding-top:100px; padding-bottom: 100px">
            <div class="container card " id="hubungi-kami">
                <div class="content card-body">
                    <div class="left-side">
                        <div class="address details">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="topic">Alamat</div>
                            <div class="text-one">Jl. Raya Wangun Kelurahan Sindangsari Bogor Timur 16720</div>
                        </div>
                        <div class="phone details">
                            <i class="fas fa-phone-alt"></i>
                            <div class="topic">Telepon</div>
                            <div class="text-one">
                                <a target="_blank" href="https://wa.me/6281909242411">CS: hubungi kami <span style="color:#1e1e2f">(klik disini)</span>
                                </a>
                            </div>
                        </div>
                        <div class="email details">
                            <i class="fas fa-envelope"></i>
                            <div class="topic">Email</div>
                            <div class="text-one">prohumasi@smkwikrama.net</div>
                        </div>
                    </div>
                    <div class="right-side">
                        <div class="topic-text">Hubungi Kami</div>
                        <p>Anda dapat mengirimi kami pesan dari sini.</p>
                        <form action="https://formspree.io/f/xgebewdb" method="POST">
                            <div class="input-box">
                                <input type="text" placeholder="Nama Lengkap" name="nama_pengirim">
                            </div>
                            <div class="input-box">
                                <input type="email" placeholder="Email" name="email_pengirim">
                            </div>
                            <div class="input-box message-box">
                                <textarea id="" cols="30" rows="10" placeholder="Pesan" name="pesan_pengirim"></textarea>
                            </div>
                            <div class="button">
                                <button type="submit" class="btn">Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="pg-footer">
            <footer class="footer">
                <svg class="footer-wave-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 100" preserveAspectRatio="none">
                    <path class="footer-wave-path" d="M851.8,100c125,0,288.3-45,348.2-64V0H0v44c3.7-1,7.3-1.9,11-2.9C80.7,22,151.7,10.8,223.5,6.3C276.7,2.9,330,4,383,9.8 c52.2,5.7,103.3,16.2,153.4,32.8C623.9,71.3,726.8,100,851.8,100z">
                    </path>
                </svg>
                <div class="footer-content">
                    <div class="footer-content-column">
                        <div class="footer-logo">
                            <a class="footer-logo-link" href="#">
                                <span class="hidden-link-text">Akademi Tamyiz Online</span>
                                <h1> Akademi Tamyiz Online </h1>
                            </a>
                        </div>
                    </div>
                    <div class="footer-content-column">
                        <div class="footer-menu">
                            <h2 class="footer-menu-name"> Tautan</h2>
                            <ul id="menu-quick-links" class="footer-menu-list">
                                <li id="menu-item-168092" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-168092">
                                    <a rel="noopener noreferrer" href="#beranda">Beranda</a>
                                </li>
                                <li id="menu-item-167418" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-167418">
                                    <a rel="noopener noreferrer" href="#jurusan">Jurusan</a>
                                </li>
                                <li id="menu-item-167954" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-167954">
                                    <a href="#tentang-kami">Tentang Kami</a>
                                </li>
                                <li id="menu-item-167423" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-167423">
                                    <a href="#testimoni">Testimoni</a>
                                </li>
                                <li id="menu-item-167955" class="menu-item menu-item-type-post_type_archive menu-item-object-customer menu-item-167955">
                                    <a href="#hubungi-kami">Hubungi Kami</a>
                                </li>
                                <li id="menu-item-170700" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-170700">
                                    <a href="/login">Login</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="footer-content-column">
                        <div class="footer-call-to-action">
                            <h2 class="footer-call-to-action-title"> Alamat</h2>
                            <p class="footer-call-to-action-description"> Alamat
                                Jl. Raya Wangun
                                Kelurahan Sindangsari
                                Bogor Timur 16720</p>
                        </div>
                        <div class="footer-call-to-action">
                            <h2 class="footer-call-to-action-title"> Kontak Kami</h2>
                            <p class="footer-call-to-action-link-wrapper"> <a class="footer-call-to-action-link" href="tel:0251-8242411" target="_self">0251-8242411 </a></p>
                        </div>
                    </div>
                    <div class="footer-social-links"> <svg class="footer-social-amoeba-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 236 54">
                            <path class="footer-social-amoeba-path" d="M223.06,43.32c-.77-7.2,1.87-28.47-20-32.53C187.78,8,180.41,18,178.32,20.7s-5.63,10.1-4.07,16.7-.13,15.23-4.06,15.91-8.75-2.9-6.89-7S167.41,36,167.15,33a18.93,18.93,0,0,0-2.64-8.53c-3.44-5.5-8-11.19-19.12-11.19a21.64,21.64,0,0,0-18.31,9.18c-2.08,2.7-5.66,9.6-4.07,16.69s.64,14.32-6.11,13.9S108.35,46.5,112,36.54s-1.89-21.24-4-23.94S96.34,0,85.23,0,57.46,8.84,56.49,24.56s6.92,20.79,7,24.59c.07,2.75-6.43,4.16-12.92,2.38s-4-10.75-3.46-12.38c1.85-6.6-2-14-4.08-16.69a21.62,21.62,0,0,0-18.3-9.18C13.62,13.28,9.06,19,5.62,24.47A18.81,18.81,0,0,0,3,33a21.85,21.85,0,0,0,1.58,9.08,16.58,16.58,0,0,1,1.06,5A6.75,6.75,0,0,1,0,54H236C235.47,54,223.83,50.52,223.06,43.32Z">
                            </path>
                        </svg>

                    </div>
                </div>
                <div class="footer-copyright">
                    <div class="footer-copyright-wrapper">
                        <p class="footer-copyright-text">
                            <a class="footer-copyright-link" href="#" target="_self"> ©2021-22. | Akademi Tamyiz Online
                                | All
                                rights reserved. </a>
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 2,
        spaceBetween: 30,
        slidesPerGroup: 2,
        loop: true,
        loopFillGroupWithBlank: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
</script>

</html>