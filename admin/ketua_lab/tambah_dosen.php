<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $nidn = trim($_POST['nidn'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $pendidikan = trim($_POST['pendidikan'] ?? '');
    $linkedin = trim($_POST['linkedin'] ?? '');
    $google_scholar = trim($_POST['google_scholar'] ?? '');
    $sinta = trim($_POST['sinta'] ?? '');
    $scopus = trim($_POST['scopus'] ?? '');

    if ($nip === '' || $nama === '' || $email === '') {
        $error = 'NIP, Nama, dan Email wajib diisi.';
    }

    $foto_saved = null;
    if ($error === '' && !empty($_FILES['foto_file']['name'])) {
        $up = $_FILES['foto_file'];
        $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];
        if (!in_array($ext, $allowed)) {
            $error = 'Tipe file foto tidak diperbolehkan. (jpg, jpeg, png)';
        } else {
            $targetDir = '../assets/img/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            $filename = basename($up['name']);
            $targetFile = $targetDir . $filename;

            if (move_uploaded_file($up['tmp_name'], $targetFile)) {
                $foto_saved = '' . $filename;
            } else {
                $error = 'Gagal mengunggah foto.';
            }
        }
    }

    if ($error === '') {
        $sql = 'INSERT INTO dosen (nip, nama, nidn, email, jabatan, foto, linkedin, google_scholarolar, sinta, scopus, pendidikan)
                VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11)';
        $params = [
            $nip,
            $nama,
            $nidn !== '' ? $nidn : null,
            $email,
            $jabatan !== '' ? $jabatan : null,
            $foto_saved !== '' ? $foto_saved : null,
            $linkedin !== '' ? $linkedin : null,
            $google_scholar !== '' ? $google_scholar : null,
            $sinta !== '' ? $sinta : null,
            $scopus !== '' ? $scopus : null,
            $pendidikan !== '' ? $pendidikan : null
        ];
        $res = pg_query_params($koneksi, $sql, $params);
        if ($res) {
            pg_free_result($res);
            header('Location: dosen.php?msg=created');
            exit;
        } else {
            $error = 'Gagal menyimpan data (cek NIP).';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Tambah Dosen</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">
<style>.img-thumb { width:100px; height:auto; border-radius:6px; object-fit:cover; margin-bottom:8px; }</style>
</head>
<body id="page-top">
<div id="wrapper">
<?php include 'inc/sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
<div id="content">
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
    <span class="h5 mb-0 text-primary">Tambah Dosen</span>
</nav>
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>NIP</label>
                        <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($_POST['nip'] ?? '') ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>NIDN</label>
                        <input type="text" name="nidn" class="form-control" value="<?= htmlspecialchars($_POST['nidn'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" value="<?= htmlspecialchars($_POST['jabatan'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Foto</label><br>
                    <img id="preview" src="" class="img-thumb" style="display:none;">
                    <input type="file" name="foto_file" class="form-control mt-2" accept=".jpg,.jpeg,.png" onchange="previewFile(event,'preview')">
                    <small class="form-text text-muted">Upload JPG/PNG/JPEG.</small>
                </div>

                <div class="form-group">
                    <label>Pendidikan</label>
                    <textarea name="pendidikan" id="pendidikan" class="form-control"><?=($_POST['pendidikan'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6"><label>LinkedIn</label><input type="text" name="linkedin" class="form-control" value="<?= htmlspecialchars($_POST['linkedin'] ?? '') ?>"></div>
                    <div class="form-group col-md-6"><label>Google Scholar</label><input type="text" name="google_scholar" class="form-control" value="<?= htmlspecialchars($_POST['google_scholar'] ?? '') ?>"></div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6"><label>Sinta</label><input type="text" name="sinta" class="form-control" value="<?= htmlspecialchars($_POST['sinta'] ?? '') ?>"></div>
                    <div class="form-group col-md-6"><label>Scopus</label><input type="text" name="scopus" class="form-control" value="<?= htmlspecialchars($_POST['scopus'] ?? '') ?>"></div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-success" type="submit"><i class="fas fa-save"></i> Simpan</button>
                    <a href="dosen.php" class="btn btn-secondary">Batal</a>
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>
<script>
$(document).ready(function() {
    $('#pendidikan').summernote({
        height: 150,
        placeholder: 'Isi pendidikan dosen...',
        toolbar: [
            ['style', ['bold','italic','underline','clear']],
            ['para', ['ul','ol','paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen','codeview']]
        ]
    });
});

function previewFile(e, id) {
    const file = e.target.files[0];
    const img = document.getElementById(id);
    if (!file) { img.style.display='none'; return; }
    const reader = new FileReader();
    reader.onload = function(ev){ img.src = ev.target.result; img.style.display='block'; }
    reader.readAsDataURL(file);
}
</script>
</body>
</html>
