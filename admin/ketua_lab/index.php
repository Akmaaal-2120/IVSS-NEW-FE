<?php
session_start();

include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil nama dosen langsung dari DB
$q = pg_query_params($koneksi, 'SELECT nama FROM dosen WHERE user_id = $1', [$user_id]);
if ($q && pg_num_rows($q) > 0) {
    $row = pg_fetch_assoc($q);
    $nama_dosen = $row['nama'];
    pg_free_result($q);
} else {
    $nama_dosen = $_SESSION['username']; // fallback kalau belum ada record dosen
}

// Ambil data jumlah
$totalMahasiswa = pg_fetch_result(pg_query($koneksi, "SELECT COUNT(*) FROM mahasiswa"), 0, 0);
$totalDosen     = pg_fetch_result(pg_query($koneksi, "SELECT COUNT(*) FROM dosen"), 0, 0);
$totalBerita    = pg_fetch_result(pg_query($koneksi, "SELECT COUNT(*) FROM berita"), 0, 0);
$totalFasilitas = pg_fetch_result(pg_query($koneksi, "SELECT COUNT(*) FROM fasilitas"), 0, 0);
$username       = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard Ketua Lab</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary font-weight-bold">Dashboard</span>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="profil.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-tie mr-1"></i> Profil
                        </a>
                        <a href="../logout.php" class="btn btn-danger btn-sm">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Selamat datang, <?= htmlspecialchars($nama_dosen) ?>!</h1>

                <div class="row">
                    <!-- Card Mahasiswa -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Data Mahasiswa</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalMahasiswa ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-user-graduate fa-2x text-primary"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Dosen -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Daftar Dosen</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalDosen ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-chalkboard-teacher fa-2x text-success"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Berita -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Berita</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalBerita ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-newspaper fa-2x text-warning"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Fasilitas -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Fasilitas</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalFasilitas ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-building fa-2x text-danger"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php include '../inc/footer.php'; ?>
    </div>
</div>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
</body>
</html>
