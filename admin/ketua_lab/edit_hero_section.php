<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($id === '') {
    header('Location: hero_section.php');
    exit;
}

$q = pg_query_params($koneksi, 'SELECT * FROM hero_section WHERE id = $1', array($id));
if (!$q || pg_num_rows($q) === 0) {
    header('Location: hero_section.php?msg=notfound');
    exit;
}
$row = pg_fetch_assoc($q);
pg_free_result($q);

$alert = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $isi = trim($_POST['isi'] ?? '');

    if ($judul === '' || $isi === '') {
        $alert = '<div class="alert alert-danger">Judul dan Isi wajib diisi.</div>';
    } else {
        $res = pg_query_params(
            $koneksi,
            'UPDATE hero_section SET judul = $1, isi = $2 WHERE id = $3',
            array($judul, $isi, $id)
        );
        if ($res) {
            header('Location: hero_section.php?msg=updated');
            exit;
        } else {
            $alert = '<div class="alert alert-danger">Gagal mengupdate data. Cek koneksi / constraint.</div>';
        }
    }

    $row['judul'] = $_POST['judul'] ?? $row['judul'];
    $row['isi'] = $_POST['isi'] ?? $row['isi'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Edit Hero Section - Admin</title>
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
                    <span class="h5 mb-0 text-primary font-weight-bold">Edit Hero Section</span>
                </nav>

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Edit Hero Section</h1>

                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <?= $alert ?>

                            <form method="POST">
                                <div class="form-group">
                                    <label>ID (tidak bisa diubah)</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($row['id']) ?>"
                                        disabled>
                                </div>

                                <div class="form-group">
                                    <label>Judul</label>
                                    <!-- Using Summernote for title might be overkill, but let's allow HTML editing if they want specific colors like in the original -->
                                    <textarea id="summernote_judul" name="judul"
                                        required><?= ($row['judul'] ?? '') ?></textarea>
                                    <small class="text-muted">Disarankan menggunakan HTML untuk styling (misal:
                                        warna).</small>
                                </div>

                                <div class="form-group">
                                    <label>Isi</label>
                                    <textarea id="summernote_isi" name="isi"
                                        required><?= ($row['isi'] ?? '') ?></textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                        Update</button>
                                    <a href="hero_section.php" class="btn btn-secondary"><i
                                            class="fas fa-arrow-left"></i> Kembali</a>
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
        $(document).ready(function () {
            $('#summernote_judul').summernote({
                height: 100,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['color']],
                    ['view', ['codeview']]
                ]
            });
            $('#summernote_isi').summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['view', ['codeview']]
                ]
            });
        });
    </script>

</body>

</html>