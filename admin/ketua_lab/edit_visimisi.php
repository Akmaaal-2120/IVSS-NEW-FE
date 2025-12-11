<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($id === '') {
    header('Location: visimisi.php');
    exit;
}

$q = pg_query_params($koneksi, 'SELECT * FROM visimisi WHERE id = $1', array($id));
if (!$q || pg_num_rows($q) === 0) {
    header('Location: visimisi.php?msg=notfound');
    exit;
}
$row = pg_fetch_assoc($q);
pg_free_result($q);

$alert = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $isi  = trim($_POST['isi'] ?? '');

    if ($nama === '' || $isi === '') {
        $alert = '<div class="alert alert-danger">Nama dan Isi wajib diisi.</div>';
    } else {
        $res = pg_query_params($koneksi,
            'UPDATE visimisi SET nama = $1, isi = $2 WHERE id = $3',
            array($nama, $isi, $id)
        );
        if ($res) {
            header('Location: visimisi.php?msg=updated');
            exit;
        } else {
            $alert = '<div class="alert alert-danger">Gagal mengupdate data. Cek koneksi / constraint.</div>';
        }
    }

    $row['nama'] = $_POST['nama'] ?? $row['nama'];
    $row['isi']  = $_POST['isi'] ?? $row['isi'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Visi & Misi - Admin</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Summernote CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">

    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <span class="h5 mb-0 text-primary font-weight-bold">Edit Visi & Misi</span>
            </nav>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Edit Visi & Misi</h1>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <?= $alert ?>

                        <form method="POST">
                            <div class="form-group">
                                <label>ID (tidak bisa diubah)</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row['id']) ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama'] ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Isi</label>
                                <textarea id="summernote" name="isi" required><?= htmlspecialchars($row['isi'] ?? '') ?></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                                <a href="visimisi.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
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
<script src="../assets/js/sb-admin-2.min.js"></script>

<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>

<script>
$(document).ready(function() {
    $('#summernote').summernote({
        height: 250,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['fontsize', 'color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });
});
</script>

</body>
</html>
