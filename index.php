<?php
include 'koneksi.php';
$query_visi = "SELECT isi FROM visimisi WHERE nama = 'visi' LIMIT 1";
$query_misi = "SELECT isi FROM visimisi WHERE nama = 'misi' LIMIT 1";

$result_visi = pg_query($koneksi, $query_visi);
$result_misi = pg_query($koneksi, $query_misi);

$data_visi = pg_fetch_assoc($result_visi);
$data_misi = pg_fetch_assoc($result_misi);
$isi_visi = $data_visi['isi'] ?? "Visi belum tersedia di database.";
$isi_misi = $data_misi['isi'] ?? "Misi belum tersedia di database.";
$batas_karakter = 300;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Charitize - Charity Organization Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@600;700&family=Open+Sans&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid bg-secondary top-bar wow fadeIn" data-wow-delay="0.1s">
        <div class="row align-items-center h-100">
            <div class="col-lg-4 text-center text-lg-start">
                <a href="index.html">
                    <div class="container-fluid bg-secondary top-bar wow fadeIn" data-wow-delay="0.1s">
                        <div class="row align-items-center h-100">
                            <div class="col-lg-4 text-center text-lg-start">
                                <a href="index.html">
                                    <img class="img-fluid" 
                                        src="../../IVSS-LAB/admin/assets/img/ivss_logo_no-desc.png" 
                                        alt="Logo"
                                        style="max-width: 100px; margin-left: -100px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid bg-secondary px-0 wow fadeIn" data-wow-delay="0.1s">
        <div class="nav-bar">
            <nav class="navbar navbar-expand-lg bg-primary navbar-dark px-4 py-lg-0">
                <h4 class="d-lg-none m-0">Menu</h4>
                <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav me-auto">
                        <a href="index.html" class="nav-item nav-link active">Home</a>
                        <a href="about.html" class="nav-item nav-link">About</a>
                        <a href="service.html" class="nav-item nav-link">Service</a>
                        <a href="donation.html" class="nav-item nav-link">Donation</a>
                        <div class="nav-item dropdown">
                            <a href="#!" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu bg-light m-0">
                                <a href="event.html" class="dropdown-item">Event</a>
                                <a href="feature.html" class="dropdown-item">Feature</a>
                                <a href="team.html" class="dropdown-item">Our Team</a>
                                <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                                <a href="404.html" class="dropdown-item">404 Page</a>
                            </div>
                        </div>
                        <a href="contact.html" class="nav-item nav-link">Contact</a>
                    </div>
                    <div class="d-none d-lg-flex ms-auto">
                        <a class="btn btn-square btn-dark ms-2" href="#!"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-square btn-dark ms-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-dark ms-2" href="#!"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->


    <!-- Carousel Start -->
    <div class="container-fluid p-0 wow fadeIn" data-wow-delay="0.1s">
        <div class="owl-carousel header-carousel py-5">
            <div class="container py-5">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="carousel-text">
                            <h3 class="display-1 text-uppercase mb-3">Selamat Datang di Lab IVSS</h3>
                            <p class="fs-5 mb-5">We believe in creating opportunities and empowering communities through
                                education, healthcare, and sustainable development.</p>
                            <div class="d-flex">
                                <a class="btn btn-primary py-3 px-4 me-3" href="#!">Login</a>
                                <a class="btn btn-secondary py-3 px-4" href="#!">Register</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="carousel-img">
                            <img class="w-100" src="img/carousel-1.jpg" alt="Image">
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="container py-5">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="carousel-text">
                            <h3 class="display-1 text-uppercase mb-3">Selamat Datang di Lab IVSS</h3>
                            <p class="fs-5 mb-5">No one should go to bed hungry. Your support helps us bring smiles,
                                hope, and a brighter future to those in need.</p>
                            <div class="d-flex mt-4">
                                <a class="btn btn-primary py-3 px-4 me-3" href="#!">Donate Now</a>
                                <a class="btn btn-secondary py-3 px-4" href="#!">Join Us Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="carousel-img">
                            <img class="w-100" src="img/carousel-2.jpg" alt="Image">
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <!-- Carousel End -->

    <!-- Fokus Riset Start -->
    <section id="fokus-riset" class="py-5 bg-light">
        <div class="container">

            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-3">Fokus Riset</h2>
                    <div style="width: 100px; height: 3px; background-color: #FFBC3B; margin: 0 auto 30px;"></div>
                </div>
            </div>

            <div class="row justify-content-center g-4">

                <!-- Intelligent Vision -->
                <div class="col-auto mx-4">
                    <div class="d-flex align-items-center position-relative">
                        <div class="shadow-sm rounded-pill py-3 ps-4 pe-5"
                            style="background-color: #1A1A37; color: #ffffff; z-index: 1;">
                            <span class="fw-bold fs-5">Intelligent Vision</span>
                        </div>

                        <div class="rounded-circle text-dark d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 70px; height: 70px; font-size: 36px; 
                            position: absolute; right: -25px; top: 50%; transform: translateY(-50%); 
                            z-index: 2; background-color: #FFBC3B">
                            <i class="lni lni-eye"></i>
                        </div>
                    </div>
                </div>

                <!-- Smart System -->
                <div class="col-auto mx-4">
                    <div class="d-flex align-items-center position-relative">
                        <div class="shadow-sm rounded-pill py-3 ps-4 pe-5"
                            style="background-color: #1A1A37; color: #ffffff; z-index: 1;">
                            <span class="fw-bold fs-5">Smart System</span>
                        </div>

                        <div class="rounded-circle text-dark d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 70px; height: 70px; font-size: 36px; 
                            position: absolute; right: -25px; top: 50%; transform: translateY(-50%); 
                            z-index: 2; background-color: #FFBC3B">
                            
                            <!-- Icon Smart System -->
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.61288 2.15759C10.4745 1.68444 12.3561 2.30712 13.5784 3.61827C14.5156 3.15767 15.6338 3.06578 16.6928 3.45537C18.4831 4.11402 19.5117 5.92951 19.2442 7.74502C20.4227 8.53784 21.1997 9.88466 21.1997 11.414C21.1997 13.8546 19.2212 15.8331 16.7806 15.8331H16.4983V18.249C16.4983 18.6632 16.8341 18.999 17.2483 18.999H17.7386C17.9667 18.6955 18.3297 18.4992 18.7385 18.4992H18.7485C19.4389 18.4992 19.9985 19.0589 19.9985 19.7492C19.9985 20.4396 19.4389 20.9992 18.7485 20.9992H18.7385C18.3295 20.9992 17.9663 20.8027 17.7383 20.499H17.2483C16.0057 20.499 14.9983 19.4916 14.9983 18.249V15.8331H12.7483V19.7417C13.0576 19.9692 13.2583 20.3358 13.2583 20.7492C13.2583 21.4396 12.6986 21.9992 12.0083 21.9992H11.9983C11.3079 21.9992 10.7483 21.4396 10.7483 20.7492C10.7483 20.3403 10.9447 19.9772 11.2483 19.7491V15.8331H8.99829V18.249C8.99829 19.4916 7.99093 20.499 6.74829 20.499H6.25857C6.03053 20.8027 5.66736 20.9992 5.25829 20.9992H5.24829C4.55793 20.9992 3.99829 20.4396 3.99829 19.7492C3.99829 19.0589 4.55793 18.4992 5.24829 18.4992H5.25829C5.66714 18.4992 6.03014 18.6955 6.2582 18.999H6.74829C7.1625 18.999 7.49829 18.6632 7.49829 18.249V15.8331H7.21593C4.77535 15.8331 2.79688 13.8546 2.79688 11.414C2.79688 9.85313 3.60618 8.48241 4.82582 7.69667C4.51439 5.19526 6.10638 2.79463 8.61288 2.15759Z"
                                    fill="#343C54" />
                            </svg>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Fokus Riset End -->

    <!-- About Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="row g-5 align-items-center">

            <!-- Logo / Image -->
            <div class="col-lg-6" data-wow-delay="0.2s">
                <img class="img-fluid" 
                     src="../../IVSS-LAB/admin/assets/img/ivss_logo_no-desc.png" 
                     alt="Image"
                     style="max-width:500px;">
            </div>

            <!-- Content -->
            <div class="col-lg-6">

                <!-- Section Title -->
                <p class="section-title bg-white text-start text-primary pe-3">
                    Profil Laboratorium
                </p>

                <!-- Main Heading -->
                <h1 class="display-6 mb-4 wow fadeIn" data-wow-delay="0.2s">
                    Join Hands, Change the World
                </h1>

                <!-- Description -->
                <p class="mb-4 wow fadeIn" data-wow-delay="0.3s">
                    Laboratorium Visi Cerdas dan Sistem Cerdas merupakan pusat riset dan pengembangan di 
                    bawah Jurusan Teknologi Informasi Politeknik Negeri Malang yang berfokus pada bidang 
                    intelligent vision dan smart system. Laboratorium ini menjadi wadah bagi dosen
                    dan mahasiswa untuk melakukan penelitian, pembelajaran, serta pelatihan dalam pengembangan 
                    sistem cerdas berbasis pengolahan citra dan kecerdasan buatan. Penelitian di laboratorium ini 
                    mengintegrasikan computer vision, AI, dan IoT untuk menciptakan solusi inovatif yang mampu 
                    mengenali, menganalisis, serta merespon lingkungan secara mandiri.
                </p>

                <!-- Visi & Misi Section -->
                
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-12">
                                <div class="about-five-content">

                                    <!-- Subtitle & Title -->
                                    <h6 class="small-title text-lg">VISI & MISI</h6>
                                    <h2 class="main-title fw-bold">
                                        LABORATORIUM INTELLIGENT AND SMART SYSTEM
                                    </h2>

                                    <!-- Tabs -->
                                    <div class="about-five-tab">
                                        <nav style="background-color: #1A1A37;">
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <button class="nav-link active tab-custom-color" 
                                                        id="nav-vision-tab"
                                                        data-bs-toggle="tab" 
                                                        data-bs-target="#nav-vision" 
                                                        type="button" 
                                                        role="tab"
                                                        aria-controls="nav-vision" 
                                                        aria-selected="true">
                                                    Visi
                                                </button>
                                                <button class="nav-link tab-custom-color" 
                                                        id="nav-history-tab" 
                                                        data-bs-toggle="tab"
                                                        data-bs-target="#nav-history" 
                                                        type="button" 
                                                        role="tab"
                                                        aria-controls="nav-history" 
                                                        aria-selected="false">
                                                    Misi
                                                </button>
                                            </div>
                                        </nav>

                                        <!-- Tab Content -->
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" 
                                                 id="nav-vision" 
                                                 role="tabpanel"
                                                 aria-labelledby="nav-vision-tab">
                                                <p><?php echo nl2br($isi_visi); ?></p>
                                            </div>
                                            <div class="tab-pane fade" 
                                                 id="nav-history" 
                                                 role="tabpanel"
                                                 aria-labelledby="nav-history-tab">
                                                <p><?php echo nl2br($isi_misi); ?></p>
                                            </div>
                                        </div>
                                    </div> <!-- End Tabs -->

                                </div>
                            </div>
                        </div>
                    </div>


            </div> <!-- End Content Column -->

        </div> <!-- End Row -->
    </div> <!-- End Container -->
