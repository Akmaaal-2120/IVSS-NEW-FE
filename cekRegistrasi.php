<?php
include './backEnd/prosesCekRegistrasi.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cek Status Registrasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
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

<body class="bg-light">
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>
    <?php include('inc/navbar.php');?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card shadow-lg border-0 p-4" style="border-radius: 15px;">

                    <div class="text-center mb-3">
                        <img src="admin/assets/img/<?php echo $logo_data['logo'] ?>" alt="Logo IVSS"
                            class="img-fluid d-block mx-auto mb-3" style="max-width: 160px;">
                    </div>

                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark">Cek Status Registrasi</h4>
                        <p class="text-muted">Masukkan NIM Anda untuk mengetahui status pengajuan.</p>
                    </div>

                    <form method="post">
                        <div class="mb-4">
                            <label for="nimInput" class="form-label fw-bold">Masukkan NIM:</label>
                            <input type="text" name="nim" id="nimInput" class="form-control form-control-sm shadow-sm"
                                placeholder="Masukkan NIM yang didaftarkan" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-sm fw-bold  shadow"
                                style="background-color: #1d4052; color:white">
                                Cek Status
                            </button>
                            <a href="index.php" class="btn btn-outline-secondary btn-sm">Kembali ke Beranda</a>
                        </div>
                    </form>

                    <?php if ($hasil): ?>
                    <hr class="mt-4 mb-3">
                    <?php 
                        // Tentukan variabel untuk status, ikon, dan warna
                        $icon_class = 'lni lni-question-circle'; // Default
                        $alert_class = 'bg-info-subtle text-info border border-info'; // Default: Info
                        
                        if (strpos($hasil, 'disetujui') !== false || strpos($hasil, 'akun Anda sudah dibuat') !== false) {
                            $alert_class = 'bg-success-subtle text-success border border-success';
                            $icon_class = 'lni lni-checkmark-circle';
                        } elseif (strpos($hasil, 'pending') !== false) {
                            $alert_class = 'bg-warning-subtle text-warning border border-warning';
                            $icon_class = 'lni lni-timer'; // Ikon jam untuk pending
                        } elseif (strpos($hasil, 'Maaf, Anda belum registrasi') !== false || strpos($hasil, 'Masukkan NIM') !== false) {
                            $alert_class = 'bg-secondary-subtle text-secondary border border-secondary';
                             $icon_class = 'lni lni-search-alt'; // Ikon pencarian untuk belum registrasi/input
                        } else {
                            // Untuk kasus umum atau kegagalan
                            $alert_class = 'bg-danger-subtle text-danger border border-danger';
                            $icon_class = 'lni lni-close';
                        }
                    ?>

                    <div class="p-3 rounded-3 <?= $alert_class ?>">
                        <div class="d-flex align-items-start">
                            <span class="fs-4 me-3 mt-1">
                                <i class="<?= $icon_class ?>"></i>
                            </span>
                            <p class="mb-0 fw-semibold text-break pt-1"><?= $hasil ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
    <?php include('inc/footer.php')?>
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