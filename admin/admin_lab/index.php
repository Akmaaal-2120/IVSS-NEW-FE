<?php
session_start();

include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin')) {
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
    $nama_dosen = $_SESSION['username']; // fallback
}

$totalMahasiswa = 0;
$totalDosen = 0;
$totalPending = 0;

$res = @pg_query($koneksi, "SELECT COUNT(*) FROM mahasiswa");
if ($res) { $totalMahasiswa = (int) pg_fetch_result($res, 0, 0); pg_free_result($res); }

$res = @pg_query($koneksi, "SELECT COUNT(*) FROM dosen");
if ($res) { $totalDosen = (int) pg_fetch_result($res, 0, 0); pg_free_result($res); }

$res = @pg_query($koneksi, "SELECT COUNT(*) FROM mahasiswa WHERE status_pendaftaran = 'pending'");
if ($res) { $totalPending = (int) pg_fetch_result($res, 0, 0); pg_free_result($res); }

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Dashboard Admin Lab</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<style>
.card .h5 { font-size:1.25rem; }
</style>
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
                    <!-- Total Mahasiswa -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Mahasiswa</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int)$totalMahasiswa ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-user-graduate fa-2x text-primary"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Dosen -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Dosen</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int)$totalDosen ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-chalkboard-teacher fa-2x text-success"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pendaftaran Pending -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendaftaran Pending</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int)$totalPending ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-hourglass-half fa-2x text-warning"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /.row -->

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
