<?php
include 'koneksi.php';

// Data detail dosen yang akan ditampilkan di halaman ini
// AMBIL ID DARI URL
$nidn = isset($_GET['nidn']) ? $_GET['nidn'] : '';

if (empty($nidn)) {
    die("NIDN dosen tidak valid");
}


$sql = "SELECT * FROM dosen WHERE nidn = $1";
$result = pg_query_params($koneksi, $sql, [$nidn]);

if (!$result) {
    die("Query error: " . pg_last_error($koneksi));
}

// Ambil data
$dosen_detail = pg_fetch_assoc($result);

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
                        <a href="index.html" class="nav-item nav-link">Home</a>
                        <a href="about.html" class="nav-item nav-link">About</a>
                        <a href="service.html" class="nav-item nav-link">Service</a>
                        <a href="donation.html" class="nav-item nav-link">Donation</a>
                        <div class="nav-item dropdown">
                            <a href="#!" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu bg-light m-0">
                                <a href="event.html" class="dropdown-item">Event</a>
                                <a href="feature.html" class="dropdown-item">Feature</a>
                                <a href="team.html" class="dropdown-item active">Our Team</a>
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


    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center py-4">
            <h1 class="display-3 animated slideInDown">Detail Member</h1>
            <nav aria-label="breadcrumb animated slideInDown">
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <section class="blank-section pt-5" style="min-height: 100vh; margin-top: 100px; background-color: #f8f9fa;">
        <div class="container mt-5 pb-5">
            
            <?php if (!$dosen_detail): ?>
                <div class="alert alert-danger text-center fw-bold" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Data dosen tidak ditemukan.
                </div>
            <?php else: ?>
            
            <div class="row g-5">
                
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 h-100" style="border-radius: 15px;">
                        
                        <div class="bg-light p-4 rounded-top" style="border-radius: 15px 15px 0 0; text-align: center;">
                            <div class="mb-4 shadow-sm rounded-3 overflow-hidden d-inline-flex"
                                 style="width: 250px; height: 300px; justify-content:center; align-items:center; border: 3px solid #dee2e6;">
                                
                                <img src="admin/assets/img/<?php echo htmlspecialchars($dosen_detail['foto'] ?? 'default.png'); ?>" 
                                    alt="<?php echo htmlspecialchars($dosen_detail['nama'] ?? 'Dosen'); ?>"
                                    class="w-100 h-100"
                                    style="object-fit: contain; padding: 15px;">
                            </div>
                        </div>

                        <div class="card-body bg-white p-4">
                            <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                                <i class="bi bi-person-lines-fill me-2 text-warning"></i> Kontak
                            </h5>
                            <ul class="list-unstyled mb-0">
                                <?php if (!empty($dosen_detail['email'])): ?>
                                <li class="d-flex align-items-center mb-2">
                                    <i class="bi bi-envelope-fill me-3 text-secondary fs-5"></i> 
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block">Email</small>
                                        <a href="mailto:<?php echo htmlspecialchars($dosen_detail['email']); ?>" class="text-dark fw-bold" style="text-decoration: none; word-break: break-all;">
                                            <?php echo htmlspecialchars($dosen_detail['email']); ?>
                                        </a>
                                    </div>
                                </li>
                                <?php endif; ?>
                                <?php if (!empty($dosen_detail['linkedin'])): ?>
                                <li class="d-flex align-items-center mb-2">
                                    <i class="bi bi-linkedin me-3 text-secondary fs-5"></i> 
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block">LinkedIn</small>
                                        <a href="https://<?php echo htmlspecialchars($dosen_detail['linkedin']); ?>" 
                                            target="_blank" 
                                            class="btn btn-primary btn-sm rounded-pill fw-bold" 
                                            style="background-color: #14123A; color: #fff; text-transform: uppercase; padding: 4px 20px; font-size: 0.8rem;">
                                            Kunjungi Profil
                                        </a>
                                    </div>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    
                    <h1 class="fw-bolder text-dark mb-2" style="font-size: 2.5rem;">
                        <?php echo htmlspecialchars($dosen_detail['nama']); ?>
                    </h1>
                    <p class="lead text-secondary mb-4"><?php echo htmlspecialchars($dosen_detail['jabatan']); ?></p>
                    
                    <hr class="mt-0 mb-4 border-warning opacity-100" style="border-width: 3px !important;">

                    <div class="mb-5 p-4 bg-white rounded shadow-sm">
                        <h4 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle-fill me-2 text-warning"></i> Profil Dosen</h4>
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center px-0">
                                        <span class="fw-medium text-dark me-3" style="min-width: 120px;">NIP</span>: 
                                        <span class="fw-bold ms-1" style="color: #14123A;"><?php echo htmlspecialchars($dosen_detail['nip']); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center px-0">
                                        <span class="fw-medium text-dark me-3" style="min-width: 120px;">NIDN</span>: 
                                        <span class="fw-bold ms-1" style="color: #14123A;"><?php echo htmlspecialchars($dosen_detail['nidn']); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center px-0">
                                        <span class="fw-medium text-dark me-3" style="min-width: 120px;">Jabatan</span>: 
                                        <span class="fw-bold ms-1" style="color: #14123A;"><?php echo htmlspecialchars($dosen_detail['jabatan']); ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-5 p-4 bg-white rounded shadow-sm">
                        <h4 class="fw-bold text-dark mb-3">
                            <i class="bi bi-mortarboard-fill me-2 text-warning"></i> Pendidikan</h4>
                        <p class="text-dark">
                            <div class="row">
                                <span class="fw-bold text-dark ms-1"><?php echo $dosen_detail['pendidikan']; ?></span>
                            </div>
                        </p>
                    </div>

                    <div class="mb-5 p-4 bg-white rounded shadow-sm">
                        <h4 class="fw-bold text-dark mb-3">
                            <i class="bi bi-journals me-2 text-warning"></i> Sorotan Publikasi</h4>
                        <div class="d-flex flex-wrap gap-2">
                            <?php if (!empty($dosen_detail['scopus'])): ?>
                            <a href="<?php echo htmlspecialchars($dosen_detail['scopus']); ?>" target="_blank" class="btn rounded-pill fw-bold"
                            style="background-color: #14123A; color: #fff; border: none; pointer-events: auto; text-decoration: none; font-size: 0.85rem; padding: 4px 18px;">
                                <i class="bi bi-link-45deg me-1"></i> Scopus Profile
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($dosen_detail['google_scholar'])): ?>
                            <a href="<?php echo htmlspecialchars($dosen_detail['google_scholar']); ?>" target="_blank" class="btn rounded-pill fw-bold"
                            style="background-color: #14123A; color: #fff; border: none; pointer-events: auto; text-decoration: none; font-size: 0.85rem; padding: 4px 18px;">
                                <i class="bi bi-link-45deg me-1"></i> Google Scholar Profile
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($dosen_detail['sinta'])): ?>
                            <a href="<?php echo htmlspecialchars($dosen_detail['sinta']); ?>" target="_blank" class="btn rounded-pill fw-bold"
                            style="background-color: #14123A; color: #fff; border: none; pointer-events: auto; text-decoration: none; font-size: 0.85rem; padding: 4px 18px;">
                                <i class="bi bi-link-45deg me-1"></i> Sinta Profile
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php endif; ?>
        </div>
    </section>
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