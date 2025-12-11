<?php
        include 'inc/koneksi.php';

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
    <title>Laboratorium Visi Cerdas dan Sistem Cerdas</title>
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
    <?php include('inc/navbar.php');?>
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
            <a href="berita.php" class="btn px-4 py-2 mt-4"
                style="background:#FFBC3B; color:#1A1A37; font-weight:600; border-radius:50px;">
                Kembali Ke Daftar Berita
            </a>

        </div>
    </section>



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