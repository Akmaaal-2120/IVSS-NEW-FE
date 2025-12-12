<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$alert = '';
$riset_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data riset existing
$q_riset = pg_query_params($koneksi, "SELECT * FROM riset WHERE riset_id = $1 AND creator_id = $2", [$riset_id, $user_id]);
if (!$q_riset || pg_num_rows($q_riset) === 0) {
    echo "<script>alert('Riset tidak ditemukan atau bukan milik Anda!'); window.location='riset.php';</script>";
    exit;
}
$riset = pg_fetch_assoc($q_riset);

// Ambil data mahasiswa
$q_mhs = pg_query_params($koneksi, "SELECT nim, nama, dosen_pembimbing FROM mahasiswa WHERE user_id = $1", [$user_id]);
$mhs = pg_fetch_assoc($q_mhs);
$dosen_pembimbing_nip = $mhs['dosen_pembimbing'] ?? null;
$nama_mahasiswa = $mhs['nama'] ?? '';

// Ambil nama dosen pembimbing
$nama_dosen = '-';
$dosen_user_id = null;
if ($dosen_pembimbing_nip) {
    $q_dosen = pg_query_params($koneksi, "SELECT nama, user_id FROM dosen WHERE nip = $1", [$dosen_pembimbing_nip]);
    if ($q_dosen && pg_num_rows($q_dosen) > 0) {
        $row_d = pg_fetch_assoc($q_dosen);
        $nama_dosen = $row_d['nama'];
        $dosen_user_id = $row_d['user_id'];
    }
}

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
        // NOTE: Status tidak direset ke pending? 
        // User request "bisa mengedit riset setelah riset di approve final" 
        // Usually edits require re-approval, but user just asked to be able to edit.
        // Assuming we keep the current status OR logic requires otherwise.
        // For safety/standard practice, usually editing resets approval. 
        // BUT user specifically wanted to edit "after approval final", implies keeping it or just fixing typos.
        // I will just update fields WITHOUT changing status.

        $update = pg_query_params($koneksi, "
            UPDATE riset 
            SET judul = $1, deskripsi = $2, tanggal_mulai = $3, tanggal_selesai = $4, approved_dosen_by = $5
            WHERE riset_id = $6 AND creator_id = $7
        ", [$judul, $deskripsi, $tanggal_mulai_val, $tanggal_selesai_val, $dosen_user_id, $riset_id, $user_id]);

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
    <title>Edit Riset</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'inc/sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid mt-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Edit Riset</h6>
                        </div>
                        <div class="card-body">
                            <?= $alert ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Mengedit riset ini. Dosen Pembimbing:
                                <strong><?= htmlspecialchars($nama_dosen) ?></strong>
                            </div>
                            <form method="POST">
                                <div class="form-group">
                                    <label>Judul Riset *</label>
                                    <input type="text" name="judul" class="form-control" required
                                        value="<?= htmlspecialchars($riset['judul']) ?>">
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control"
                                        rows="5"><?= htmlspecialchars($riset['deskripsi']) ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" class="form-control"
                                                value="<?= htmlspecialchars($riset['tanggal_mulai'] ?? '') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai" class="form-control"
                                                value="<?= htmlspecialchars($riset['tanggal_selesai'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="riset.php" class="btn btn-secondary">Kembali</a>
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