<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// ambil user + dosen
$q = pg_query_params(
    $koneksi,
    'SELECT u.username, u.role, d.* 
     FROM users u
     LEFT JOIN dosen d ON d.user_id = u.user_id
     WHERE u.user_id = $1',
    [$user_id]
);

if (!$q || pg_num_rows($q) === 0) {
    die("Data tidak ditemukan.");
}

$row = pg_fetch_assoc($q);
pg_free_result($q);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // users
    $username = trim($_POST['username'] ?? '');
    $role     = trim($_POST['role'] ?? '');

    // dosen (termasuk NIP + NIDN)
    $nip_new = trim($_POST['nip'] ?? '');
    $nidn = trim($_POST['nidn'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $pendidikan = trim($_POST['pendidikan'] ?? '');
    $linkedin = trim($_POST['linkedin'] ?? '');
    $google_scholar = trim($_POST['google_scholar'] ?? '');
    $sinta = trim($_POST['sinta'] ?? '');
    $scopus = trim($_POST['scopus'] ?? '');

    if ($username === '' || $nama === '' || $email === '' || $nip_new === '') {
        $error = "Username, NIP, Nama, dan Email wajib diisi.";
    }

    // cek unik NIP (kecuali baris sendiri)
    if ($error === '') {
        $check = pg_query_params($koneksi, 'SELECT 1 FROM dosen WHERE nip=$1 AND user_id<>$2 LIMIT 1', [$nip_new, $user_id]);
        if ($check && pg_num_rows($check) > 0) {
            $error = "NIP sudah terpakai oleh user lain. Pilih NIP lain atau cek data.";
        }
        if ($check) pg_free_result($check);
    }

    // foto default tetap
    $foto_saved = $row['foto'];

    if ($error === '' && !empty($_FILES['foto_file']['name'])) {
        $up = $_FILES['foto_file'];
        $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        if (!in_array($ext, $allowed)) {
            $error = "Format foto harus jpg/jpeg/png.";
        } else {
            $dir = '../assets/img/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $filename = time() . "_" . preg_replace('/[^A-Za-z0-9_\.-]/','_', $up['name']);
            $target = $dir . $filename;

            if (move_uploaded_file($up['tmp_name'], $target)) {
                $foto_saved = $filename;
            } else {
                $error = "Gagal upload foto.";
            }
        }
    }

    if ($error === '') {

        // update users
        $uq = pg_query_params(
            $koneksi,
            "UPDATE users SET username=$1, role=$2 WHERE user_id=$3",
            [$username, $role, $user_id]
        );

        // update dosen (update nip + nidn dsb) berdasarkan user_id
        $dq = pg_query_params(
            $koneksi,
            "UPDATE dosen 
             SET nip=$1, nidn=$2, nama=$3, email=$4, jabatan=$5, foto=$6,
                 pendidikan=$7, linkedin=$8, google_scholar=$9, 
                 sinta=$10, scopus=$11
             WHERE user_id=$12",
            [
                $nip_new, $nidn, $nama, $email, $jabatan, $foto_saved,
                $pendidikan, $linkedin, $google_scholar,
                $sinta, $scopus, $user_id
            ]
        );

        if ($uq && $dq) {
            $success = "Profil berhasil diperbarui.";

            // refresh data
            $fetch = pg_query_params(
                $koneksi,
                'SELECT u.username, u.role, d.* FROM users u LEFT JOIN dosen d ON d.user_id=u.user_id WHERE u.user_id=$1',
                [$user_id]
            );
            $row = pg_fetch_assoc($fetch);
            pg_free_result($fetch);

            // update session supaya UI langsung reflect perubahan username/role
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
        } else {
            $error = "Gagal update profil.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Edit Profil</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">
<style>.img-thumb { width:100px; border-radius:8px; }</style>
</head>
<body id="page-top">

<div id="wrapper">
<?php include 'inc/sidebar.php'; ?>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
    <span class="h5 text-primary mb-0">Edit Profil Ketua</span>
    <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="index.php" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user mr-1"></i> Profil
                        </a>
                    </li>
                </ul>
</nav>

<div class="container-fluid">
<div class="card shadow-sm">
<div class="card-body">

<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">

    <h5 class="text-primary">Akun</h5>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Username</label>
            <input type="text" name="username" class="form-control" 
                    value="<?= htmlspecialchars($row['username'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Role</label>
            <input type="text" class="form-control" value="ketua" readonly>
        </div>
    </div>

    <hr>

    <h5 class="text-primary">Data Dosen</h5>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>NIP</label>
            <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($row['nip'] ?? '') ?>" required>
            <small class="form-text text-muted">Mengubah NIP juga akan mengganti identitas dosen.</small>
        </div>
        <div class="form-group col-md-6">
            <label>NIDN</label>
            <input type="text" name="nidn" class="form-control" value="<?= htmlspecialchars($row['nidn'] ?? '') ?>">
        </div>
    </div>

    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama'] ?? '') ?>" required>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email'] ?? '') ?>" required>
        </div>

        <div class="form-group col-md-6">
            <label>Jabatan</label>
            <input type="text" name="jabatan" class="form-control" value="<?= htmlspecialchars($row['jabatan'] ?? '') ?>">
        </div>
    </div>

    <div class="form-group">
        <label>Foto</label><br>
        <?php if (!empty($row['foto'])): ?>
            <img src="../assets/img/<?= htmlspecialchars($row['foto']) ?>" id="preview" class="img-thumb mb-2">
        <?php else: ?>
            <img id="preview" class="img-thumb mb-2" style="display:none">
        <?php endif; ?>

        <input type="file" name="foto_file" class="form-control" accept=".jpg,.jpeg,.png"
                onchange="previewFile(event,'preview')">
    </div>

    <div class="form-group">
        <label>Pendidikan</label>
        <textarea name="pendidikan" id="pendidikan" class="form-control"><?= htmlspecialchars($row['pendidikan'] ?? '') ?></textarea>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6"><label>LinkedIn</label><input name="linkedin" class="form-control" value="<?= htmlspecialchars($row['linkedin'] ?? '') ?>"></div>
        <div class="form-group col-md-6"><label>Google Scholar</label><input name="google_scholar" class="form-control" value="<?= htmlspecialchars($row['google_scholar'] ?? '') ?>"></div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6"><label>Sinta</label><input name="sinta" class="form-control" value="<?= htmlspecialchars($row['sinta'] ?? '') ?>"></div>
        <div class="form-group col-md-6"><label>Scopus</label><input name="scopus" class="form-control" value="<?= htmlspecialchars($row['scopus'] ?? '') ?>"></div>
    </div>

    <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
    <a href="profil.php" class="btn btn-secondary ml-2">Batal</a>

</form>

</div></div></div>
</div>

<?php include '../inc/footer.php'; ?>
</div></div>

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

function previewFile(e,id){
    let file = e.target.files[0];
    let img = document.getElementById(id);
    if (!file) { img.style.display='none'; return; }
    let r = new FileReader();
    r.onload = x => { img.src=x.target.result; img.style.display='block'; };
    r.readAsDataURL(file);
}
</script>

</body>
</html>
