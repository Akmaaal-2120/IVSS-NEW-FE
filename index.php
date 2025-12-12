<?php
include 'inc/koneksi.php';
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
    <title>Laboratorium Visi Cerdas dan Sistem Cerdas</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


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
    <?php include('inc/navbar.php');?>
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
                            <i class="fa-solid fa-eye"></i>
                            <path fill="#343C54" />
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

    
    <!-- Service Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-12 col-lg-4 col-xl-3 wow fadeIn" data-wow-delay="0.1s">
                    <div class="service-title">
                        <h1 class="display-6 mb-4">Kegiatan & Proyek</h1>

                    </div>
                </div>
                <div class="col-md-12 col-lg-8 col-xl-9">
                    <div class="row g-5">
                        <div class="col-sm-6 col-md-4 wow fadeIn" data-wow-delay="0.1s">
                            <div class="service-item h-100"
                                style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                                <div class="btn-square bg-light mb-4">
                                    <i class="fa fa-solid fa-cloud-meatball fa-2x text-secondary"></i>
                                </div>
                                <h3>Sistem Cerdas</h3>
                                <p class="mb-2">Integrasi AI dengan sistem nyata untuk membantu pengambilan keputusan.
                                </p>
                                <div style="height: 3px; width: 100%; background-color: #FFAC00; margin: 10px 0 0;">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 wow fadeIn" data-wow-delay="0.3s">
                            <div class="service-item h-100"
                                style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                                <div class="btn-square bg-light mb-4">
                                    <i class="fa fa-solid fa-layer-group fa-2x text-secondary"></i>
                                </div>
                                <h3>Machine Learning</h3>
                                <p class="mb-2">Pembelajaran mesin untuk klasifikasi, regresi, dan clustering
                                    menggunakan dataset nyata.</p>
                                <div style="height: 3px; width: 100%; background-color: #FFAC00; margin: 10px 0 0;">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 wow fadeIn" data-wow-delay="0.5s">
                            <div class="service-item h-100"
                                style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                                <div class="btn-square bg-light mb-4">
                                    <i class="fa fa-regular fa-lightbulb fa-2x text-secondary"></i>
                                </div>
                                <h3>Visi Komputer</h3>
                                <p class="mb-2">Penerapan teknik AI pada pengolahan citra/video untuk mendeteksi dan
                                    mengenali objek.</p>
                                <div style="height: 3px; width: 100%; background-color: #FFAC00; margin: 10px 0 0;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->

    <!-- About Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5 align-items-center">

                <!-- Logo / Image -->
                <div class="col-lg-6" data-wow-delay="0.2s">
                    <img class="img-fluid" src="../../IVSS-LAB/admin/assets/img/ivss_logo_no-desc.png" alt="Image"
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
                <h1 class="display-6 mb-3">Visi & Misi</h1>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100 shadow-lg border-start border-5" style="border-color: #ffac00 !important;">
                        <div class="card-body p-4">

                            <h3 class="card-title mb-3 d-flex align-items-center" style="color: #1a685b;">
                                <span class="icon-circle me-3"
                                    style="background-color: rgba(26, 104, 91, 0.1); color: #1a685b; padding: 8px 10px; border-radius: 8px;">
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
                                <span class="icon-circle me-3"
                                    style="background-color: rgba(26, 104, 91, 0.1); color: #1a685b; padding: 8px 10px; border-radius: 8px;">
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
                                            <p class="mb-0 text-dark">Gedung Jurusan Teknologi Informasi — Lantai 8
                                                Barat</p>
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
                                                <span>Sat-Sun</span>
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
                <h1 class="display-6 mb-4">Fasilitas Laboratorium</h1>
            </div>

            <div class="row g-4 justify-content-center">
                <?php while ($data_fasilitas = pg_fetch_assoc($result_fasilitas_umum)) { ?>
                    <div class="col-lg-4 col-md-6 col-12">
                        
                        <div class="d-flex flex-column h-100 p-4"
                            style="border: 1px solid #eee; border-radius: 12px; 
                                box-shadow: 0 4px 12px rgba(0,0,0,0.08);">

                            <div style="flex-grow: 1; display: flex; flex-direction: column;">

                                <!-- Gambar made same as peralatan -->
                                <div class="position-relative mb-4"
                                    style="height: 300px; overflow: hidden; border-radius: 10px;">
                                    
                                    <img src="admin/assets/img/<?php echo $data_fasilitas['gambar']; ?>"
                                        alt="<?php echo $data_fasilitas['nama']; ?>"
                                        class="w-100 h-100"
                                        style="object-fit: cover;">
                                </div>

                                <!-- Judul -->
                                <h4 class="mb-2" style="color: #333;">
                                    <?php echo $data_fasilitas['nama']; ?>
                                </h4>

                                <!-- Deskripsi kalau ada -->
                                <p style="color: #555; font-size: 0.95rem; flex-grow: 1;">
                                    <?php echo $data_fasilitas['deskripsi'] ?? 'Informasi fasilitas belum tersedia.'; ?>
                                </p>

                                <!-- Tombol sejajar bawah -->
                                <a href="#!"
                                    class="btn btn-primary w-100 py-3 mt-auto"
                                    style="border-radius: 8px;">
                                    Fasilitas
                                </a>

                            </div>

                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>
    <!-- Fasilitas End -->

    <!-- Peralatan Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="section-title bg-white text-center text-primary px-3">LAB IVSS</p>
                <h1 class="display-6 mb-4">Peralatan Laboratorium</h1>
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
                    <button type="button" data-bs-target="#donationCarousel" data-bs-slide-to="<?php echo $i; ?>"
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
                            <div class="d-flex flex-column h-100 p-4" 
                                style="border: 1px solid #eee; border-radius: 12px; 
                                    box-shadow: 0 4px 12px rgba(0,0,0,0.08);">

                                <div style="flex-grow: 1; display: flex; flex-direction: column;">

                                    <!-- Gambar FIXED SIZE -->
                                    <div class="position-relative mb-4" 
                                        style="height: 300px; overflow: hidden; border-radius: 10px;">

                                        <img src="admin/assets/img/<?php echo $item['gambar']; ?>"
                                            alt="<?php echo $item['nama']; ?>"
                                            class="w-100 h-100"
                                            style="object-fit: cover;">
                                    </div>

                                    <!-- Judul -->
                                    <a href="#!" class="h4 d-inline-block mb-2"
                                        style="text-decoration: none; color: #333;">
                                        <?php echo $item['nama']; ?>
                                    </a>

                                    <!-- Deskripsi -->
                                    <p style="color: #555; font-size: 0.95rem; flex-grow: 1;">
                                        <?php echo $item['deskripsi'] ?? 'Klik detail untuk informasi lebih lanjut.'; ?>
                                    </p>

                                    <!-- Tombol SELALU DI BAWAH -->
                                    <a href="#!" 
                                        class="btn btn-primary w-100 py-3 mt-auto"
                                        style="border-radius: 8px;">
                                        Alat
                                    </a>

                                </div>

                            </div>
                        </div>
                        <?php endfor; ?>

                    </div>
                </div>

                    <?php endfor; ?>

                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#donationCarousel"
                    data-bs-slide="prev">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="black"
                        class="bi bi-chevron-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                    </svg>
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#donationCarousel"
                    data-bs-slide="next">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="black"
                        class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Peralatan End -->

    <!-- Features Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <div class="rounded overflow-hidden">
                        <div class="row g-0">
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.1s">
                                <div class="text-center bg-primary py-5 px-4 h-100">
                                    <i class="fa-solid fa-brain fa-3x text-secondary mb-3"></i>
                                    <h3 class="display-5 mb-0" style="font-size: 24px;">Kecerdasan Artifisial</h3>
                                    <span class="text-dark">Teknologi yang fokus pada pengembangan sistem atau mesin
                                        yang dapat
                                        melakukan tugas-tugas yang biasanya memerlukan kecerdasan manusia, seperti
                                        pengenalan pola,
                                        pembelajaran, pemecahan masalah, dan pengambilan keputusan.</span>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.3s">
                                <div class="text-center bg-secondary py-5 px-4 h-100">
                                    <i class="fa fa-eye fa-3x text-primary mb-3"></i>
                                    <h3 class="display-5 text-white mb-0" style="font-size: 24px;">Pengolahan Citra &
                                        Visi Komputer</h3>
                                    <span class="text-white">Cabang dari kecerdasan artifisial yang fokus pada
                                        pengembangan algoritma yang
                                        memungkinkan mesin belajar dari data untuk membuat prediksi atau keputusan tanpa
                                        diprogram secara eksplisit.</span>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.5s">
                                <div class="text-center bg-secondary py-5 px-4 h-100">
                                    <i class="fa fa-network-wired fa-3x text-primary mb-3"></i>
                                    <h3 class="display-5 text-white mb-0" style="font-size: 24px;">Machine Learning</h3>
                                    <span class="text-white">Teknik untuk mengolah dan menganalisis gambar atau video
                                        menggunakan komputer, termasuk deteksi objek,
                                        segmentasi, pengenalan pola, dan interpretasi citra untuk aplikasi seperti
                                        pengenalan wajah dan kendaraan otomatis.</span>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.7s">
                                <div class="text-center bg-primary py-5 px-4 h-100">
                                    <i class="fa fa-lightbulb fa-3x text-secondary mb-3"></i>
                                    <h1 class="display-5 mb-0" style="font-size: 24px;">Sistem Cerdas</h1>
                                    <span class="text-dark">Pengembangan sistem yang dapat meniru atau melampaui
                                        kemampuan kognitif manusia, seperti pengambilan
                                        keputusan otomatis, perencanaan, dan pemrosesan informasi dalam konteks aplikasi
                                        nyata, seperti robotika dan sistem pakar.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <p class="section-title bg-white text-start text-primary pe-3">LAB IVSS</p>
                    <h1 class="display-6 mb-4 wow fadeIn" data-wow-delay="0.2s">Perkuliahan Terkait</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->
    <div class="container py-5">

        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold display-5" style="color: #1A685B;">
                    <i class="fas fa-book-reader me-2"></i> Publikasi Dosen
                </h2>
                <div class="mx-auto" style="width: 150px; height: 5px; background-color: #FFAC00; margin-top: 10px;">
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle rounded-4 overflow-hidden"
                        style="box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15); border: 1px solid #dee2e6;">

                        <thead style="background-color: #1A685B;">
                            <tr class="text-white text-uppercase small">
                                <th scope="col" class="py-3 px-4 text-start">Dosen / Peneliti</th>
                                <th scope="col" class="py-3 text-center">Scopus</th>
                                <th scope="col" class="py-3 text-center">Google Scholar</th>
                                <th scope="col" class="py-3 text-center">Sinta</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                        // Memastikan $result_dosen ada dan memiliki data (asumsi ini adalah logic PHP)
                        if (isset($result_dosen) && pg_num_rows($result_dosen) > 0) {
                            $no = 1;
                            // Asumsi pg_result_seek diperlukan di sini jika data sudah pernah dibaca
                            if (isset($result_dosen)) { pg_result_seek($result_dosen, 0); } 
                            
                            while ($data_dosen = pg_fetch_assoc($result_dosen)) {
                                $link_scopus = $data_dosen['scopus'] ?? '#';
                                $link_scholar = $data_dosen['google_scholar'] ?? '#';
                                $link_sinta = $data_dosen['sinta'] ?? '#';
                                
                                // Kelas untuk tombol Link (Bootstrap classes)
                                $scopus_class = ($link_scopus === '#') ? 'btn-outline-secondary disabled' : 'btn-warning text-dark fw-bold';
                                $scholar_class = ($link_scholar === '#') ? 'btn-outline-secondary disabled' : 'btn-warning text-dark fw-bold';
                                $sinta_class = ($link_sinta === '#') ? 'btn-outline-secondary disabled' : 'btn-warning text-dark fw-bold';
                        ?>
                            <tr class="bg-white">
                                <td class="text-start px-4">
                                    <span class="fw-bold me-2 text-primary"><?php echo $no++; ?>.</span>
                                    <?php echo htmlspecialchars($data_dosen['nama'] ?? 'Nama Dosen Tidak Ada'); ?>
                                </td>

                                <td class="text-center">
                                    <a href="<?php echo $link_scopus; ?>" target="_blank"
                                        class="btn btn-sm <?php echo $scopus_class; ?>"
                                        style="<?php echo ($link_scopus !== '#') ? 'background-color: #FFAC00; border-color: #FFAC00;' : ''; ?>">
                                        <i class="fas fa-external-link-alt me-1"></i> Detail
                                    </a>
                                </td>

                                <td class="text-center">
                                    <a href="<?php echo $link_scholar; ?>" target="_blank"
                                        class="btn btn-sm <?php echo $scholar_class; ?>"
                                        style="<?php echo ($link_scholar !== '#') ? 'background-color: #FFAC00; border-color: #FFAC00;' : ''; ?>">
                                        <i class="fas fa-external-link-alt me-1"></i> Detail
                                    </a>
                                </td>

                                <td class="text-center">
                                    <a href="<?php echo $link_sinta; ?>" target="_blank"
                                        class="btn btn-sm <?php echo $sinta_class; ?>"
                                        style="<?php echo ($link_sinta !== '#') ? 'background-color: #FFAC00; border-color: #FFAC00;' : ''; ?>">
                                        <i class="fas fa-external-link-alt me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            <?php
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center text-muted py-4 small">Data dosen tidak ditemukan di database atau belum ada.</td></tr>';
                        }
                        ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Berita Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="section-title bg-white text-center text-primary px-3">BERITA</p>
                <h1 class="display-6 mb-4">Berita Terbaru</h1>
            </div>

            <div class="row g-4">

                <?php
            $batas_karakter = 150;

            if ($result_berita_terbaru && pg_num_rows($result_berita_terbaru) > 0) {

                pg_result_seek($result_berita_terbaru, 0);

                while ($data_berita = pg_fetch_assoc($result_berita_terbaru)) {

                    $berita_id  = htmlspecialchars($data_berita['berita_id']);
                    $berita_link = "detailBerita.php?id=" . urlencode($berita_id);
                    $tanggal_format = date('d F Y', strtotime($data_berita['tanggal'] ?? date("Y-m-d")));

                    // preview
                    $isi_lengkap = strip_tags($data_berita['isi']);
                    if (strlen($isi_lengkap) > $batas_karakter) {
                        $potongan_isi = substr($isi_lengkap, 0, $batas_karakter);
                        $potongan_isi = substr($potongan_isi, 0, strrpos($potongan_isi, ' '));
                        $isi_tampilan = $potongan_isi . "...";
                        $show_button = true;
                    } else {
                        $isi_tampilan = $isi_lengkap;
                        $show_button = false;
                    }
            ?>

                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                    <div class="event-item h-100 p-4 d-flex flex-column shadow-sm border rounded">

                        <img class="img-fluid w-100 mb-3 rounded"
                            src="admin/assets/img/<?php echo htmlspecialchars($data_berita['gambar']); ?>"
                            alt="<?php echo htmlspecialchars($data_berita['judul']); ?>"
                            style="height: 200px; object-fit: cover;">

                        <a href="<?php echo $berita_link; ?>" class="h4 text-primary mb-2 fw-bold d-block">
                            <?php echo htmlspecialchars($data_berita['judul']); ?>
                        </a>

                        <p class="flex-grow-1" style="color:#495057;">
                            <?php echo htmlspecialchars($isi_tampilan); ?>
                        </p>

                        <div class="bg-light p-3 rounded mt-3">
                            <p class="mb-1">
                                <i class="fa fa-calendar-alt text-primary me-2"></i>
                                <?php echo $tanggal_format; ?>
                            </p>

                            <p class="mb-2">
                                <i class="fa fa-user-edit text-primary me-2"></i>
                                Penulis: <?php echo htmlspecialchars($data_berita['penulis'] ?? "-"); ?>
                            </p>

                            <?php if ($show_button): ?>
                            <a href="<?php echo $berita_link; ?>" class="fw-bold d-block text-end"
                                style="color:#FFBC3B; text-decoration:none;">
                                Baca Selengkapnya →
                            </a>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

                <?php
                } // end while

            } else {
                echo "<div class='col-12 text-center'>Tidak ada berita terbaru yang tersedia.</div>";
            }
            ?>

            </div>
        </div>
    </div>

    <!-- Berita End -->

    <!-- Footer Start -->
    <?php include('inc/footer.php')?>
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