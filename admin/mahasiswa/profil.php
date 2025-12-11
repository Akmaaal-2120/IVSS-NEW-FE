<?php
session_start();
include '../inc/koneksi.php';

// Cek session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data mahasiswa + dosen pembimbing
$query = '
    SELECT u.username, u.role,
           m.nim, m.nama, m.jenis_kelamin, m.no_telp, m.email, m.keperluan,
           m.status_mahasiswa, m.tanggal_daftar, m.status_pendaftaran,
           d.nama AS dosen_pembimbing
    FROM users u
    LEFT JOIN mahasiswa m ON m.user_id = u.user_id
    LEFT JOIN dosen d ON d.nip = m.dosen_pembimbing
    WHERE u.user_id = $1
';
$result = pg_query_params($koneksi, $query, [$user_id]);

if ($result && pg_num_rows($result) === 1) {
    $mahasiswa = pg_fetch_assoc($result);
} else {
    $mahasiswa = [
        'username' => $_SESSION['username'],
        'role' => $_SESSION['role'],
        'nama' => $_SESSION['username'],
    ];
}

if ($result) pg_free_result($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Profil Mahasiswa - Lab IVSS</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<style>
.profile-img {
    width:150px;
    height:150px;
    object-fit:cover;
    border-radius:8px;
    border:1px solid #e3e6f0;
}
.info-label { font-weight:600; color:#4e73df; }
</style>
</head>
<body id="page-top">

<div id="wrapper">
<?php include 'inc/sidebar.php'; ?>

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>
            <span class="h5 mb-0 text-primary font-weight-bold">Profil Mahasiswa</span>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="index.php" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                </li>
            </ul>
        </nav>

        <div class="container-fluid">
            <h1 class="h3 mb-4 text-gray-800">Profil Mahasiswa</h1>

            <div class="row">
                <!-- Profil Card -->
                <div class="col-lg-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-0"><?= htmlspecialchars($mahasiswa['nama'] ?? '-') ?></h5>
                            <p class="text-muted small">Login sebagai <?= htmlspecialchars($mahasiswa['role'] ?? '-') ?>.</p>
                            <a href="ubah_password.php?id=<?= urlencode($user_id) ?>" class="btn btn-secondary btn-sm mt-2">
                                <i class="fas fa-key mr-1"></i> Ubah Password
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profil Detail -->
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header"><strong>Detail Mahasiswa</strong></div>
                        <div class="card-body">
                            <p><span class="info-label">NIM:</span> <?= htmlspecialchars($mahasiswa['nim'] ?? '-') ?></p>
                            <p><span class="info-label">Email:</span> <?= htmlspecialchars($mahasiswa['email'] ?? '-') ?></p>
                            <p><span class="info-label">No. Telp:</span> <?= htmlspecialchars($mahasiswa['no_telp'] ?? '-') ?></p>
                            <p><span class="info-label">Jenis Kelamin:</span> <?= htmlspecialchars($mahasiswa['jenis_kelamin'] ?? '-') ?></p>
                            <p><span class="info-label">Dosen Pembimbing:</span> <?= htmlspecialchars($mahasiswa['dosen_pembimbing'] ?? '-') ?></p>
                            <p><span class="info-label">Status Mahasiswa:</span> <?= htmlspecialchars($mahasiswa['status_mahasiswa'] ?? '-') ?></p>
                            <p><span class="info-label">Tanggal Daftar:</span> <?= htmlspecialchars($mahasiswa['tanggal_daftar'] ?? '-') ?></p>
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
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
</body>
</html>
