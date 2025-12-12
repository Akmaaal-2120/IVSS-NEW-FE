<?php
require_once 'koneksi.php';
$sql = "SELECT * FROM logo";
$result = pg_query($koneksi, $sql);
$logo_data = pg_fetch_assoc($result);
?>

<?php
require_once 'koneksi.php';
$sql = "SELECT * FROM logo";
$result = pg_query($koneksi, $sql);
$logo_data = pg_fetch_assoc($result);
?>
    <!-- Topbar Start -->
<div class="container-fluid top-bar wow fadeIn" data-wow-delay="0.1s" style="background-color:#FFBC3B;">
    <div class="row align-items-center h-100 py-2">

        <!-- Logo rata kiri -->
        <div class="col-lg-3 col-12 text-lg-start text-center">
            <a href="index.html">
                <img class="img-fluid" 
                    src="../../IVSS-LAB/admin/assets/img/ivss_logo_no-desc.png" 
                    alt="Logo"
                    style="max-width: 80px;">
            </a>
        </div>

        <!-- Contact info rata kanan -->
        <div class="col-lg-9 col-12">
            <div class="row justify-content-lg-end justify-content-center g-3">

                <!-- Call -->
                <div class="col-lg-3 col-6">
                    <div class="d-flex justify-content-lg-end justify-content-center">
                        <div class="flex-shrink-0 btn-square" style="background-color:#FFF3CD;">
                            <i class="fa fa-phone-alt text-dark"></i>
                        </div>
                        <div class="ms-2">
                            <h6 class="text-dark mb-0">Call Us</h6>
                            <span class="text-dark">+012 345 6789</span>
                        </div>
                    </div>
                </div>

                <!-- Mail -->
                <div class="col-lg-3 col-6">
                    <div class="d-flex justify-content-lg-end justify-content-center">
                        <div class="flex-shrink-0 btn-square" style="background-color:#FFF3CD;">
                            <i class="fa fa-envelope-open text-dark"></i>
                        </div>
                        <div class="ms-2">
                            <h6 class="text-dark mb-0">Mail Us</h6>
                            <span class="text-dark">info@domain.com</span>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="col-lg-3 col-6">
                    <div class="d-flex justify-content-lg-end justify-content-center">
                        <div class="flex-shrink-0 btn-square" style="background-color:#FFF3CD;">
                            <i class="fa fa-map-marker-alt text-dark"></i>
                        </div>
                        <div class="ms-2">
                            <h6 class="text-dark mb-0">Address</h6>
                            <span class="text-dark">123 Street, NY, USA</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

    <!-- Topbar End -->
    <div class="container-fluid px-0 wow fadeIn" data-wow-delay="0.1s" style="background-color: #1d4052;">
        <div class="nav-bar">
            <nav class="navbar navbar-expand-lg navbar-dark px-4 py-lg-0" style="background-color: #1d4052;">
                <h4 class="d-lg-none m-0">Menu</h4>
                <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav me-auto">
                        <a href="index.php" class="nav-item nav-link" style="color: white;">Beranda</a>
                        <a href="berita.php" class="nav-item nav-link" style="color: white;">Berita</a>
                        <a href="dosen.php" class="nav-item nav-link" style="color: white;">Daftar Member</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>

   