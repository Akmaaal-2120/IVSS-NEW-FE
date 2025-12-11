<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) header('Location: logo.php?msg=error');

$q = pg_query_params($koneksi, 'SELECT id, logo FROM logo WHERE id=$1', [$id]);
if (!$q || pg_num_rows($q) === 0) header('Location: logo.php?msg=error');

$row = pg_fetch_assoc($q);
pg_free_result($q);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $logo_saved = $row['logo']; // default pakai nama file lama

    if (!empty($_FILES['logo_file']['name'])) {
        $file = $_FILES['logo_file'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg','jpeg','png','gif','webp','svg'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = 'Tipe file tidak diperbolehkan.';
            } else {
                $targetDir = '../assets/img/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                $filename = basename($file['name']); // nama file aja
                $targetFile = $targetDir . $filename;

                if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                    // hapus file lama kalau ada
                    if ($logo_saved && is_file($targetDir . $logo_saved)) {
                        @unlink($targetDir . $logo_saved);
                    }
                    $logo_saved = $filename; // SIMPAN CUMA NAMA FILE
                } else {
                    $error = 'Gagal mengunggah file.';
                }
            }
        } else {
            $error = 'Error saat upload file.';
        }
    }

    if ($error === '') {
        $res = pg_query_params($koneksi, 'UPDATE logo SET logo=$1 WHERE id=$2', [$logo_saved, $id]);
        if ($res) {
            header('Location: logo.php?msg=updated'); exit;
        } else {
            $error = 'Gagal menyimpan ke database.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Edit Logo - Admin</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<style>
.img-thumb { width: 160px; height: auto; border-radius:6px; display:block; margin-bottom:8px; }
</style>
</head>
<body id="page-top">
<div id="wrapper">
<?php include 'inc/sidebar.php'; ?>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <span class="h5 mb-0 text-primary">Edit Logo</span>
    </nav>

    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Preview Saat Ini</label><br>
                        <?php if (!empty($row['logo'])): ?>
                            <img src="../assets/img/<?= htmlspecialchars($row['logo']) ?>" alt="logo" class="img-thumb">
                        <?php else: ?>
                            <div class="text-muted small">Belum ada logo</div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Ganti Logo (upload file)</label>
                        <input type="file" name="logo_file" class="form-control-file mb-2" accept="image/*">
                        <small class="form-text text-muted">Format: jpg/jpeg/png/gif/webp/svg. Jika upload, akan menggantikan logo lama.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Simpan</button>
                        <a href="logo.php" class="btn btn-light">Batal</a>
                    </div>
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
</body>
</html>
