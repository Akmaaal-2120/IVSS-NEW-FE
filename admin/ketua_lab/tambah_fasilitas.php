<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $isi  = trim($_POST['isi'] ?? '');

    if ($nama === '') $error = 'Nama fasilitas wajib diisi.';

    $foto_saved = null;
    if (empty($error) && !empty($_FILES['foto_file']['name'])) {
        $up = $_FILES['foto_file'];
        $allowed = ['jpg','jpeg','png','gif','webp'];
        $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));

        if ($up['error'] !== UPLOAD_ERR_OK) {
            $error = 'Gagal upload file.';
        } elseif (!in_array($ext, $allowed)) {
            $error = 'Tipe file tidak diperbolehkan. (jpg,jpeg,png,gif,webp)';
        } else {
            $targetDir = '../assets/img/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            $targetFile = $targetDir . $up['name'];
            if (move_uploaded_file($up['tmp_name'], $targetFile)) {
                $foto_saved = $up['name']; // **nama file asli** saja
            } else {
                $error = 'Gagal menyimpan file.';
            }
        }
    }

    if (empty($error)) {
        $sql = 'INSERT INTO fasilitas (nama, gambar, isi) VALUES ($1, $2, $3)';
        $res = pg_query_params($koneksi, $sql, [
            $nama !== '' ? $nama : null,
            $foto_saved !== null ? $foto_saved : null,
            $isi !== '' ? $isi : null
        ]);

        if ($res) {
            pg_free_result($res);
            $success = 'Data fasilitas berhasil disimpan.';
            $_POST = [];
        } else {
            $error = 'Gagal menyimpan data. Cek konfigurasi database.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Tambah Fasilitas</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<style>
.img-thumb { width:140px; height:auto; border-radius:6px; object-fit:cover; display:block; margin-bottom:8px; }
</style>
</head>
<body id="page-top">
<div id="wrapper">
<?php include 'inc/sidebar.php'; ?>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
        <span class="h5 mb-0 text-primary">Tambah Fasilitas</span>
    </nav>

    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Foto (opsional)</label>
                        <input type="file" name="foto_file" class="form-control-file mb-2" accept="image/*" onchange="previewFile(event, 'preview')">
                        <small class="form-text text-muted">jpg/jpeg/png/gif/webp — max 3MB. Disimpan ke folder <code>assets/img/</code> dengan nama asli.</small>
                        <div id="preview-wrap">
                            <img id="preview" class="img-thumb" style="display:none;" alt="preview">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Isi</label>
                        <textarea name="isi" rows="6" class="form-control"><?= htmlspecialchars($_POST['isi'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-success" type="submit"><i class="fas fa-save"></i> Simpan</button>
                        <a href="fasilitas.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<?php include '../inc/footer.php'; ?>
</div>
</div>

<script>
function previewFile(e, id) {
    const file = e.target.files[0];
    const img = document.getElementById(id);
    if (!file) { img.style.display = 'none'; return; }
    const reader = new FileReader();
    reader.onload = function(ev) {
        img.src = ev.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
}
</script>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