</div>
<!-- About End -->


    <!-- Service Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-12 col-lg-4 col-xl-3 wow fadeIn" data-wow-delay="0.1s">
                    <div class="service-title">
                        <h1 class="display-6 mb-4">What We Do for Those in Need.</h1>
                        <p class="fs-5 mb-0">We work to bring smiles, hope, and a brighter future to those in need.</p>
                    </div>
                </div>
                <div class="col-md-12 col-lg-8 col-xl-9">
                    <div class="row g-5">
                        <div class="col-sm-6 col-md-4 wow fadeIn" data-wow-delay="0.1s">
                            <div class="service-item h-100">
                                <div class="btn-square bg-light mb-4">
                                    <i class="fa fa-droplet fa-2x text-secondary"></i>
                                </div>
                                <h3>Pure Water</h3>
                                <p class="mb-2">We’re creating programs that address urgent needs while fostering
                                    long-term solutions for sustainable change.</p>
                                <a href="#!">Read More</a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 wow fadeIn" data-wow-delay="0.3s">
                            <div class="service-item h-100">
                                <div class="btn-square bg-light mb-4">
                                    <i class="fa fa-hospital fa-2x text-secondary"></i>
                                </div>
                                <h3>Health Care</h3>
                                <p class="mb-2">We’re creating programs that address urgent needs while fostering
                                    long-term solutions for sustainable change.</p>
                                <a href="#!">Read More</a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 wow fadeIn" data-wow-delay="0.5s">
                            <div class="service-item h-100">
                                <div class="btn-square bg-light mb-4">
                                    <i class="fa fa-hands-holding-child fa-2x text-secondary"></i>
                                </div>
                                <h3>Social Care</h3>
                                <p class="mb-2">We’re creating programs that address urgent needs while fostering
                                    long-term solutions for sustainable change.</p>
                                <a href="#!">Read More</a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 wow fadeIn" data-wow-delay="0.1s">
                            <div class="service-item h-100">
                                <div class="btn-square bg-light mb-4">
                                    <i class="fa fa-bowl-food fa-2x text-secondary"></i>
                                </div>
                                <h3>Healthy Food</h3>
                                <p class="mb-2">We’re creating programs that address urgent needs while fostering
                                    long-term solutions for sustainable change.</p>
                                <a href="#!">Read More</a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 wow fadeIn" data-wow-delay="0.3s">
                            <div class="service-item h-100">
                                <div class="btn-square bg-light mb-4">
                                    <i class="fa fa-school-flag fa-2x text-secondary"></i>
                                </div>
                                <h3>Primary Education</h3>
                                <p class="mb-2">We’re creating programs that address urgent needs while fostering
                                    long-term solutions for sustainable change.</p>
                                <a href="#!">Read More</a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 wow fadeIn" data-wow-delay="0.5s">
                            <div class="service-item h-100">
                                <div class="btn-square bg-light mb-4">
                                    <i class="fa fa-home fa-2x text-secondary"></i>
                                </div>
                                <h3>Residence Facilities</h3>
                                <p class="mb-2">We’re creating programs that address urgent needs while fostering
                                    long-term solutions for sustainable change.</p>
                                <a href="#!">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->


    <!-- Features Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <div class="rounded overflow-hidden">
                        <div class="row g-0">
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.1s">
                                <div class="text-center bg-primary py-5 px-4 h-100">
                                    <i class="fa fa-users fa-3x text-secondary mb-3"></i>
                                    <h1 class="display-5 mb-0" data-toggle="counter-up">500</h1>
                                    <span class="text-dark">Team Members</span>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.3s">
                                <div class="text-center bg-secondary py-5 px-4 h-100">
                                    <i class="fa fa-award fa-3x text-primary mb-3"></i>
                                    <h1 class="display-5 text-white mb-0" data-toggle="counter-up">70</h1>
                                    <span class="text-white">Award Winning</span>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.5s">
                                <div class="text-center bg-secondary py-5 px-4 h-100">
                                    <i class="fa fa-list-check fa-3x text-primary mb-3"></i>
                                    <h1 class="display-5 text-white mb-0" data-toggle="counter-up">3000</h1>
                                    <span class="text-white">Total Projects</span>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.7s">
                                <div class="text-center bg-primary py-5 px-4 h-100">
                                    <i class="fa fa-comments fa-3x text-secondary mb-3"></i>
                                    <h1 class="display-5 mb-0" data-toggle="counter-up">7000</h1>
                                    <span class="text-dark">Client's Review</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <p class="section-title bg-white text-start text-primary pe-3">Why Us!</p>
                    <h1 class="display-6 mb-4 wow fadeIn" data-wow-delay="0.2s">Few Reasons Why People Choosing Us!</h1>
                    <p class="mb-4 wow fadeIn" data-wow-delay="0.3s">We believe in creating opportunities and empowering
                        communities through education, healthcare, and sustainable development. Your support helps us
                        bring smiles, hope, and a brighter future to those in need.</p>
                    <p class="text-dark wow fadeIn" data-wow-delay="0.4s"><i
                            class="fa fa-check text-primary me-2"></i>Justo magna erat amet</p>
                    <p class="text-dark wow fadeIn" data-wow-delay="0.5s"><i
                            class="fa fa-check text-primary me-2"></i>Aliqu diam amet diam et eos</p>
                    <p class="text-dark wow fadeIn" data-wow-delay="0.6s"><i
                            class="fa fa-check text-primary me-2"></i>Clita erat ipsum et lorem et sit</p>
                    <div class="d-flex mt-4 wow fadeIn" data-wow-delay="0.7s">
                        <a class="btn btn-primary py-3 px-4 me-3" href="#!">Donate Now</a>
                        <a class="btn btn-secondary py-3 px-4" href="#!">Join Us Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->


    <!-- Donation Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="section-title bg-white text-center text-primary px-3">Donation</p>
                <h1 class="display-6 mb-4">Our Donation Causes Around the World</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                    <div class="donation-item d-flex h-100 p-4">
                        <div class="donation-progress d-flex flex-column flex-shrink-0 text-center me-4">
                            <h6 class="mb-0">Raised</h6>
                            <span class="mb-2">$8000</span>
                            <div class="progress d-flex align-items-end w-100 h-100 mb-2">
                                <div class="progress-bar w-100 bg-secondary" role="progressbar" aria-valuenow="85"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <span class="fs-4">85%</span>
                                </div>
                            </div>
                            <h6 class="mb-0">Goal</h6>
                            <span>$10000</span>
                        </div>
                        <div class="donation-detail">
                            <div class="position-relative mb-4">
                                <img class="img-fluid w-100" src="img/donation-1.jpg" alt="">
                                <a href="#!"
                                    class="btn btn-sm btn-secondary px-3 position-absolute top-0 end-0">Food</a>
                            </div>
                            <a href="#!" class="h3 d-inline-block">Healthy Food</a>
                            <p>Through your donations and volunteer work, we spread kindness and support to children.
                            </p>
                            <a href="#!" class="btn btn-primary w-100 py-3"><i class="fa fa-plus me-2"></i>Donate
                                Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.13s">
                    <div class="donation-item d-flex h-100 p-4">
                        <div class="donation-progress d-flex flex-column flex-shrink-0 text-center me-4">
                            <h6 class="mb-0">Raised</h6>
                            <span class="mb-2">$8000</span>
                            <div class="progress d-flex align-items-end w-100 h-100 mb-2">
                                <div class="progress-bar w-100 bg-secondary" role="progressbar" aria-valuenow="95"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <span class="fs-4">95%</span>
                                </div>
                            </div>
                            <h6 class="mb-0">Goal</h6>
                            <span>$10000</span>
                        </div>
                        <div class="donation-detail">
                            <div class="position-relative mb-4">
                                <img class="img-fluid w-100" src="img/donation-2.jpg" alt="">
                                <a href="#!"
                                    class="btn btn-sm btn-secondary px-3 position-absolute top-0 end-0">Health</a>
                            </div>
                            <a href="#!" class="h3 d-inline-block">Water Treatment</a>
                            <p>Through your donations and volunteer work, we spread kindness and support to children.
                            </p>
                            <a href="#!" class="btn btn-primary w-100 py-3"><i class="fa fa-plus me-2"></i>Donate
                                Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                    <div class="donation-item d-flex h-100 p-4">
                        <div class="donation-progress d-flex flex-column flex-shrink-0 text-center me-4">
                            <h6 class="mb-0">Raised</h6>
                            <span class="mb-2">$8000</span>
                            <div class="progress d-flex align-items-end w-100 h-100 mb-2">
                                <div class="progress-bar w-100 bg-secondary" role="progressbar" aria-valuenow="75"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <span class="fs-4">75%</span>
                                </div>
                            </div>
                            <h6 class="mb-0">Goal</h6>
                            <span>$10000</span>
                        </div>
                        <div class="donation-detail">
                            <div class="position-relative mb-4">
                                <img class="img-fluid w-100" src="img/donation-3.jpg" alt="">
                                <a href="#!"
                                    class="btn btn-sm btn-secondary px-3 position-absolute top-0 end-0">Education</a>
                            </div>
                            <a href="#!" class="h3 d-inline-block">Education Support</a>
                            <p>Through your donations and volunteer work, we spread kindness and support to children.
                            </p>
                            <a href="#!" class="btn btn-primary w-100 py-3"><i class="fa fa-plus me-2"></i>Donate
                                Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Donation End -->


    <!-- Banner Start -->
    <div class="container-fluid banner py-5">
        <div class="container">
            <div class="banner-inner bg-light p-5 wow fadeIn" data-wow-delay="0.1s">
                <div class="row justify-content-center">
                    <div class="col-lg-8 py-5 text-center">
                        <h1 class="display-6 wow fadeIn" data-wow-delay="0.3s">Our Door Are Always Open to More People
                            Who Want to Support Each Others!</h1>
                        <p class="fs-5 mb-4 wow fadeIn" data-wow-delay="0.5s">Through your donations and volunteer work,
                            we spread kindness and support to children, families, and communities struggling to find
                            stability.</p>
                        <div class="d-flex justify-content-center wow fadeIn" data-wow-delay="0.7s">
                            <a class="btn btn-primary py-3 px-4 me-3" href="#!">Donate Now</a>
                            <a class="btn btn-secondary py-3 px-4" href="#!">Join Us Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->


    <!-- Event Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="section-title bg-white text-center text-primary px-3">Events</p>
                <h1 class="display-6 mb-4">Be a Part of a Global Movement</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                    <div class="event-item h-100 p-4">
                        <img class="img-fluid w-100 mb-4" src="img/event-1.jpg" alt="">
                        <a href="#!" class="h3 d-inline-block">Education Program</a>
                        <p>Through your donations and volunteer work, we spread kindness and support to children.</p>
                        <div class="bg-light p-4">
                            <p class="mb-1"><i class="fa fa-clock text-primary me-2"></i>10:00 AM - 18:00 PM</p>
                            <p class="mb-1"><i class="fa fa-calendar-alt text-primary me-2"></i>Jan 01 - Jan 10</p>
                            <p class="mb-0"><i class="fa fa-map-marker-alt text-primary me-2"></i>123 Street, New York,
                                USA</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                    <div class="event-item h-100 p-4">
                        <img class="img-fluid w-100 mb-4" src="img/event-2.jpg" alt="">
                        <a href="#!" class="h3 d-inline-block">Awareness Program</a>
                        <p>Through your donations and volunteer work, we spread kindness and support to children.</p>
                        <div class="bg-light p-4">
                            <p class="mb-1"><i class="fa fa-clock text-primary me-2"></i>10:00 AM - 18:00 PM</p>
                            <p class="mb-1"><i class="fa fa-calendar-alt text-primary me-2"></i>Jan 01 - Jan 10</p>
                            <p class="mb-0"><i class="fa fa-map-marker-alt text-primary me-2"></i>123 Street, New York,
                                USA</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                    <div class="event-item h-100 p-4">
                        <img class="img-fluid w-100 mb-4" src="img/event-3.jpg" alt="">
                        <a href="#!" class="h3 d-inline-block">Health Care Program</a>
                        <p>Through your donations and volunteer work, we spread kindness and support to children.</p>
                        <div class="bg-light p-4">
                            <p class="mb-1"><i class="fa fa-clock text-primary me-2"></i>10:00 AM - 18:00 PM</p>
                            <p class="mb-1"><i class="fa fa-calendar-alt text-primary me-2"></i>Jan 01 - Jan 10</p>
                            <p class="mb-0"><i class="fa fa-map-marker-alt text-primary me-2"></i>123 Street, New York,
                                USA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Event End -->


    <!-- Donate Start -->
    <div class="container-fluid donate py-5">
        <div class="container">
            <div class="row g-0">
                <div class="col-lg-7 donate-text bg-light py-5 wow fadeIn" data-wow-delay="0.1s">
                    <div class="d-flex flex-column justify-content-center h-100 p-5 wow fadeIn" data-wow-delay="0.3s">
                        <h1 class="display-6 mb-4">Let's Donate to Needy People for Better Lives</h1>
                        <p class="fs-5 mb-0">Through your donations, we spread kindness and support to children,
                            families, and communities struggling to find stability.</p>
                    </div>
                </div>
                <div class="col-lg-5 donate-form bg-primary py-5 text-center wow fadeIn" data-wow-delay="0.5s">
                    <div class="h-100 p-5">
                        <form>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" placeholder="Your Name">
                                        <label for="name">Your Name</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" placeholder="Your Email">
                                        <label for="email">Your Email</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1"
                                            autocomplete="off" checked>
                                        <label class="btn btn-light" for="btnradio1">$10</label>

                                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2"
                                            autocomplete="off">
                                        <label class="btn btn-light" for="btnradio2">$20</label>

                                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3"
                                            autocomplete="off">
                                        <label class="btn btn-light" for="btnradio3">$30</label>

                                        <input type="radio" class="btn-check" name="btnradio" id="btnradio4"
                                            autocomplete="off">
                                        <label class="btn btn-light" for="btnradio4">$40</label>

                                        <input type="radio" class="btn-check" name="btnradio" id="btnradio5"
                                            autocomplete="off">
                                        <label class="btn btn-light" for="btnradio5">$50</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-secondary py-3 w-100" type="submit">Donate Now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Donate End -->


    <!-- Team Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="section-title bg-white text-center text-primary px-3">Our Team</p>
                <h1 class="display-6 mb-4">Meet Our Dedicated Team Members</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                    <div class="team-item d-flex h-100 p-4">
                        <div class="team-detail pe-4">
                            <img class="img-fluid mb-4" src="img/team-1.jpg" alt="">
                            <h3>Boris Johnson</h3>
                            <span>Founder & CEO</span>
                        </div>
                        <div class="team-social bg-light d-flex flex-column justify-content-center flex-shrink-0 p-4">
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-x-twitter"></i></a>
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                    <div class="team-item d-flex h-100 p-4">
                        <div class="team-detail pe-4">
                            <img class="img-fluid mb-4" src="img/team-2.jpg" alt="">
                            <h3>Donald Pakura</h3>
                            <span>Project Manager</span>
                        </div>
                        <div class="team-social bg-light d-flex flex-column justify-content-center flex-shrink-0 p-4">
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-x-twitter"></i></a>
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                    <div class="team-item d-flex h-100 p-4">
                        <div class="team-detail pe-4">
                            <img class="img-fluid mb-4" src="img/team-3.jpg" alt="">
                            <h3>Alexander Bell</h3>
                            <span>Volunteer</span>
                        </div>
                        <div class="team-social bg-light d-flex flex-column justify-content-center flex-shrink-0 p-4">
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-x-twitter"></i></a>
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team End -->


    <!-- Testimonial Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-12 col-lg-4 col-xl-3 wow fadeIn" data-wow-delay="0.1s">
                    <div class="testimonial-title">
                        <h1 class="display-6 mb-4">What People Say About Our Activities.</h1>
                        <p class="fs-5 mb-0">We work to bring smiles, hope, and a brighter future to those in need.</p>
                    </div>
                </div>
                <div class="col-md-12 col-lg-8 col-xl-9">
                    <div class="owl-carousel testimonial-carousel wow fadeIn" data-wow-delay="0.3s">
                        <div class="testimonial-item">
                            <div class="row g-5 align-items-center">
                                <div class="col-md-6">
                                    <div class="testimonial-img">
                                        <img class="img-fluid" src="img/testimonial-1.jpg" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="testimonial-text pb-5 pb-md-0">
                                        <div class="mb-2">
                                            <i class="fa fa-star text-primary"></i>
                                            <i class="fa fa-star text-primary"></i>
                                            <i class="fa fa-star text-primary"></i>
                                            <i class="fa fa-star text-primary"></i>
                                            <i class="fa fa-star text-primary"></i>
                                        </div>
                                        <p class="fs-5">Education is the foundation of change. By funding schools,
                                            scholarships, and training programs, we can help children and adults unlock
                                            their potential for a better future.</p>
                                        <div class="d-flex align-items-center">
                                            <div class="btn-lg-square bg-light text-secondary flex-shrink-0">
                                                <i class="fa fa-quote-right fa-2x"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h5 class="mb-0">Alexander Bell</h5>
                                                <span>CEO, Founder</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-item">
                            <div class="row g-5 align-items-center">
                                <div class="col-md-6">
                                    <div class="testimonial-img">
                                        <img class="img-fluid" src="img/testimonial-2.jpg" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="testimonial-text pb-5 pb-md-0">
                                        <div class="mb-2">
                                            <i class="fa fa-star text-primary"></i>
                                            <i class="fa fa-star text-primary"></i>
                                            <i class="fa fa-star text-primary"></i>
                                            <i class="fa fa-star text-primary"></i>
                                            <i class="fa fa-star text-primary"></i>
                                        </div>
                                        <p class="fs-5">Every hand extended in kindness brings us closer to a world free
                                            from suffering. Be part of a global movement dedicated to building a future
                                            where equality and compassion thrive.</p>
                                        <div class="d-flex align-items-center">
                                            <div class="btn-lg-square bg-light text-secondary flex-shrink-0">
                                                <i class="fa fa-quote-right fa-2x"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h5 class="mb-0">Donald Pakura</h5>
                                                <span>CEO, Founder</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-item">
                            <div class="row g-5 align-items-center">
                                <div class="col-md-6">
                                    <div class="testimonial-img">
                                        <img class="img-fluid" src="img/testimonial-3.jpg" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="testimonial-text pb-5 pb-md-0">
                                        <div class="mb-2">
                                            <i class="fa fa-star text-primary"></i>
                                            <i class="fa fa-star text-primary"></i>
                                            <i class="fa fa-star text-primary"></i>
                                            <i class="fa fa-star text-primary"></i>
                                            <i class="fa fa-star text-primary"></i>
                                        </div>
                                        <p class="fs-5">Love and compassion have the power to heal. Through your
                                            donations and volunteer work, we can spread kindness and support to
                                            children, families, and communities struggling to find stability.</p>
                                        <div class="d-flex align-items-center">
                                            <div class="btn-lg-square bg-light text-secondary flex-shrink-0">
                                                <i class="fa fa-quote-right fa-2x"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h5 class="mb-0">Boris Johnson</h5>
                                                <span>CEO, Founder</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->


    <!-- Newsletter Start -->
    <div class="container-fluid bg-primary py-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="display-6 mb-4">Subscribe the Newsletter</h1>
                    <div class="position-relative w-100 mb-2">
                        <input class="form-control border-0 w-100 ps-4 pe-5" type="text" placeholder="Enter Your Email"
                            style="height: 60px;">
                        <button type="button"
                            class="btn btn-lg-square shadow-none position-absolute top-0 end-0 mt-2 me-2"><i
                                class="fa fa-paper-plane text-primary fs-4"></i></button>
                    </div>
                    <p class="mb-0">Don't worry, we won't spam you with emails.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Newsletter End -->


    <!-- Footer Start -->
    <div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-5 py-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Our Office</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                    <div class="d-flex pt-3">
                        <a class="btn btn-square btn-primary me-2" href="#!"><i class="fab fa-x-twitter"></i></a>
                        <a class="btn btn-square btn-primary me-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-primary me-2" href="#!"><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-square btn-primary me-2" href="#!"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Quick Links</h4>
                    <a class="btn btn-link" href="#!">About Us</a>
                    <a class="btn btn-link" href="#!">Contact Us</a>
                    <a class="btn btn-link" href="#!">Our Services</a>
                    <a class="btn btn-link" href="#!">Terms & Condition</a>
                    <a class="btn btn-link" href="#!">Support</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Business Hours</h4>
                    <p class="mb-1">Monday - Friday</p>
                    <h6 class="text-light">09:00 am - 07:00 pm</h6>
                    <p class="mb-1">Saturday</p>
                    <h6 class="text-light">09:00 am - 12:00 pm</h6>
                    <p class="mb-1">Sunday</p>
                    <h6 class="text-light">Closed</h6>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Gallery</h4>
                    <div class="row g-2">
                        <div class="col-4">
                            <img class="img-fluid w-100" src="img/gallery-1.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid w-100" src="img/gallery-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid w-100" src="img/gallery-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid w-100" src="img/gallery-4.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid w-100" src="img/gallery-5.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid w-100" src="img/gallery-6.jpg" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright pt-5">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="fw-semi-bold" href="#!">Your Site Name</a>, All Right Reserved.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                        <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                        <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                        Designed By <a class="fw-semi-bold" href="https://htmlcodex.com">HTML Codex</a>. Distributed by
                        <a class="fw-semi-bold" href="https://themewagon.com">ThemeWagon</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#!" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>