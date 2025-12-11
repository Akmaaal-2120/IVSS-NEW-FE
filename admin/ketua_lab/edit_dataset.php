<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$dataset_id = $_GET['id'] ?? null;
if (!$dataset_id) {
    header('Location: dataset.php');
    exit;
}

// Ambil dataset untuk dicek ownership
$q = pg_query_params($koneksi, "SELECT * FROM dataset WHERE dataset_id = $1", [$dataset_id]);
$dataset = pg_fetch_assoc($q);

if (!$dataset || $dataset['uploader_by'] != $user_id) {
    header('Location: dataset.php');
    exit;
}

if (isset($_POST['submit'])) {
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $file_path = trim($_POST['file_path']);
    $visibility = $_POST['visibility'];

    $update = pg_query_params($koneksi, "
        UPDATE dataset
        SET judul = $1, deskripsi = $2, file_path = $3, visibility = $4
        WHERE dataset_id = $5 AND uploader_by = $6
    ", [$judul, $deskripsi, $file_path, $visibility, $dataset_id, $user_id]);

    if ($update) {
        header('Location: dataset.php?msg=updated');
        exit;
    } else {
        $alert = '<div class="alert alert-danger">Gagal update dataset.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Edit Dataset</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Edit Dataset</span>
            </nav>
            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?= $alert ?? '' ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Judul</label>
                                <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($dataset['judul']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($dataset['deskripsi']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">File (link)</label>
                                <input type="text" name="file_path" class="form-control" value="<?= htmlspecialchars($dataset['file_path']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Visibility</label>
                                <select name="visibility" class="form-control">
                                    <option value="publik" <?= $dataset['visibility']=='publik'?'selected':'' ?>>Publik</option>
                                    <option value="privat" <?= $dataset['visibility']=='privat'?'selected':'' ?>>Privat</option>
                                </select>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
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
