<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// Ambil semua riset
$q = pg_query($koneksi, "
    SELECT r.riset_id, r.judul, r.deskripsi, r.status, 
           r.tanggal_mulai, r.tanggal_selesai, r.created_at,
           m.nim, m.nama AS mahasiswa_nama,
           d.nama AS pembimbing_nama
    FROM riset r
    INNER JOIN users u ON r.creator_id = u.user_id
    INNER JOIN mahasiswa m ON u.user_id = m.user_id
    LEFT JOIN dosen d ON r.approved_dosen_by = d.nip
    ORDER BY r.created_at DESC
");

$riset_list = $q ? pg_fetch_all($q) : [];
if ($riset_list === false) $riset_list = [];

function getStatusBadge($status) {
    switch($status) {
        case 'pending': return '<span class="badge badge-warning">Pending</span>';
        case 'approved': return '<span class="badge badge-success">Approved</span>';
        case 'rejected': return '<span class="badge badge-danger">Rejected</span>';
        default: return '<span class="badge badge-secondary">'.$status.'</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Semua Riset</title>
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
                <span class="h5 mb-0 text-primary">Semua Riset Mahasiswa</span>
            </nav>

            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Riset</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="risetTable" width="100%" cellspacing="0">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Mahasiswa</th>
                                        <th>NIM</th>
                                        <th>Pembimbing</th>
                                        <th>Status</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($riset_list)): ?>
                                    <tr><td colspan="8" class="text-center text-muted">Belum ada riset.</td></tr>
                                <?php else: foreach ($riset_list as $i => $r): ?>
                                    <tr>
                                        <td class="text-center"><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($r['judul']) ?></td>
                                        <td><?= htmlspecialchars($r['mahasiswa_nama']) ?></td>
                                        <td><?= htmlspecialchars($r['nim']) ?></td>
                                        <td><?= htmlspecialchars($r['pembimbing_nama'] ?? '-') ?></td>
                                        <td class="text-center"><?= getStatusBadge($r['status']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($r['created_at']) ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal<?= $r['riset_id'] ?>">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Detail -->
                                    <div class="modal fade" id="detailModal<?= $r['riset_id'] ?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><?= htmlspecialchars($r['judul']) ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Mahasiswa:</strong> <?= htmlspecialchars($r['mahasiswa_nama']) ?> (<?= htmlspecialchars($r['nim']) ?>)</p>
                                                    <p><strong>Pembimbing:</strong> <?= htmlspecialchars($r['pembimbing_nama'] ?? '-') ?></p>
                                                    <p><strong>Status:</strong> <?= getStatusBadge($r['status']) ?></p>
                                                    <p><strong>Tanggal Pengajuan:</strong> <?= htmlspecialchars($r['created_at']) ?></p>
                                                    <p><strong>Tanggal Mulai:</strong> <?= htmlspecialchars($r['tanggal_mulai'] ?? '-') ?></p>
                                                    <p><strong>Tanggal Selesai:</strong> <?= htmlspecialchars($r['tanggal_selesai'] ?? '-') ?></p>
                                                    <hr>
                                                    <p><strong>Deskripsi:</strong></p>
                                                    <p><?= nl2br(htmlspecialchars($r['deskripsi'] ?? '-')) ?></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
$(document).ready(function(){
    $('#risetTable').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [7] }
        ]
    });
});
</script>
</body>
</html>