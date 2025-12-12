<?php
session_start();
include '../inc/koneksi.php';

// Pastikan hanya mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$dataset_id = $_GET['id'] ?? null;
if (!$dataset_id) {
    header('Location: dataset.php');
    exit;
}

// Ambil dataset
$q = pg_query_params($koneksi, "SELECT d.*, u.role AS uploader_role FROM dataset d LEFT JOIN users u ON d.uploader_by = u.user_id WHERE d.dataset_id = $1", [$dataset_id]);
$dataset = pg_fetch_assoc($q);

if (!$dataset) {
    header('Location: dataset.php');
    exit;
}

$alert = '';

if (isset($_POST['submit'])) {
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $file_path = trim($_POST['file_path']);
    $visibility_from_form = $_POST['visibility'];

    // Cek apakah dataset milik mahasiswa sendiri
    $is_owner = ($dataset['uploader_by'] == $user_id);

    if ($is_owner) {
        // Update dataset milik mahasiswa sendiri
        $update = pg_query_params($koneksi, "
            UPDATE dataset
            SET judul = $1, deskripsi = $2, file_path = $3, visibility = $4
            WHERE dataset_id = $5 AND uploader_by = $6
        ", [$judul, $deskripsi, $file_path, $visibility_from_form, $dataset_id, $user_id]);

        if ($update) {
            header('Location: dataset.php?msg=updated');
            exit;
        } else {
            $alert = '<div class="alert alert-danger">Gagal update dataset.</div>';
        }
    } else {
        // Dataset milik ketua (copy)
        if ($dataset['uploader_role'] === 'ketua') {
            $insert = pg_query_params($koneksi, "
                INSERT INTO dataset (judul, deskripsi, file_path, uploader_by, visibility)
                VALUES ($1, $2, $3, $4, $5)
            ", [$judul, $deskripsi, $file_path, $user_id, $visibility_from_form]);

            if ($insert) {
                header('Location: dataset.php?msg=copied');
                exit;
            } else {
                $alert = '<div class="alert alert-danger">Gagal membuat copy dataset.</div>';
            }
        } else {
            // Bukan owner dan bukan dataset ketua
            header('Location: dataset.php');
            exit;
        }
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
                        <?= $alert ?>
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
                                <input type="text" class="form-control"
                                    value="<?= ucfirst(htmlspecialchars($dataset['visibility'])) ?>"
                                    readonly>
                                <input type="hidden" name="visibility" value="<?= htmlspecialchars($dataset['visibility']) ?>">
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?= ($dataset['uploader_by'] == $user_id) ? 'Update' : 'Copy' ?>
                            </button>
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
