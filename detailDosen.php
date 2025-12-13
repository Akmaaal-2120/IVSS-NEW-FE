<?php
include 'backEnd/prosesDosen.php';
?>

<?php if ($mode === 'detail'): ?>
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
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@600;700&family=Open+Sans&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

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

        <div class="container mt-5 pb-5">

            <?php if (!$dosen_detail): ?>
                <div class="alert alert-danger text-center fw-bold" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Data dosen tidak ditemukan.
                </div>

            <?php else: ?>

                <div class="row g-5">

                    <!-- SIDEBAR (FOTO & KONTAK) -->
                    <div class="col-md-4 border-end border-4 border-secondary">

                        <!-- Foto -->
                        <div class="text-center mb-4">
                            <div class="mb-4 shadow-sm rounded-3 overflow-hidden d-inline-flex"
                                style="width: 250px; height: 300px; justify-content:center; align-items:center; border: 3px solid #dee2e6;">

                                <img src="admin/assets/img/<?php echo htmlspecialchars($dosen_detail['foto'] ?? 'default.png'); ?>"
                                    alt="<?php echo htmlspecialchars($dosen_detail['nama'] ?? 'Dosen'); ?>"
                                    class="w-100 h-100"
                                    style="object-fit: contain; padding: 15px;">
                            </div>
                        </div>

                        <!-- Kontak -->
                        <div class="p-0">
                            <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                                <i class="bi bi-person-lines-fill me-2 text-warning"></i> Kontak
                            </h5>
                            <ul class="list-unstyled mb-0">
                                <?php if (!empty($dosen_detail['email'])): ?>
                                    <li class="d-flex align-items-center mb-2">
                                        <i class="bi bi-envelope-fill me-3  fs-5" style="color: #1d4052;"></i>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block">Email</small>
                                            <a href="mailto:<?php echo htmlspecialchars($dosen_detail['email']); ?>"
                                            class="text-dark fw-bold"
                                            style="text-decoration: none; word-break: break-all;">
                                            <?php echo htmlspecialchars($dosen_detail['email']); ?>
                                            </a>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                <?php if (!empty($dosen_detail['linkedin'])): ?>
                                    <li class="d-flex align-items-center mb-2" >
                                        <i class="bi bi-linkedin me-3  fs-5"  style="color: #1d4052;"></i>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block">LinkedIn</small>
                                            <a href="https://<?php echo htmlspecialchars($dosen_detail['linkedin']); ?>"
                                            target="_blank"
                                            class="btn btn-primary btn-sm rounded-pill fw-bold"
                                            style="background-color: #1d4052; color: #fff; border: none; pointer-events: auto; text-decoration: none; font-size: 0.85rem; padding: 4px 18px;">
                                            Kunjungi Profil
                                            </a>
                                        </div>
                                    </li>
                                <?php endif; ?>

                            </ul>
                        </div>

                    </div>

                    <!-- MAIN CONTENT -->
                    <div class="col-md-8">

                        <!-- Nama & Jabatan -->
                        <h1 class="fw-bolder text-dark mb-2" style="font-size: 2.5rem;">
                            <?php echo htmlspecialchars($dosen_detail['nama']); ?>
                        </h1>

                        <p class="lead text-dark mb-4">
                            <?php echo htmlspecialchars($dosen_detail['jabatan']); ?>
                        </p>

                        <hr class="mt-0 mb-4 border-warning opacity-100" style="border-width: 3px !important;">

                        <!-- Profil Dosen -->
                        <div class="mb-5 p-4 bg-white rounded shadow-sm">
                            <h4 class="fw-bold text-dark mb-3">
                                <i class="bi bi-info-circle-fill me-2 text-warning"></i> Profil Dosen
                            </h4>

                            <div class="row">
                                <div class="col-sm-12">

                                    <ul class="list-group list-group-flush">

                                        <li class="list-group-item d-flex align-items-center px-0">
                                            <span class="fw-medium text-dark me-3" style="min-width: 120px;">NIP</span>:
                                            <span class="fw-bold ms-1" style="color: #14123A;">
                                                <?php echo htmlspecialchars($dosen_detail['nip']); ?>
                                            </span>
                                        </li>

                                        <li class="list-group-item d-flex align-items-center px-0">
                                            <span class="fw-medium text-dark me-3" style="min-width: 120px;">NIDN</span>:
                                            <span class="fw-bold ms-1" style="color: #14123A;">
                                                <?php echo htmlspecialchars($dosen_detail['nidn']); ?>
                                            </span>
                                        </li>

                                        <li class="list-group-item d-flex align-items-center px-0">
                                            <span class="fw-medium text-dark me-3" style="min-width: 120px;">Jabatan</span>:
                                            <span class="fw-bold ms-1" style="color: #14123A;">
                                                <?php echo htmlspecialchars($dosen_detail['jabatan']); ?>
                                            </span>
                                        </li>

                                    </ul>

                                </div>
                            </div>
                        </div>

                        <!-- Pendidikan -->
                        <div class="mb-5 p-4 bg-white rounded shadow-sm">
                            <h4 class="fw-bold text-dark mb-3">
                                <i class="bi bi-mortarboard-fill me-2 text-warning"></i> Pendidikan</h4>
                            <p class="text-dark">
                                <div class="row">
                                    <span class="fw-bold text-dark ms-1"><?php echo $dosen_detail['pendidikan']; ?></span>
                                </div>
                            </p>
                        </div>

                        <!-- Sorotan Publikasi -->
                        <div class="mb-5 p-4 bg-white rounded shadow-sm">
                            <h4 class="fw-bold text-dark mb-3">
                                <i class="bi bi-journals me-2 text-warning"></i> Sorotan Publikasi</h4>
                            <div class="d-flex flex-wrap gap-2">
                                <?php if (!empty($dosen_detail['scopus'])): ?>
                                <a href="<?php echo htmlspecialchars($dosen_detail['scopus']); ?>" target="_blank" class="btn rounded-pill fw-bold"
                                style="background-color: #1d4052; color: #fff; border: none; pointer-events: auto; text-decoration: none; font-size: 0.85rem; padding: 4px 18px;">
                                    <i class="bi bi-link-45deg me-1"></i> Scopus Profile
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($dosen_detail['google_scholar'])): ?>
                                <a href="<?php echo htmlspecialchars($dosen_detail['google_scholar']); ?>" target="_blank" class="btn rounded-pill fw-bold"
                                style="background-color: #1d4052; color: #fff; border: none; pointer-events: auto; text-decoration: none; font-size: 0.85rem; padding: 4px 18px;">
                                    <i class="bi bi-link-45deg me-1"></i> Google Scholar Profile
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($dosen_detail['sinta'])): ?>
                                <a href="<?php echo htmlspecialchars($dosen_detail['sinta']); ?>" target="_blank" class="btn rounded-pill fw-bold"
                                style="background-color: #1d4052; color: #fff; border: none; pointer-events: auto; text-decoration: none; font-size: 0.85rem; padding: 4px 18px;">
                                    <i class="bi bi-link-45deg me-1"></i> Sinta Profile
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Riset Terkini -->
                        <div class="mb-5 p-4 bg-white rounded shadow-sm">
                            <h4 class="fw-bold text-dark mb-3">
                                <i class="bi bi-journal-text me-2 text-warning"></i> Riset Terkini
                            </h4>

                            <div class="card shadow border-0" style="border-radius: 10px;">
                                <table class="table mb-0">
                                    
                                    <thead style="background-color: #1d4052;">
                                        <tr>
                                            <th scope="col" class="text-white text-center" style="width: 10%;">No</th>
                                            <th scope="col" class="text-white">Judul Riset Terbaru</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <tr style="background-color: #FFBC3B;">
                                            <td class="fw-bold text-center pt-3 pb-3" style="color: #000;">1.</td>
                                            <td class="fw-bold pt-3 pb-3" style="color: #000;">Pemanfaatan Wireshark untuk Sniffing Komunikasi Data Berprotokol HTTP pada Jaringan Internet</td>
                                        </tr>
                                        
                                        <tr style="background-color: #FFBC3B;">
                                            <td class="fw-bold text-center pt-3 pb-3" style="color: #000;">2.</td>
                                            <td class="fw-bold pt-3 pb-3" style="color: #000;">Segmentasi berbasis k-means pada deteksi citra penyakit daun tanaman jagung</td>
                                        </tr>
                                        
                                        </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            <?php endif; ?>

        </div>

    <?php include('inc/footer.php')?>

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
<?php endif; ?>