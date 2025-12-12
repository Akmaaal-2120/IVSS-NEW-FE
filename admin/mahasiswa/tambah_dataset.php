<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$alert = '';

if (isset($_POST['submit'])) {
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $file_path = trim($_POST['file_path']);
    $visibility = $_POST['visibility'];

    $insert = pg_query_params($koneksi, "
        INSERT INTO dataset (judul, deskripsi, file_path, uploader_by, visibility)
        VALUES ($1, $2, $3, $4, $5)
    ", [$judul, $deskripsi, $file_path, $user_id, $visibility]);

    if ($insert) {
        header('Location: dataset.php?msg=created');
        exit;
    } else {
        $alert = '<div class="alert alert-danger">Gagal menambahkan dataset.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Tambah Dataset</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Tambah Dataset</span>
            </nav>
            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?= $alert ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Judul</label>
                                <input type="text" name="judul" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">File (link)</label>
                                <input type="text" name="file_path" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Visibility</label>
                                <input type="text" class="form-control" value="Privat" readonly>
                                <input type="hidden" name="visibility" value="privat">
                            </div>
                            <button type="submit" name="submit" class="btn btn-success"><i class="fas fa-plus"></i> Tambah</button>
                            <a href="dataset.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../inc/footer.php'; ?>
    </div>
</div>
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
</body>
</html>
