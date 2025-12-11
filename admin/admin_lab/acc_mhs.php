<?php
session_start();
include '../inc/koneksi.php';

//  role 'admin'
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$dosen_nip = $_SESSION['username'] ?? null;

$alert = '';

// Handle POST: acc atau reject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nim'], $_POST['action']) && $dosen_nip) {
    $nim = trim($_POST['nim']);

    if ($_POST['action'] === 'acc_dosen') {
        // Acc dosen → status_pendaftaran = acc_dosen
        $res = pg_query_params($koneksi, "
            UPDATE mahasiswa
            SET status_pendaftaran = 'acc_dosen'
            WHERE nim = $1 AND dosen_pembimbing = $2 AND status_pendaftaran = 'pending'
        ", [$nim, $dosen_nip]);

        if ($res) {
            $alert = '<div class="alert alert-success">Mahasiswa '.$nim.' berhasil di-acc oleh dosen.</div>';
        } else {
            $alert = '<div class="alert alert-danger">Gagal update status mahasiswa.</div>';
        }
    } elseif ($_POST['action'] === 'reject') {
        // Reject → DELETE mahasiswa (trigger masuk log)
        $res = pg_query_params($koneksi, "
            DELETE FROM mahasiswa
            WHERE nim = $1 AND dosen_pembimbing = $2 AND status_pendaftaran = 'pending'
        ", [$nim, $dosen_nip]);

        if ($res) {
            $alert = '<div class="alert alert-warning">Mahasiswa '.$nim.' ditolak dan dihapus.</div>';
        } else {
            $alert = '<div class="alert alert-danger">Gagal menolak mahasiswa.</div>';
        }
    }
}

// Ambil list mahasiswa pending untuk dosen ini, hanya jika $dosen_nip ada
$list = [];
if ($dosen_nip) {
    $q = pg_query_params($koneksi, "
        SELECT m.nim, m.nama, m.email, m.tanggal_daftar, d.nama AS pembimbing
        FROM mahasiswa m
        LEFT JOIN dosen d ON d.nip = m.dosen_pembimbing
        WHERE m.status_pendaftaran = 'pending' AND m.dosen_pembimbing = $1
        ORDER BY m.tanggal_daftar ASC
    ", [$dosen_nip]);

    $list = $q ? pg_fetch_all($q) : [];
    if ($list === false) $list = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Review Pendaftaran - Dosen Pembimbing</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<style>
    .table thead th { text-align: center; }
</style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Review Pendaftaran Mahasiswa (Pending)</span>
            </nav>
            <div class="container-fluid">
                <?= $alert ?>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?php if (!$dosen_nip): ?>
                            <div class="text-muted">Session NIP dosen belum diset. Tidak bisa menampilkan mahasiswa.</div>
                        <?php elseif (empty($list)): ?>
                            <div class="text-muted">Tidak ada mahasiswa pending untuk Anda.</div>
                        <?php else: ?>
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Pembimbing</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i=1; foreach ($list as $r): ?>
                                <tr>
                                    <td class="text-center"><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($r['nim']) ?></td>
                                    <td><?= htmlspecialchars($r['nama']) ?></td>
                                    <td><?= htmlspecialchars($r['email'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($r['pembimbing'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($r['tanggal_daftar'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <form method="POST" style="display:inline" onsubmit="return confirm('Acc pendaftaran <?= addslashes(htmlspecialchars($r['nama'])) ?>?')">
                                            <input type="hidden" name="nim" value="<?= htmlspecialchars($r['nim']) ?>">
                                            <button name="action" value="acc_dosen" class="btn btn-sm btn-success">Acc</button>
                                        </form>
                                        <form method="POST" style="display:inline" onsubmit="return confirm('Tolak pendaftaran <?= addslashes(htmlspecialchars($r['nama'])) ?>?')">
                                            <input type="hidden" name="nim" value="<?= htmlspecialchars($r['nim']) ?>">
                                            <button name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../inc/footer.php'; ?>
    </div>
</div>
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
</body>
</html>
