<?php
include './backEnd/proses.php';
?>

<?php if ($mode === 'list'): ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laboratorium Visi Cerdas dan Sistem Cerdas</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed w-100 vh-100 top-50 start-50 translate-middle d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>

    <?php include('inc/navbar.php'); ?>

    <!-- Page Header -->
    <div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center py-4">
            <h3 class="display-3 animated slideInDown" style="color: #1d4052;">Daftar Dosen Laboratorium Visi Cerdas dan Sistem Cerdas</h3>
        </div>
    </div>

    <!-- Team Section -->
    <div class="container-fluid py-5" style="margin-top: 50px;">
        <div class="container">

            <?php if ($ketua_lab): ?>
            <h2 class="text-center mb-5 display-5 fw-bolder text-dark border-bottom border-primary pb-2">
                Ketua Laboratorium
            </h2>

            <div class="row justify-content-center mb-5">
                <div class="col-sm-10 col-md-8 col-lg-5 col-xl-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card team-card border-0 shadow-lg h-100 position-relative overflow-hidden rounded-4">
                        <div class="ratio ratio-1x1 bg-light">
                            <img src="admin/assets/img/<?php echo htmlspecialchars($ketua_lab['foto']); ?>"
                                alt="<?php echo htmlspecialchars($ketua_lab['nama']); ?>"
                                class="w-100 h-100 object-fit-contain d-block"
                                style="object-position: top; object-fit: contain !important;">
                        </div>

                        <div class="card-body team-detail text-center p-4">
                            <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($ketua_lab['nama']); ?></h4>
                            <p class="text-primary mb-3 fw-medium">
                                <?php echo htmlspecialchars($ketua_lab['jabatan']); ?></p>

                            <a href="detailDosen.php?nidn=<?php echo urlencode($ketua_lab['nidn']); ?>"
                                class="btn  btn-lg rounded-pill px-4 mt-2" style="background-color: #FFBC3B; color:#1d4052">
                                <i class="fas fa-user me-2"></i> Lihat Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <h2 class="text-center mb-5 display-5 fw-bolder text-dark border-bottom border-primary pb-2">
                Anggota Laboratorium
            </h2>

            <div class="row g-4 justify-content-center">
                <?php foreach ($member_lab_items as $row): ?>
                <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card team-card border-0 shadow h-100 position-relative overflow-hidden rounded-4">
                        <div class="ratio ratio-1x1 bg-light">
                            <img src="admin/assets/img/<?php echo htmlspecialchars($row['foto']); ?>"
                                alt="<?php echo htmlspecialchars($row['nama']); ?>"
                                class="w-100 h-100 object-fit-contain d-block"
                                style="object-position: top; object-fit: contain !important;">
                        </div>

                        <div class="card-body team-detail text-center p-4">
                            <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($row['nama']); ?></h5>
                            <p class="text-primary mb-3 fw-medium small">
                                <?php echo htmlspecialchars($row['jabatan']); ?></p>

                            <a href="detailDosen.php?nidn=<?php echo urlencode($row['nidn']); ?>"
                                class="btn btn-sm  rounded-pill px-3" style="background-color: #FFBC3B; color:#1d4052">
                                <i class="fas fa-user me-2"></i> Lihat Profil
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
    <?php include('inc/footer.php')?>

    <!-- JS -->
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
<?php endif; ?>