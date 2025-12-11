<?php
session_start();
include '../inc/koneksi.php';

// Cek session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user + dosen
$query = '
    SELECT u.username, u.role,
           d.nama, d.nip, d.jabatan, d.email, d.foto,
           d.linkedin, d.google_scholar, d.sinta, d.scopus, d.pendidikan
    FROM users u
    LEFT JOIN dosen d ON d.user_id = u.user_id
    WHERE u.user_id = $1
';
$result = pg_query_params($koneksi, $query, [$user_id]);

if ($result && pg_num_rows($result) === 1) {
    $admin = pg_fetch_assoc($result);
} else {
    $admin = [
        'username' => $_SESSION['username'] ?? '',
        'role'     => $_SESSION['role'] ?? '',
        'nama'     => $_SESSION['username'] ?? '',
        'foto'     => null
    ];
}
if ($result) pg_free_result($result);

$profile_img_src = '';

if (!empty($admin['foto'])) {
    $profile_img_src = '../assets/img/' . $admin['foto'];
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Profil Admin - Lab IVSS</title>
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
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <span class="h5 mb-0 text-primary font-weight-bold">Profil Admin</span>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="index.php" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-home mr-1"></i> Dashboard
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Profil Ketua Lab IVSS</h1>

                <div class="row">
                    <!-- Profil Card -->
                    <div class="col-lg-4">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body text-center">

                                <?php if (!empty($profile_img_src)) : ?>
                                    <img src="<?= htmlspecialchars($profile_img_src) ?>" class="profile-img mb-3">
                                <?php else: ?>
                                    <div class="mb-3" style="width:150px; height:150px; border-radius:8px; background:#eee;"></div>
                                <?php endif; ?>

                                <h5 class="card-title mb-0"><?= htmlspecialchars($admin['nama'] ?? '') ?></h5>
                                <p class="text-muted small">Anda login sebagai <?= htmlspecialchars($admin['role'] ?? '') ?>.</p>

                                <a href="ubah_password.php?id=<?= urlencode($user_id) ?>" class="btn btn-secondary btn-sm mt-2">
                                    <i class="fas fa-key mr-1"></i> Ubah Password
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Profil Info -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header"><strong>Profil Akun</strong></div>
                            <div class="card-body">

                                <p><span class="info-label">NIP:</span> <?= htmlspecialchars($admin['nip'] ?? '-') ?></p>
                                <p><span class="info-label">Jabatan:</span> <?= htmlspecialchars($admin['jabatan'] ?? '-') ?></p>
                                <p><span class="info-label">Email:</span> <?= htmlspecialchars($admin['email'] ?? '-') ?></p>

                                <p><span class="info-label">LinkedIn:</span>
                                    <?= !empty($admin['linkedin']) ? '<a target="_blank" href="'.htmlspecialchars($admin['linkedin']).'">Klik di sini</a>' : '-' ?>
                                </p>

                                <p><span class="info-label">Google Scholar:</span>
                                    <?= !empty($admin['google_scholar']) ? '<a target="_blank" href="'.htmlspecialchars($admin['google_scholar']).'">Klik di sini</a>' : '-' ?>
                                </p>

                                <p><span class="info-label">Sinta:</span>
                                    <?= !empty($admin['sinta']) ? '<a target="_blank" href="'.htmlspecialchars($admin['sinta']).'">Klik di sini</a>' : '-' ?>
                                </p>

                                <p><span class="info-label">Scopus:</span>
                                    <?= !empty($admin['scopus']) ? '<a target="_blank" href="'.htmlspecialchars($admin['scopus']).'">Klik di sini</a>' : '-' ?>
                                </p>

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
