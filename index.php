<?php
include 'koneksi.php';
$query_visi = "SELECT isi FROM visimisi WHERE nama = 'visi' LIMIT 1";
$query_misi = "SELECT isi FROM visimisi WHERE nama = 'misi' LIMIT 1";
$query_dosen = "SELECT * FROM dosen";
$query_peralatan_lab = "SELECT gambar, nama FROM fasilitas 
                        WHERE nama NOT IN ('Area Mushola', 'AC', 'Whiteboard', 'Locker')";
$query_fasilitas_umum = "SELECT gambar, nama FROM fasilitas WHERE nama = 'Area Mushola' OR nama = 'AC' OR nama = 'Whiteboard' OR nama = 'Locker'";
$query_berita_terbaru = "SELECT * FROM berita ORDER BY berita_id DESC LIMIT 3";



$result_visi = pg_query($koneksi, $query_visi);
$result_misi = pg_query($koneksi, $query_misi);
$result_dosen = pg_query($koneksi, $query_dosen);
$result_peralatan_lab = pg_query($koneksi, $query_peralatan_lab);
$result_fasilitas_umum = pg_query($koneksi, $query_fasilitas_umum);
$result_berita_terbaru = pg_query($koneksi, $query_berita_terbaru);

$data_visi = pg_fetch_assoc($result_visi);
$data_misi = pg_fetch_assoc($result_misi);
$data_dosen = pg_fetch_assoc($result_dosen);
$dosen_id_url = $data_dosen['nidn'];

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
    <?php include('navbar.php');?>

    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>
    <!-- Spinner End -->

    <!-- Carousel Start -->
    <div class="container-fluid p-0 wow fadeIn" data-wow-delay="0.1s">
        <div class="owl-carousel header-carousel py-5">
            <div class="container py-5">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="carousel-text">
                            <h3 class="display-1 text-uppercase mb-3">Selamat Datang di Lab IVSS</h3>
                            <p class="fs-5 mb-5">Laboratorium Visi Cerdas dan Sistem Cerdas</p>
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
        </div>
    </div>
    <!-- Carousel End -->

    <!-- Fokus Riset Start -->
    <section id="fokus-riset" class="py-5" style="background-color: #ffac00;">
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
                        Laboratorium Visi Cerdas dan Sistem Cerdas
                    </h1>

                    <!-- Description -->
                    <p class="mb-4 wow fadeIn" data-wow-delay="0.3s" style="text-align: justify;">
                        Laboratorium Visi Cerdas dan Sistem Cerdas merupakan pusat riset dan pengembangan di 
                        bawah Jurusan Teknologi Informasi Politeknik Negeri Malang yang berfokus pada bidang 
                        intelligent vision dan smart system. Laboratorium ini menjadi wadah bagi dosen
                        dan mahasiswa untuk melakukan penelitian, pembelajaran, serta pelatihan dalam pengembangan 
                        sistem cerdas berbasis pengolahan citra dan kecerdasan buatan. Penelitian di laboratorium ini 
                        mengintegrasikan computer vision, AI, dan IoT untuk menciptakan solusi inovatif yang mampu 
                        mengenali, menganalisis, serta merespon lingkungan secara mandiri.
                    </p>
                </div> <!-- End Content Column -->

            </div> <!-- End Row -->
        </div> <!-- End Container -->
    </div>
    <!-- About End -->

    <!-- Visi Misi Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 700px;">
                <p class="section-title bg-white text-center text-primary px-3">LAB IVSS</p>
                <h1 class="display-6 mb-3">VISI & MISI</h1>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100 shadow-lg border-start border-5" style="border-color: #ffac00 !important;"> 
                        <div class="card-body p-4">

                            <h3 class="card-title mb-3 d-flex align-items-center" style="color: #1a685b;">
                                <span class="icon-circle me-3" style="background-color: rgba(26, 104, 91, 0.1); color: #1a685b; padding: 8px 10px; border-radius: 8px;">
                                    <i class="fas fa-eye fa-lg"></i> 
                                </span>
                                Visi
                            </h3>
                            
                            <hr style="border-top: 2px solid #ffac00; opacity: 0.5;">

                            <p class="card-text text-dark">
                                <?php echo nl2br($isi_visi); ?>
                            </p>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100 shadow-lg border-start border-5" style="border-color: #ffac00 !important;">
                        <div class="card-body p-4">

                            <h3 class="card-title mb-3 d-flex align-items-center" style="color: #1a685b;">
                                <span class="icon-circle me-3" style="background-color: rgba(26, 104, 91, 0.1); color: #1a685b; padding: 8px 10px; border-radius: 8px;">
                                    <i class="fas fa-check-circle fa-lg"></i> 
                                </span>
                                Misi
                            </h3>
                            
                            <hr style="border-top: 2px solid #ffac00; opacity: 0.5;">

                            <ul class="list-unstyled">
                                <?php
                                    $misi_points = explode("\n", trim($isi_misi));

                                    foreach ($misi_points as $point) {
                                        if (!empty(trim($point))) {
                                            echo '<li class="mb-3 d-flex align-items-start">';
                                            // Ikon checklist warna Hijau Tua/Teal
                                            echo ' <i class="fas fa-check-square me-3 mt-1" style="color: #1a685b; font-size: 1.2em;"></i>'; 
                                            echo ' <span class="text-dark">' . trim($point) . '</span>';
                                            echo '</li>';
                                        }
                                    }
                                ?>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Visi Misi Start -->
    
    <!-- SOP & Layanan Start -->
    <div class="container-fluid banner py-5" style="background-color: #f8f9fa;"> 
    <div class="container py-4">
            <div class="banner-inner bg-white rounded-4 shadow-lg p-5 wow fadeIn" data-wow-delay="0.1s">
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-xl-8">
                        <div class="text-center">
                            
                            <h2 class="display-5 fw-bold mb-4" style="color: #1a685b;">
                                SOP dan Layanan
                            </h2>
                            
                            <p class="lead text-muted mb-5">
                                Informasi standar operasional prosedur dan detail layanan Laboratorium.
                            </p>
                            
                            <div class="row text-start g-4">
                                
                                <div class="col-md-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-map-marker-alt fa-2x me-3" style="color: #ffac00;"></i>
                                        <div>
                                            <h5 class="fw-bold" style="color: #1a685b;">Lokasi</h5>
                                            <p class="mb-0 text-dark">Gedung Jurusan Teknologi Informasi — Lantai 8 Barat</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-envelope fa-2x me-3" style="color: #ffac00;"></i>
                                        <div>
                                            <h5 class="fw-bold" style="color: #1a685b;">Surel</h5>
                                            <p class="mb-0 text-dark">—</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-clock fa-2x me-3" style="color: #ffac00;"></i>
                                        <div>
                                            <h5 class="fw-bold" style="color: #1a685b;">Jam Layanan</h5>
                                                <div class="d-flex justify-content-between" style="width: 200px;">
                                                    <span>Mon-Fri</span>
                                                    <span>07.00 — 21.00</span>
                                                </div>
                                                <div class="d-flex justify-content-between" style="width: 200px;">
                                                    <span>Sat</span>
                                                    <span>09.00 — 21.00</span>
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
    <!-- SOP & Layanan End -->

    <!-- Fasilitas Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="section-title bg-white text-center text-primary px-3">LAB IVSS</p>
            <h1 class="display-6 mb-4">FASILITAS LABORATORIUM</h1>
        </div>

        <div class="row g-4 justify-content-center">
            <?php
            while ($data_fasilitas = pg_fetch_assoc($result_fasilitas_umum)) {
            ?>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                    <div class="team-item d-flex h-100 p-4">

                        <div class="team-detail pe-4">
                            <!-- Foto fasilitas dari database -->
                            <img class="img-fluid mb-4" 
                                 src="admin/assets/img/<?php echo $data_fasilitas['gambar']; ?>" 
                                 alt="<?php echo $data_fasilitas['nama']; ?>">
                            <!-- Nama fasilitas -->
                            <h3><?php echo $data_fasilitas['nama']; ?></h3>
                        </div>

                        <div class="team-social bg-light d-flex flex-column justify-content-center flex-shrink-0 p-4">
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-x-twitter"></i></a>
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-square btn-primary my-2" href="#!"><i class="fab fa-youtube"></i></a>
                        </div>

                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- Fasilitas End -->


    <!-- Peralatan Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="section-title bg-white text-center text-primary px-3">LAB IVSS</p>
                <h1 class="display-6 mb-4">PERALATAN LABORATORIUM</h1>
            </div>

        <?php  
        // === Ambil semua data alat ke array ===
        $items = [];
        while ($row = pg_fetch_assoc($result_peralatan_lab)) {
            $items[] = $row;
        }

        // Konfigurasi
        $totalItems = count($items);
        $perSlide = 3; // 1 slide = 3 item
        $totalSlides = ceil($totalItems / $perSlide);
        ?>

        <div id="donationCarousel" class="carousel slide" data-bs-ride="carousel">
    
                <!-- Indicators otomatis -->
                <div class="carousel-indicators">
                    <?php for ($i = 0; $i < $totalSlides; $i++) : ?>
                        <button type="button"
                                data-bs-target="#donationCarousel"
                                data-bs-slide-to="<?php echo $i; ?>"
                                class="<?php echo $i === 0 ? 'active' : ''; ?>">
                        </button>
                    <?php endfor; ?>
                </div>

                <!-- Slides Dinamis -->
                <div class="carousel-inner">

                    <?php for ($i = 0; $i < $totalSlides; $i++) : ?>
                        <div class="carousel-item <?php echo $i === 0 ? 'active' : ''; ?>">
                            <div class="row justify-content-center g-4">

                                <?php
                                $start = $i * $perSlide;
                                $end = min($start + $perSlide, $totalItems);

                                for ($j = $start; $j < $end; $j++) :
                                    $item = $items[$j];
                                ?>
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <!-- Gambar -->
                                            <img src="admin/assets/img/<?php echo $item['gambar']; ?>"
                                                alt="<?php echo $item['nama']; ?>"
                                                class="card-img-top img-fluid"
                                                style="height: 200px; object-fit: cover;">

                                            <div class="card-body text-center">
                                                <span class="badge rounded-pill text-dark px-3 py-2 mb-2" style="background-color: #FFBC3B;">
                                                    <?php echo $item['kategori'] ?? 'Alat'; ?>
                                                </span>
                                                <h5 class="card-title"><?php echo $item['nama']; ?></h5>
                                                <p class="card-text"><?php echo $item['deskripsi'] ?? ''; ?></p>
                                                <a href="#!" class="btn btn-primary"><i class="fa fa-plus me-2"></i>Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endfor; ?>

                            </div>
                        </div>
                    <?php endfor; ?>

                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#donationCarousel" data-bs-slide="prev">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="black" class="bi bi-chevron-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#donationCarousel" data-bs-slide="next">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="black" class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Peralatan End -->

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

    <!-- Berita Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="section-title bg-white text-center text-primary px-3">BERITA</p>
                <h1 class="display-6 mb-4">BERITA TERBARU</h1>
            </div>
            
            <div class="row g-4">
                <?php
                $batas_karakter = 150;

                if ($result_berita_terbaru) {
                    if (isset($result_berita_terbaru)) { pg_result_seek($result_berita_terbaru, 0); }
                    
                    while ($data_berita = pg_fetch_assoc($result_berita_terbaru)) {
                        $berita_id = $data_berita['berita_id']; 
                        $berita_link = "detailBerita.php?id=" . urlencode($berita_id);
                        
                        $tanggal_format = date('d F Y', strtotime($data_berita['tanggal']));
                        
                        $isi_lengkap = $data_berita['isi']; 
                        
                        if (strlen($isi_lengkap) > $batas_karakter) {
                            $potongan_isi = substr($isi_lengkap, 0, $batas_karakter);
                            $potongan_isi = substr($potongan_isi, 0, strrpos($potongan_isi, ' '));
                            $isi_tampilan = $potongan_isi . '...';
                            $show_button = true;
                        } else {
                            $isi_tampilan = $isi_lengkap;
                            $show_button = false;
                        }
                ?>
                
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                    <div class="event-item h-100 p-4">
                        <img class="img-fluid w-100 mb-4" 
                            src="admin/assets/img/<?php echo htmlspecialchars($data_berita['gambar']); ?>" 
                            alt="<?= htmlspecialchars($data_berita['judul']); ?>" 
                            style="height: 200px; object-fit: cover;"> <a href="<?php echo $berita_link; ?>" class="h3 d-inline-block text-primary">
                            <?php echo htmlspecialchars($data_berita['judul']); ?>
                        </a>
                        
                        <p style="color: #495057;">
                            <?php echo htmlspecialchars($isi_tampilan); ?>
                        </p>
                        
                        <div class="bg-light p-4 mt-auto"> <p class="mb-1"><i class="fa fa-calendar-alt text-primary me-2"></i><?php echo $tanggal_format; ?></p>
                            <p class="mb-0"><i class="fa fa-user-edit text-primary me-2"></i>Penulis: <?php echo htmlspecialchars($data_berita['penulis'] ?? '-'); ?></p> 
                            
                            <?php if ($show_button): ?>
                            <a href="<?php echo $berita_link; ?>" class="more-btn fw-bold mt-2 d-block"
                                style="color: #FFBC3B; text-decoration: none;">
                                Baca Selengkapnya &rarr;
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <?php
                    } 
                } else {
                    echo "<div class='col-12'><p class='text-center'>Tidak ada berita terbaru yang tersedia.</p></div>";
                }
                ?>
            </div>
            </div>
    </div>
    <!-- Berita End -->

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