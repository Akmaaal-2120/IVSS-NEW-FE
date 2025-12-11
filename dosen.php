<?php
include 'inc/koneksi.php';

// Ambil data Member Lab 
$member_lab_res = pg_query($koneksi, "SELECT * FROM dosen WHERE jabatan!='Kepala Lab' ORDER BY nama ASC");
$member_lab_items = [];
while ($row = pg_fetch_assoc($member_lab_res)) {
    $member_lab_items[] = $row;
}

// Ambil data Ketua Lab
$result = pg_query($koneksi, "SELECT * FROM dosen WHERE jabatan='Kepala Lab' LIMIT 1");
$ketua_lab = pg_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laboratorium Visi Cerdas dan Sistem Cerdas</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@600;700&family=Open+Sans&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons & FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Bootstrap & Template CSS -->
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
            <h1 class="display-3 animated slideInDown">Daftar Member</h1>
        </div>
    </div>

    <!-- Team Section -->
    <div class="container-fluid py-5" style="margin-top: 100px;">
        <div class="container">

            <!-- Ketua Lab -->
            <?php if ($ketua_lab): ?>
            <h3 class="text-center mb-4 fw-bold text-dark">Ketua Laboratorium</h3>

            <div class="row justify-content-center mb-5">
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                    <div class="team-item d-flex h-100 p-4" style="min-height: 420px;">

                        <div class="team-detail pe-4">

                            <div class="ratio ratio-1x1 mb-4">
                                <img src="admin/assets/img/<?php echo htmlspecialchars($ketua_lab['foto']); ?>"
                                    alt="<?php echo htmlspecialchars($ketua_lab['nama']); ?>"
                                    class="rounded shadow-sm object-fit-cover" style="object-position: top;">
                            </div>

                            <h3 class="fw-bold"><?php echo htmlspecialchars($ketua_lab['nama']); ?></h3>
                            <span class="text-secondary"><?php echo htmlspecialchars($ketua_lab['jabatan']); ?></span>
                        </div>

                        <div class="team-social bg-light d-flex flex-column justify-content-center p-3 rounded">
                            <a class="btn btn-square btn-primary my-2"
                                href="detailDosen.php?nidn=<?php echo urlencode($ketua_lab['nidn']); ?>">
                                <i class="fas fa-user"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>


            <?php endif; ?>

            <hr class="my-4">

            <!-- Anggota Lab -->
            <h3 class="text-center mb-4 fw-bold text-dark">Anggota Laboratorium</h3>

            <div class="row g-4">
                <?php foreach ($member_lab_items as $row): ?>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                    <div class="team-item d-flex h-100 p-4">

                        <div class="team-detail pe-4">

                            <div class="ratio ratio-1x1 mb-4">
                                <img src="admin/assets/img/<?php echo htmlspecialchars($row['foto']); ?>"
                                    alt="<?php echo htmlspecialchars($row['nama']); ?>"
                                    class="rounded shadow-sm object-fit-cover" style="object-position: top;">
                            </div>

                            <h3 class="fw-bold"><?php echo htmlspecialchars($row['nama']); ?></h3>
                            <span class="text-secondary"><?php echo htmlspecialchars($row['jabatan']); ?></span>
                        </div>

                        <div class="team-social bg-light d-flex flex-column justify-content-center p-3 rounded">
                            <a class="btn btn-square btn-primary my-2"
                                href="detailDosen.php?nidn=<?php echo urlencode($row['nidn']); ?>">
                                <i class="fas fa-user"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
            </div>

        </div>
    </div>

    <?php include('inc/footer.php'); ?>

    <a href="#!" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

</body>

</html>
