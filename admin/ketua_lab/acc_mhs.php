<?php
session_start();
include '../inc/koneksi.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); exit;
}

$alert = '';

// ACC oleh ketua
if (isset($_POST['action']) && $_POST['action'] === 'acc_ketua' && isset($_POST['nim'])) {
    $nim = trim($_POST['nim']);
    $res = pg_query_params($koneksi,
        "UPDATE mahasiswa
            SET status_pendaftaran = 'acc_ketua', status_mahasiswa = 'member'
            WHERE nim = $1 AND status_pendaftaran = 'acc_dosen'",
        [$nim]
    );
    if ($res) {
        $alert = '<div class="alert alert-success">Mahasiswa '.htmlspecialchars($nim).' : accepted oleh Ketua (member).</div>';
    } else {
        $alert = '<div class="alert alert-danger">Gagal update data mahasiswa.</div>';
    }
}

// REJECT (hapus) oleh ketua — with set_config to pass user id into trigger
if (isset($_POST['action']) && $_POST['action'] === 'reject' && isset($_POST['nim'])) {
    $nim = trim($_POST['nim']);
    $user_id = $_SESSION['user_id'];

    // set app user for trigger (transaction/session-local)
    $set = pg_query_params($koneksi, "SELECT set_config('public.mahasiswa', $1, true)", [$user_id]);

    if ($set !== false) {
        $res = pg_query_params($koneksi, "DELETE FROM mahasiswa WHERE nim = $1", [$nim]);
        if ($res) {
            $alert = '<div class="alert alert-warning">Mahasiswa '.htmlspecialchars($nim).' telah direject dan dihapus.</div>';
        } else {
            $alert = '<div class="alert alert-danger">Gagal menghapus data mahasiswa.</div>';
        }
    } else {
        $alert = '<div class="alert alert-danger">Gagal meng-set user context untuk logging.</div>';
    }
}

// ambil list acc_dosen
$q = pg_query($koneksi, "
    SELECT m.nim, m.nama, m.email, m.tanggal_daftar, d.nama AS pembimbing
    FROM mahasiswa m
    LEFT JOIN dosen d ON d.nip = m.dosen_pembimbing
    WHERE m.status_pendaftaran = 'acc_dosen'
    ORDER BY m.tanggal_daftar ASC
");
$list = $q ? pg_fetch_all($q) : [];
if ($list === false) $list = [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Review by Ketua - Admin</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Review Pendaftaran (Siap ke Ketua)</span>
            </nav>

            <div class="container-fluid">
                <?= $alert ?>

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?php if (empty($list)): ?>
                            <div class="text-muted">Tidak ada pendaftaran yang menunggu persetujuan Ketua.</div>
                        <?php else: ?>
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
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
                                    <td><?= htmlspecialchars($r['pembimbing'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($r['tanggal_daftar'] ?? '-') ?></td>
                                    <td>
                                        <!-- ACC KETUA -->
                                        <form method="POST" style="display:inline">
                                            <input type="hidden" name="nim" value="<?= htmlspecialchars($r['nim']) ?>">
                                            <button name="action" value="acc_ketua" class="btn btn-sm btn-success"
                                                onclick="return confirm('Setujui pendaftaran jadi member untuk <?= addslashes(htmlspecialchars($r['nama'])) ?>?')">
                                                Acc Ketua
                                            </button>
                                        </form>

                                        <!-- REJECT (delete) -->
                                        <form method="POST" style="display:inline">
                                            <input type="hidden" name="nim" value="<?= htmlspecialchars($r['nim']) ?>">
                                            <button name="action" value="reject" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin reject dan hapus <?= addslashes(htmlspecialchars($r['nama'])) ?>?')">
                                                Reject
                                            </button>
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
</body>
</html>
