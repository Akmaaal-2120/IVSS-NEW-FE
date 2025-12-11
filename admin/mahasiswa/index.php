<?php
session_start();
include '../inc/koneksi.php';

// proteksi: hanya mahasiswa boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// ambil nama mahasiswa
$query = "SELECT nama FROM mahasiswa WHERE user_id = $1";
$res = pg_query_params($koneksi, $query, [$user_id]);
$nama_mhs = ($res && pg_num_rows($res) > 0) ? pg_fetch_result($res, 0, 'nama') : ($_SESSION['username'] ?? 'Mahasiswa');

// ambil summary counts
$totalMahasiswa = pg_fetch_result(pg_query($koneksi, "SELECT COUNT(*) FROM mahasiswa"), 0, 0);
$totalDosen     = pg_fetch_result(pg_query($koneksi, "SELECT COUNT(*) FROM dosen"), 0, 0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Dashboard Mahasiswa - Lab IVSS</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<style>
.card-icon { font-size: 2rem; }
</style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- Topbar -->
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
                <h1 class="h3 mb-4 text-gray-800">Selamat datang, <?= htmlspecialchars($nama_mhs) ?>!</h1>

                <div class="row mb-4">
                    <!-- Card: Total Mahasiswa -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Mahasiswa</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int)$totalMahasiswa ?></div>
                                </div>
                                <i class="fas fa-user-graduate text-primary card-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- /.container-fluid -->
        </div> <!-- /#content -->

        <?php include '../inc/footer.php'; ?>
    </div>
</div>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
</body>
</html>
