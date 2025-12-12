<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$alert = '';
$riset_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data riset existing (Pastikan milik user yang login)
$q_riset = pg_query_params($koneksi, "SELECT * FROM riset WHERE riset_id = $1 AND creator_id = $2", [$riset_id, $user_id]);
if (!$q_riset || pg_num_rows($q_riset) === 0) {
    echo "<script>alert('Riset tidak ditemukan atau bukan milik Anda!'); window.location='riset.php';</script>";
    exit;
}
$riset = pg_fetch_assoc($q_riset);

// Handle Update
if (isset($_POST['submit'])) {
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_mulai = trim($_POST['tanggal_mulai']);
    $tanggal_selesai = trim($_POST['tanggal_selesai']);

    // Validasi
    if (empty($judul)) {
        $alert = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Judul riset harus diisi.</div>';
    } elseif (!empty($tanggal_mulai) && !empty($tanggal_selesai) && $tanggal_selesai < $tanggal_mulai) {
        $alert = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Tanggal selesai tidak boleh lebih awal dari tanggal mulai.</div>';
    } else {
        $tanggal_mulai_val = !empty($tanggal_mulai) ? $tanggal_mulai : null;
        $tanggal_selesai_val = !empty($tanggal_selesai) ? $tanggal_selesai : null;

        // Update data
        // approved_dosen_by tetap user_id sendiri (karena admin lab = dosen pembimbing diri sendiri)

        $update = pg_query_params($koneksi, "
            UPDATE riset 
            SET judul = $1, deskripsi = $2, tanggal_mulai = $3, tanggal_selesai = $4, approved_dosen_by = $5
            WHERE riset_id = $6 AND creator_id = $7
        ", [$judul, $deskripsi, $tanggal_mulai_val, $tanggal_selesai_val, $user_id, $riset_id, $user_id]);

        if ($update) {
            header('Location: riset.php?msg=updated');
            exit;
        } else {
            $error_msg = pg_last_error($koneksi);
            $alert = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Gagal update riset. ' . htmlspecialchars($error_msg) . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Edit Riset Saya (Admin Lab)</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .char-count {
            float: right;
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'inc/sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                    <span class="h5 mb-0 text-primary font-weight-bold">
                        <i class="fas fa-edit mr-2"></i>Edit Riset Saya
                    </span>
                </nav>

                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Riset</h6>
                                </div>
                                <div class="card-body">
                                    <?= $alert ?>

                                    <form method="POST">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Judul Riset <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="judul" class="form-control" required
                                                maxlength="255" value="<?= htmlspecialchars($riset['judul']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                Deskripsi
                                                <span class="char-count">Max 1000 karakter</span>
                                            </label>
                                            <textarea name="deskripsi" class="form-control" rows="6"
                                                maxlength="1000"><?= htmlspecialchars($riset['deskripsi']) ?></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Tanggal Mulai</label>
                                                    <input type="date" name="tanggal_mulai" class="form-control"
                                                        value="<?= htmlspecialchars($riset['tanggal_mulai'] ?? '') ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Tanggal Selesai</label>
                                                    <input type="date" name="tanggal_selesai" class="form-control"
                                                        value="<?= htmlspecialchars($riset['tanggal_selesai'] ?? '') ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="d-flex justify-content-between">
                                            <a href="riset.php" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                                            </a>
                                            <button type="submit" name="submit" class="btn btn-primary">
                                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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