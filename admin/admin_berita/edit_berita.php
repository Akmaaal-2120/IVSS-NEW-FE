<?php
session_start();

include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'berita') {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: berita.php'); exit; }

// ambil data lama
$sql = 'SELECT * FROM berita WHERE berita_id=$1';
$berita = pg_fetch_assoc(pg_query_params($koneksi, $sql, [$id]));
if (!$berita) { header('Location: berita.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul   = trim($_POST['judul'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    // isi HTML dari Summernote
    $isi     = $_POST['isi'] ?? '';
    $tanggal = trim($_POST['tanggal'] ?? '');
    if ($tanggal === '') $tanggal = null;

    if (!$judul || trim(strip_tags($isi)) === '') $error = 'Judul dan isi wajib diisi.';

    $gambar_path = $berita['gambar'];

    if (empty($error) && !empty($_FILES['gambar_file']['name'])) {
        $file = $_FILES['gambar_file'];
        $targetFile = '../assets/img/' . basename($file['name']);

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $gambar_path = $file['name'];
        } else {
            $error = 'Gagal upload gambar.';
        }
    }

    if (empty($error)) {
        $sql = 'UPDATE berita SET judul=$1, isi=$2, gambar=$3, penulis=$4, tanggal=$5 WHERE berita_id=$6';
        $res = pg_query_params($koneksi, $sql, [
            $judul,
            $isi,
            $gambar_path,
            $penulis ?: null,
            $tanggal ?: null,
            $id
        ]);
        if ($res) {
            header('Location: berita.php?msg=updated');
            exit;
        } else {
            $error = 'Gagal menyimpan berita.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Edit Berita</title>

<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

<!-- Summernote CSS (CDN) -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">

<style>
    .img-thumb{ width:180px; height:auto; border-radius:6px; object-fit:cover; display:block; margin-bottom:8px; }
</style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Edit Berita</span>
            </nav>
            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($_POST['judul'] ?? $berita['judul']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Penulis</label>
                                <input type="text" name="penulis" class="form-control" value="<?= htmlspecialchars($_POST['penulis'] ?? $berita['penulis']) ?>">
                            </div>

                            <div class="form-group">
                                <label>Gambar</label>
                                <input type="file" name="gambar_file" class="form-control-file mb-2" accept="image/*" onchange="previewFile(event,'preview')">
                                <small class="form-text text-muted">jpg/jpeg/png/gif/webp — max 3MB.</small>
                                <?php if(!empty($berita['gambar'])): ?>
                                    <img src="../assets/img/<?= htmlspecialchars($berita['gambar']) ?>" class="img-thumb" alt="gambar">
                                <?php else: ?>
                                    <img id="preview" class="img-thumb" style="display:none;" alt="preview">
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label>Isi</label>
                                <!-- textarea id="isi" akan diinisialisasi Summernote -->
                                <textarea id="isi" name="isi" rows="8" class="form-control"><?= $berita['isi'] ?? '' ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($_POST['tanggal'] ?? $berita['tanggal']) ?>">
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-success" type="submit"><i class="fas fa-save"></i> Simpan</button>
                                <a href="berita.php" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    <?php include '../inc/footer.php'; ?>
    </div>
</div>

<!-- JS: jQuery, Bootstrap bundle (SB Admin already uses these) -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Summernote JS (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>

<script>
function previewFile(e, id) {
    const file = e.target.files[0];
    const img = document.getElementById(id);
    if (!file) { img.style.display='none'; return; }
    const reader = new FileReader();
    reader.onload = function(ev) {
        img.src = ev.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
}

$(document).ready(function() {
    // Inisialisasi Summernote pada textarea #isi
    $('#isi').summernote({
        height: 300,
        placeholder: 'Tulis isi berita di sini...',
        toolbar: [
            ['style', ['bold','italic','underline','clear']],
            ['para', ['ul','ol','paragraph']],
        ]
    });
});
</script>
</body>
</html>
