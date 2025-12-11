<?php
        include 'koneksi.php';

        if (!isset($_GET['id'])) {
            echo "<h3>Berita tidak ditemukan.</h3>";
            exit;
        }

        $id = intval($_GET['id']);

        $sql = "
            SELECT *,
                TO_CHAR(tanggal, 'DD Month YYYY') AS tanggal_format
            FROM berita 
            WHERE berita_id = $id
        ";
        $result = pg_query($koneksi, $sql);
        $berita = pg_fetch_assoc($result);

        if (!$berita) {
            echo "<h3>Berita tidak ditemukan.</h3>";
            exit;
        }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Charitize - Charity Organization Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="img/favicon.ico" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@600;700&family=Open+Sans&display=swap"
        rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet"> 

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>
    <div class="container-fluid bg-secondary top-bar wow fadeIn" data-wow-delay="0.1s">
        <div class="row align-items-center h-100">
            <div class="col-lg-4 text-center text-lg-start">
                <a href="index.html">
                    <h1 class="display-5 text-primary m-0">Charitize</h1>
                </a>
            </div>
            <div class="col-lg-8 d-none d-lg-block">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="d-flex justify-content-end">
                            <div class="flex-shrink-0 btn-square bg-primary">
                                <i class="fa fa-phone-alt text-dark"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="text-primary mb-0">Call Us</h6>
                                <span class="text-white">+012 345 6789</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex justify-content-end">
                            <div class="flex-shrink-0 btn-square bg-primary">
                                <i class="fa fa-envelope-open text-dark"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="text-primary mb-0">Mail Us</h6>
                                <span class="text-white">info@domain.com</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex justify-content-end">
                            <div class="flex-shrink-0 btn-square bg-primary">
                                <i class="fa fa-map-marker-alt text-dark"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="text-primary mb-0">Address</h6>
                                <span class="text-white">123 Street, NY, USA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                        <a href="index.html" class="nav-item nav-link">Home</a>
                        <a href="about.html" class="nav-item nav-link">About</a>
                        <a href="service.html" class="nav-item nav-link">Service</a>
                        <a href="donation.html" class="nav-item nav-link">Donation</a>
                        <div class="nav-item dropdown">
                            <a href="#!" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu bg-light m-0">
                                <a href="event.html" class="dropdown-item active">Event</a>
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
    <div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center py-4">
            <h1 class="display-3 animated slideInDown">Berita</h1>
            <nav aria-label="breadcrumb animated slideInDown">
            </nav>
        </div>
    </div>
    <section class="py-5">
    <div class="container">

        <!-- Judul -->
        <h1 class="fw-bold" style="color:#1A1A37; font-size:40px;">
            <?= htmlspecialchars($berita['judul']); ?>
        </h1>
        <div style="width:150px; height:4px; background:#1A1A37; margin-top:10px; margin-bottom:30px;"></div>

        <!-- Gambar -->
        <div class="w-100 mb-4" style="height:420px; border-radius:15px; overflow:hidden;">
            <img src="admin/assets/img/<?= htmlspecialchars($berita['gambar']); ?>"
                 style="width:100%; height:100%; object-fit:cover;" alt="">
        </div>

        <!-- Meta -->
        <div class="d-flex align-items-center gap-4 mb-4 text-dark">

            <div class="d-flex align-items-center">
                <i class="lni lni-user me-2" style="font-size:20px; color:#1A1A37;"></i>
                <span class="fw-semibold">
                    <?= htmlspecialchars($berita['penulis']); ?>
                </span>
            </div>

            <div class="d-flex align-items-center">
                <i class="lni lni-calendar me-2" style="font-size:20px; color:#1A1A37;"></i>
                <span><?= $berita['tanggal_format']; ?></span>
            </div>

        </div>

        <!-- Isi Berita -->
        <div style="line-height:1.9; color:#444; font-size:18px; margin-top:25px;">
    <?= $berita['isi']; ?>
</div>


        <!-- Tombol Kembali -->
        <a href="berita.php"
           class="btn px-4 py-2 mt-4"
           style="background:#FFBC3B; color:#1A1A37; font-weight:600; border-radius:50px;">
            Kembali Ke Daftar Berita
        </a>

    </div>
</section>



    <?php include 'footer.php' ?>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>

    <script src="js/main.js"></script>
</body>

</html>