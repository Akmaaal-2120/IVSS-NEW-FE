<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$nip = isset($_GET['nip']) ? trim($_GET['nip']) : '';
if ($nip === '') {
    header('Location: dosen.php');
    exit;
}

// ambil data existing
$q = pg_query_params($koneksi, 'SELECT * FROM dosen WHERE nip=$1', [$nip]);
if (!$q || pg_num_rows($q) === 0) {
    header('Location: dosen.php');
    exit;
}
$row = pg_fetch_assoc($q);
pg_free_result($q);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $nidn = trim($_POST['nidn'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $pendidikan = trim($_POST['pendidikan'] ?? '');
    $linkedin = trim($_POST['linkedin'] ?? '');
    $google_scholar = trim($_POST['google_scholar'] ?? '');
    $sinta = trim($_POST['sinta'] ?? '');
    $scopus = trim($_POST['scopus'] ?? '');

    if ($nama === '' || $email === '') {
        $error = 'Nama dan Email wajib diisi.';
    }

    // default tetap nama file lama
    $foto_saved = $row['foto'];

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
                $foto_saved = $filename; // tetap nama file asli untuk db
            } else {
                $error = 'Gagal mengunggah foto.';
            }
        }
    }

    if ($error === '') {
        $sql = 'UPDATE dosen SET nama=$1, nidn=$2, email=$3, jabatan=$4, foto=$5, linkedin=$6, google_scholar=$7, sinta=$8, scopus=$9, pendidikan=$10
                WHERE nip=$11';
        $params = [
            $nama,
            $nidn !== '' ? $nidn : null,
            $email,
            $jabatan !== '' ? $jabatan : null,
            $foto_saved !== '' ? $foto_saved : null,
            $linkedin !== '' ? $linkedin : null,
            $google_scholar !== '' ? $google_scholar : null,
            $sinta !== '' ? $sinta : null,
            $scopus !== '' ? $scopus : null,
            $pendidikan !== '' ? $pendidikan : null,
            $nip
        ];

        $res = pg_query_params($koneksi, $sql, $params);
        if ($res) {
            pg_free_result($res);
            $success = 'Data dosen berhasil diperbarui.';
            // refresh data
            $q2 = pg_query_params($koneksi, 'SELECT * FROM dosen WHERE nip=$1', [$nip]);
            $row = pg_fetch_assoc($q2);
            pg_free_result($q2);
        } else {
            $error = 'Gagal mengupdate data.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Edit Dosen</title>
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
    <span class="h5 mb-0 text-primary">Edit Dosen</span>
</nav>
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>NIP</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($row['nip']) ?>" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label>NIDN</label>
                        <input type="text" name="nidn" class="form-control" value="<?= htmlspecialchars($_POST['nidn'] ?? $row['nidn']) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($_POST['nama'] ?? $row['nama']) ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? $row['email']) ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" value="<?= htmlspecialchars($_POST['jabatan'] ?? $row['jabatan']) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Foto</label><br>
                    <?php if (!empty($row['foto'])): ?>
                        <img id="preview" src="../assets/img/<?= htmlspecialchars($row['foto']) ?>" class="img-thumb" alt="foto">
                    <?php else: ?>
                        <img id="preview" src="" class="img-thumb" style="display:none;">
                    <?php endif; ?>
                    <input type="file" name="foto_file" class="form-control mt-2" accept=".jpg,.jpeg,.png" onchange="previewFile(event,'preview')">
                    <small class="form-text text-muted">Kosongi untuk mempertahankan foto lama.</small>
                </div>

                <div class="form-group">
                    <label>Pendidikan</label>
                    <textarea name="pendidikan" id="pendidikan" class="form-control"><?=($_POST['pendidikan'] ?? $row['pendidikan']) ?></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6"><label>LinkedIn</label><input type="text" name="linkedin" class="form-control" value="<?= htmlspecialchars($_POST['linkedin'] ?? $row['linkedin']) ?>"></div>
                    <div class="form-group col-md-6"><label>Google Scholar</label><input type="text" name="google_scholar" class="form-control" value="<?= htmlspecialchars($_POST['google_scholar'] ?? $row['google_scholar']) ?>"></div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6"><label>Sinta</label><input type="text" name="sinta" class="form-control" value="<?= htmlspecialchars($_POST['sinta'] ?? $row['sinta']) ?>"></div>
                    <div class="form-group col-md-6"><label>Scopus</label><input type="text" name="scopus" class="form-control" value="<?= htmlspecialchars($_POST['scopus'] ?? $row['scopus']) ?>"></div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Update</button>
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
