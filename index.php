<?php
include './backEnd/prosesIndex.php';
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
                    <div class="col-lg-6"> <div class="carousel-text text-start text-white">
                        <p class="mb-3 text-uppercase fw-semibold" style="letter-spacing: 2px; color:#1d4052">
                            <i class="fas fa-microchip me-2" style="color: #1d4052;"></i> Lab IVSS
                        </p>

                            <h1 class="display-3 text-uppercase fw-bolder mb-4 animate__animated animate__fadeInDown" style="color: #1d4052;">
                                Intelligent Vision & <span style="color: #FFBC3B;">Smart System</span>
                            </h1>

                            <p class="fs-5 mb-5 fw-light animate__animated animate__fadeInUp" style="color: black;">
                                Pusat penelitian dan pengembangan teknologi 
                                <b style="color: black;">Computer Vision</b>, 
                                <b style="color: black;">Artificial Intelligence (AI)</b>, 
                                dan 
                                <b style="color: black;">Internet of Things (IoT)</b>
                            </p>

                            <div class="d-flex align-items-center mb-5 animate__animated animate__fadeInUp">
                                
                                <a class="py-3 px-5 me-3 fw-bold shadow-lg rounded-pill" 
                                href="admin/index.php" role="button" style="background-color: #FFBC3B; color:#1d4052"> 
                                <i class="fas fa-sign-in-alt me-2" style="color: #1d4052;"></i> LOGIN
                                </a>
                                
                                <a class="btn py-3 px-5 fw-bold rounded-pill" 
                                href="register_mahasiswa.php" role="button"
                                style="border: 2px solid #FFBC3B; color: #FFBC3B;">
                                <i class="fas fa-user-plus me-2"></i> REGISTER
                                </a>
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
    <section id="fokus-riset" class="py-5" style="background-color: #FFBC3B;">
        <div class="container">

            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-3" style="color: #1d4052;">Fokus Riset</h2>
                    <div style="width: 100px; height: 3px; background-color: #1d4052; margin: 0 auto 30px;"></div>
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
                            z-index: 2; background-color: #1d4052">
                            <i class="fa-solid fa-eye" style="color: white;"></i>
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
                            z-index: 2; background-color: #1d4052; color:white">

                            <!-- Icon Smart System -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-cpu" viewBox="0 0 16 16">
                                <path style="color: white" d="M5 0a.5.5 0 0 1 .5.5V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2A2.5 2.5 0 0 1 14 4.5h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14a2.5 2.5 0 0 1-2.5 2.5v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14A2.5 2.5 0 0 1 2 11.5H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2A2.5 2.5 0 0 1 4.5 2V.5A.5.5 0 0 1 5 0m-.5 3A1.5 1.5 0 0 0 3 4.5v7A1.5 1.5 0 0 0 4.5 13h7a1.5 1.5 0 0 0 1.5-1.5v-7A1.5 1.5 0 0 0 11.5 3zM5 6.5A1.5 1.5 0 0 1 6.5 5h3A1.5 1.5 0 0 1 11 6.5v3A1.5 1.5 0 0 1 9.5 11h-3A1.5 1.5 0 0 1 5 9.5zM6.5 6a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
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
                        <h1 class="display-6 mb-4" style="color: #1d4052;">Kegiatan & Proyek</h1>

                    </div>
                </div>
                <div class="col-md-12 col-lg-8 col-xl-9">
                    <div class="row g-5">
                        <div class="col-sm-6 col-md-4 wow fadeIn" data-wow-delay="0.1s">
                            <div class="service-item h-100"
                                style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                                <div class="btn-square bg-light mb-4">
                                    <i class="fa fa-solid fa-cloud-meatball fa-2x " style="color: #1d4052;"></i>
                                </div>
                                <h3 style="color: #FFBC3B;">Sistem Cerdas</h3>
                                <p class="mb-2" style="color: black;">Integrasi AI dengan sistem nyata untuk membantu pengambilan keputusan.
                                </p>
                                <div style="height: 3px; width: 100%; background-color: #1d4052; margin: 10px 0 0;">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 wow fadeIn" data-wow-delay="0.3s">
                            <div class="service-item h-100"
                                style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                                <div class="btn-square bg-light mb-4">
                                    <i class="fa fa-solid fa-layer-group fa-2x " style="color: #1d4052;"></i>
                                </div>
                                <h3 style="color: #FFBC3B;">Machine Learning</h3>
                                <p class="mb-2" style="color: black;">Pembelajaran mesin untuk klasifikasi, regresi, dan clustering
                                    menggunakan dataset nyata.</p>
                                <div style="height: 3px; width: 100%; background-color: #1d4052; margin: 10px 0 0;">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 wow fadeIn" data-wow-delay="0.5s">
                            <div class="service-item h-100"
                                style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                                <div class="btn-square bg-light mb-4">
                                    <i class="fa fa-regular fa-lightbulb fa-2x " style="color: #1d4052;"></i>
                                </div>
                                <h3 style="color: #FFBC3B;">Visi Komputer</h3>
                                <p class="mb-2" style="color: black;">Penerapan teknik AI pada pengolahan citra/video untuk mendeteksi dan
                                    mengenali objek.</p>
                                <div style="height: 3px; width: 100%; background-color: #1d4052; margin: 10px 0 0;">
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
                    <img class="img-fluid" src="admin/assets/img/<?php echo $logo_data['logo'] ?>" alt="Image"
                        style="max-width:500px;">
                </div>

                <!-- Content -->
                <div class="col-lg-6">

                    <!-- Section Title -->
                    <p class="section-title bg-white text-start pe-3" style="color: #FFBC3B;">
                        Profil Laboratorium
                    </p>

                    <!-- Main Heading -->
                    <h1 class="display-6 mb-4 wow fadeIn" data-wow-delay="0.2s" style="color: #1d4052;">
                        Laboratorium Visi Cerdas dan Sistem Cerdas
                    </h1>

                    <!-- Description -->
                    <p class="mb-4 wow fadeIn" data-wow-delay="0.3s" style="text-align: justify; color: black">
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
                <p class="section-title bg-white text-center px-3" style="color: #FFBC3B;">LAB IVSS</p>
                <h1 class="display-6 mb-3" style="color: #1d4052;">Visi & Misi</h1>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100 shadow-lg border-start border-5" style="border-color: #FFBC3B !important;">
                        <div class="card-body p-4">

                            <h3 class="card-title mb-3 d-flex align-items-center" style="color: #1d4052;">
                                <span class="icon-circle me-3"
                                    style="background-color: rgba(26, 104, 91, 0.1); color: #1d4052; padding: 8px 10px; border-radius: 8px;">
                                    <i class="fas fa-eye fa-lg"></i>
                                </span>
                                Visi
                            </h3>

                            <hr style="border-top: 2px solid #FFBC3B; opacity: 0.5;">

                            <p class="card-text text-dark">
                                <?php echo nl2br($isi_visi); ?>
                            </p>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card h-100 shadow-lg border-start border-5" style="border-color: #FFBC3B !important;">
                        <div class="card-body p-4" style="color: black;">

                            <h3 class="card-title mb-3 d-flex align-items-center" style="color: #1d4052;">
                                <span class="icon-circle me-3"
                                    style="background-color: rgba(26, 104, 91, 0.1); color: #1d4052; padding: 8px 10px; border-radius: 8px;">
                                    <i class="fas fa-check-circle fa-lg"></i>
                                </span>
                                Misi
                            </h3>

                            <hr style="border-top: 2px solid #FFBC3B; opacity: 0.5;">
                            <p><?php echo nl2br($isi_misi); ?></p>

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

                            <h2 class="display-5 fw-bold mb-4" style="color: #1d4052;">
                                SOP dan Layanan
                            </h2>

                            <p class="lead mb-5" style="color: black;">
                                Informasi standar operasional prosedur dan detail layanan Laboratorium.
                            </p>

                            <div class="row text-start g-4">

                                <div class="col-md-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-map-marker-alt fa-2x me-3" style="color: #FFBC3B;"></i>
                                        <div>
                                            <h5 class="fw-bold" style="color: #1d4052;">Lokasi</h5>
                                            <p class="mb-0 text-dark">Gedung Jurusan Teknologi Informasi — Lantai 8
                                                Barat</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-envelope fa-2x me-3" style="color: #FFBC3B;"></i>
                                        <div>
                                            <h5 class="fw-bold" style="color: #1d4052;">Surel</h5>
                                            <p class="mb-0 text-dark">—</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-clock fa-2x me-3" style="color: #FFBC3B;"></i>
                                        <div>
                                            <h5 class="fw-bold" style="color: #1d4052;">Jam Layanan</h5>
                                            <div class="d-flex justify-content-between text-dark" style="width: 200px;">
                                                <span>Mon-Fri</span>
                                                <span>07.00 — 21.00</span>
                                            </div>
                                            <div class="d-flex justify-content-between text-dark" style="width: 200px;">
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
                <p class="section-title bg-white text-center px-3" style="color: #FFBC3B;">LAB IVSS</p>
                <h1 class="display-6 mb-4" style="color: #1d4052;">Fasilitas Laboratorium</h1>
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
                                <h4 class="mb-2" style="color: #1d4052;">
                                    <?php echo $data_fasilitas['nama']; ?>
                                </h4>

                                <!-- Deskripsi kalau ada -->
                                <p style="color: #555; font-size: 0.95rem; flex-grow: 1;">
                                    <?php echo $data_fasilitas['isi'] ?? 'Informasi fasilitas belum tersedia.'; ?>
                                </p>

                                <!-- Tombol sejajar bawah -->
                                <a href="#!"
                                    class="btn  w-100 py-3 mt-auto"
                                    style="border-radius: 8px; background-color:#FFBC3B; color:white">
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
                <p class="section-title bg-white text-center px-3" style="color: #FFBC3B;">LAB IVSS</p>
                <h1 class="display-6 mb-4" style="color: #1d4052;">Peralatan Laboratorium</h1>
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
                        <div class="col-lg-3 col-md-6 col-12">
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
                                        style="text-decoration: none; color: #1d4052;">
                                        <?php echo $item['nama']; ?>
                                    </a>

                                    <!-- Deskripsi -->
                                    <p style="color: #555; font-size: 0.95rem; flex-grow: 1;">
                                        <?php echo $item['isi'] ?? 'Klik detail untuk informasi lebih lanjut.'; ?>
                                    </p>

                                    <!-- Tombol SELALU DI BAWAH -->
                                    <a href="#!" 
                                        class="btn w-100 py-3 mt-auto"
                                        style="border-radius: 8px; background-color:#FFBC3B; color:white">
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
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="section-title bg-white text-center px-3" style="color: #FFBC3B;">LAB IVSS</p>
                <h1 class="display-6 mb-4" style="color:#1d4052">Perkuliahan Terkait</h1>
            </div>
            <div class="row g-5 align-items-center">
                <div class="col-lg-12"> <div class="rounded overflow-hidden">
                        
                        <div class="d-flex flex-nowrap g-0" style="overflow-x: auto;"> 

                            <div class="col-3 wow fadeIn flex-shrink-0" data-wow-delay="0.1s">
                                <div class="text-center py-5 px-4 h-100" style="background-color: #FFBC3B;">
                                    <i class="fa-solid fa-brain fa-3x  mb-3" style="color: #1d4052;"></i>
                                    <h3 class="display-5 mb-0" style="font-size: 24px;">Kecerdasan Artifisial</h3>
                                    <span class="text-dark">Teknologi yang fokus pada pengembangan sistem atau mesin
                                        yang dapat melakukan tugas-tugas yang biasanya memerlukan kecerdasan manusia, seperti
                                        pengenalan pola, pembelajaran, pemecahan masalah, dan pengambilan keputusan.</span>
                                </div>
                            </div>
                            
                            <div class="col-3 wow fadeIn flex-shrink-0" data-wow-delay="0.3s">
                                <div class="text-center py-5 px-4 h-100" style="background-color: #1d4052;">
                                    <i class="fa fa-eye fa-3x  mb-3" style="color: #FFBC3B;"></i>
                                    <h3 class="display-5 text-white mb-0" style="font-size: 24px;">Pengolahan Citra &
                                        Visi Komputer</h3>
                                    <span class="text-white">Teknik untuk mengolah dan menganalisis gambar atau video
                                        menggunakan komputer, termasuk deteksi objek,
                                        segmentasi, pengenalan pola, dan interpretasi citra untuk aplikasi seperti
                                        pengenalan wajah dan kendaraan otomatis.</span>
                                </div>
                            </div>

                            <div class="col-3 wow fadeIn flex-shrink-0" data-wow-delay="0.7s">
                                <div class="text-center  py-5 px-4 h-100" style="background-color: #FFBC3B;">
                                    <i class="fa fa-lightbulb fa-3x  mb-3" style="color: #1d4052;"></i>
                                    <h1 class="display-5 mb-0" style="font-size: 24px;">Sistem Cerdas</h1>
                                    <span class="text-dark">Pengembangan sistem yang dapat meniru atau melampaui
                                        kemampuan kognitif manusia, seperti pengambilan
                                        keputusan otomatis, perencanaan, dan pemrosesan informasi dalam konteks aplikasi
                                        nyata, seperti robotika dan sistem pakar.</span>
                                </div>
                            </div>
                            
                            <div class="col-3 wow fadeIn flex-shrink-0" data-wow-delay="0.5s">
                                <div class="text-center  py-5 px-4 h-100" style="background-color: #1d4052;">
                                    <i class="fa fa-network-wired fa-3x  mb-3" style="color: #FFBC3B;"></i>
                                    <h3 class="display-5 text-white mb-0" style="font-size: 24px;">Machine Learning</h3>
                                    <span class="text-white">Cabang dari kecerdasan artifisial yang fokus pada
                                        pengembangan algoritma yang memungkinkan mesin belajar dari data untuk membuat prediksi atau keputusan tanpa
                                        dipogram secara eksplisit.</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->
    <div class="container py-5">

        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold display-5" style="color: #1d4052;">
                    <i class="fas fa-book-reader me-2"></i> Publikasi Dosen
                </h2>
                <div class="mx-auto" style="width: 150px; height: 5px; background-color: #FFBC3B; margin-top: 10px;">
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle rounded-4 overflow-hidden"
                        style="box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15); border: 1px solid #1d4052;">

                        <thead style="background-color: #1d4052;">
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
                                <td class=" px-4" style="color: black;">
                                    <span class="fw-bold me-2 " style="color: black;"><?php echo $no++; ?>.</span>
                                    <?php echo htmlspecialchars($data_dosen['nama'] ?? 'Nama Dosen Tidak Ada'); ?>
                                </td>

                                <td class="text-center">
                                    <a href="<?php echo $link_scopus; ?>" target="_blank"
                                        class="btn btn-sm <?php echo $scopus_class; ?>"
                                        style="<?php echo ($link_scopus !== '#') ? 'background-color: #FFBC3B; border-color: #FFBC3B;' : ''; ?>">
                                        <i class="fas fa-external-link-alt me-1"></i> Detail
                                    </a>
                                </td>

                                <td class="text-center">
                                    <a href="<?php echo $link_scholar; ?>" target="_blank"
                                        class="btn btn-sm <?php echo $scholar_class; ?>"
                                        style="<?php echo ($link_scholar !== '#') ? 'background-color: #FFBC3B; border-color: #FFBC3B;' : ''; ?>">
                                        <i class="fas fa-external-link-alt me-1"></i> Detail
                                    </a>
                                </td>

                                <td class="text-center">
                                    <a href="<?php echo $link_sinta; ?>" target="_blank"
                                        class="btn btn-sm <?php echo $sinta_class; ?>"
                                        style="<?php echo ($link_sinta !== '#') ? 'background-color: #FFBC3B; border-color: #FFBC3B;' : ''; ?>">
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
                <p class="section-title bg-white px-3 mb-2" style="color:#FFBC3B;">BERITA</p>
                <h1 class="display-6 mb-3" style="color: #1d4052;">Berita Terbaru</h1>
                <p class="lead " style="font-size: 1rem; color: black">Baca berita terbaru dari Laboratorium Visi Cerdas dan Sistem Cerdas.</p>
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

                        <a href="<?php echo $berita_link; ?>" class="h4  mb-2 fw-bold d-block" style="color:#FFBC3B;">
                            <?php echo htmlspecialchars($data_berita['judul']); ?>
                        </a>

                        <p class="flex-grow-1" style="color:black;">
                            <?php echo htmlspecialchars($isi_tampilan); ?>
                        </p>

                        <div class="bg-light p-3 rounded mt-3">
                            <p class="mb-1" style="color: #1d4052;">
                                <i class="fa fa-calendar-alt  me-2" style="color:#FFBC3B;"></i>
                                <?php echo $tanggal_format; ?>
                            </p>

                            <p class="mb-2" style="color: #1d4052;">
                                <i class="fa fa-user-edit  me-2" style="color: #FFBC3B;"></i>
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