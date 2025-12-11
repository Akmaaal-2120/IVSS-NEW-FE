<?php
require_once 'koneksi.php';
$sql = "SELECT * FROM logo";
$result = pg_query($koneksi, $sql);
$logo_data = pg_fetch_assoc($result);
?>
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
                                        style="max-width: 80px; margin-left: -100px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- Topbar End -->
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
                        <a href="index.php" class="nav-item nav-link active">Home</a>
                        <a href="about.php" class="nav-item nav-link">About</a>
                        <a href="service.php" class="nav-item nav-link">Service</a>
                        <a href="donation.php" class="nav-item nav-link">Donation</a>

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu bg-light m-0">
                                <a href="event.php" class="dropdown-item">Event</a>
                                <a href="feature.php" class="dropdown-item">Feature</a>
                                <a href="team.php" class="dropdown-item">Our Team</a>
                                <a href="testimonial.php" class="dropdown-item">Testimonial</a>
                                <a href="404.php" class="dropdown-item">404 Page</a>
                            </div>
                        </div>

                        <a href="contact.php" class="nav-item nav-link">Contact</a>
                    </div>

                    <div class="d-none d-lg-flex ms-auto">
                        <a class="btn btn-square btn-dark ms-2" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-square btn-dark ms-2" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-dark ms-2" href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </nav>
        </div>
    </div>