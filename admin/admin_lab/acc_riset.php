<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$alert = '';

// ACTION: Approve/Reject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['riset_id'])) {
    $riset_id = intval($_POST['riset_id']);

    if ($_POST['action'] === 'approve') {
        // Update status to 'approve_dosen_pembimbing' (waiting for Ketua)
        $update = pg_query_params($koneksi, "
            UPDATE riset 
            SET status = 'approve_dosen_pembimbing'
            WHERE riset_id = $1 AND status = 'pending'
        ", [$riset_id]);

        if ($update && pg_affected_rows($update) > 0) {
            $alert = '<div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> Riset berhasil disetujui (Lanjut ke Ketua Lab).
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle"></i> Gagal menyetujui riset. Status mungkin sudah berubah.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
        }
    } elseif ($_POST['action'] === 'reject') {
        $update = pg_query_params($koneksi, "
            UPDATE riset 
            SET status = 'rejected'
            WHERE riset_id = $1 AND status = 'pending'
        ", [$riset_id]);

        if ($update && pg_affected_rows($update) > 0) {
            $alert = '<div class="alert alert-warning alert-dismissible fade show">
                <i class="fas fa-times-circle"></i> Riset ditolak.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle"></i> Gagal menolak riset.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
        }
    }
}

// Get Pending Research List (Filtered by Dosen Pembimbing)
// Hanya tampilkan riset yang approved_dosen_by-nya adalah User ID yang sedang login
$q = pg_query_params($koneksi, "
    SELECT r.riset_id, r.judul, r.deskripsi, r.tanggal_mulai, r.tanggal_selesai, r.created_at,
           m.nama AS mahasiswa_nama, m.nim
    FROM riset r
    JOIN users u ON r.creator_id = u.user_id
    JOIN mahasiswa m ON u.user_id = m.user_id
    WHERE r.status = 'pending' AND r.approved_dosen_by = $1
    ORDER BY r.created_at ASC
", [$_SESSION['user_id']]);
$list = $q ? pg_fetch_all($q) : [];
if ($list === false)
    $list = [];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Approval Riset - Admin Lab</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'inc/sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                    <span class="h5 mb-0 text-primary font-weight-bold">
                        <i class="fas fa-tasks mr-2"></i>Approval Riset (Admin Lab)
                    </span>
                </nav>

                <div class="container-fluid">
                    <?= $alert ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengajuan Riset Pending</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%"
                                    cellspacing="0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Mahasiswa</th>
                                            <th>Judul Riset</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($list)): ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Tidak ada pengajuan riset
                                                    pending.</td>
                                            </tr>
                                        <?php else:
                                            foreach ($list as $i => $r): ?>
                                                <tr>
                                                    <td class="text-center"><?= $i + 1 ?></td>
                                                    <td>
                                                        <strong><?= htmlspecialchars($r['mahasiswa_nama']) ?></strong><br>
                                                        <small><?= htmlspecialchars($r['nim']) ?></small>
                                                    </td>
                                                    <td><?= htmlspecialchars($r['judul']) ?></td>
                                                    <td class="text-center"><?= date('d/m/Y', strtotime($r['created_at'])) ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                            data-target="#detailModal<?= $r['riset_id'] ?>">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </button>
                                                    </td>
                                                </tr>

                                                <!-- Modal Detail -->
                                                <div class="modal fade" id="detailModal<?= $r['riset_id'] ?>" tabindex="-1"
                                                    role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title font-weight-bold text-primary">Detail
                                                                    Riset</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <p><strong>Mahasiswa:</strong>
                                                                            <?= htmlspecialchars($r['mahasiswa_nama']) ?>
                                                                            (<?= htmlspecialchars($r['nim']) ?>)</p>
                                                                        <p><strong>Judul:</strong>
                                                                            <?= htmlspecialchars($r['judul']) ?></p>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <p><strong>Tanggal Mulai:</strong>
                                                                            <?= htmlspecialchars($r['tanggal_mulai'] ?? '-') ?>
                                                                        </p>
                                                                        <p><strong>Tanggal Selesai:</strong>
                                                                            <?= htmlspecialchars($r['tanggal_selesai'] ?? '-') ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <p><strong>Deskripsi:</strong></p>
                                                                <div class="p-3 bg-light rounded text-justify">
                                                                    <?= nl2br(htmlspecialchars($r['deskripsi'])) ?>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form method="POST">
                                                                    <input type="hidden" name="riset_id"
                                                                        value="<?= $r['riset_id'] ?>">
                                                                    <button type="submit" name="action" value="reject"
                                                                        class="btn btn-danger"
                                                                        onclick="return confirm('Yakin tolak riset ini?')">
                                                                        <i class="fas fa-times"></i> Tolak
                                                                    </button>
                                                                    <button type="submit" name="action" value="approve"
                                                                        class="btn btn-success"
                                                                        onclick="return confirm('Setujui riset ini? Riset akan diteruskan ke Ketua Lab.')">
                                                                        <i class="fas fa-check"></i> Setujui
                                                                    </button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
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
    <script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
</body>

</html>