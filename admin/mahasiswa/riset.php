<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$alert = '';

// Handle POST: hapus riset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['riset_id'])) {
    $riset_id = intval($_POST['riset_id']);

    if ($_POST['action'] === 'delete') {
        // Bisa hapus status apapun
        $del = pg_query_params($koneksi, "
            DELETE FROM riset 
            WHERE riset_id = $1 AND creator_id = $2
        ", [$riset_id, $user_id]);

        if ($del && pg_affected_rows($del) > 0) {
            $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> Riset berhasil dihapus.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
        } else {
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Gagal menghapus riset. Hanya riset dengan status pending yang bisa dihapus.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
        }
    }
}

// Ambil data mahasiswa
$q_mhs = pg_query_params($koneksi, "SELECT nim, dosen_pembimbing FROM mahasiswa WHERE user_id = $1", [$user_id]);
$mhs_data = pg_fetch_assoc($q_mhs);

// Ambil daftar riset mahasiswa
$q = pg_query_params($koneksi, "
    SELECT r.riset_id, r.judul, r.deskripsi, r.status, 
           r.tanggal_mulai, r.tanggal_selesai, r.created_at,
           d.nama AS pembimbing_nama
    FROM riset r
    LEFT JOIN dosen d ON r.approved_dosen_by = d.user_id
    WHERE r.creator_id = $1
    ORDER BY r.created_at DESC
", [$user_id]);

$riset_list = $q ? pg_fetch_all($q) : [];
if ($riset_list === false)
    $riset_list = [];

// Status color mapping
function getStatusBadge($status)
{
    switch ($status) {
        case 'draft':
            return '<span class="badge badge-secondary"><i class="fas fa-edit"></i> Draft</span>';
        case 'pending':
            return '<span class="badge badge-warning"><i class="fas fa-clock"></i> Pend. Admin Lab</span>';
        case 'approve_dosen_pembimbing':
            return '<span class="badge badge-info"><i class="fas fa-user-check"></i> Acc Admin Lab</span>';
        case 'approve_ketua_lab':
            return '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Approved</span>';
        case 'rejected':
            return '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Rejected</span>';
        default:
            return '<span class="badge badge-secondary">' . $status . '</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Riset Saya</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .table td {
            vertical-align: middle;
        }

        .riset-description {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
                        <i class="fas fa-flask mr-2"></i>Riset Saya
                    </span>
                </nav>

                <div class="container-fluid">
                    <?= $alert ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-list mr-2"></i>Daftar Pengajuan Riset
                            </h6>
                            <a href="tambah_riset.php" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Ajukan Riset Baru
                            </a>
                        </div>
                        <div class="card-body">
                            <?php
                            if (isset($_GET['msg'])) {
                                $msg = $_GET['msg'];
                                if ($msg === 'created') {
                                    echo '<div class="alert alert-success alert-dismissible fade show">
                                    <i class="fas fa-check-circle"></i> Riset berhasil diajukan dan menunggu approval dosen.
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>';
                                } elseif ($msg === 'updated') {
                                    echo '<div class="alert alert-success alert-dismissible fade show">
                                    <i class="fas fa-check-circle"></i> Riset berhasil diupdate.
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>';
                                }
                            }
                            ?>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="risetTable" width="100%"
                                    cellspacing="0">
                                    <thead class="thead-light">
                                        <tr class="text-center">
                                            <th width="5%">No</th>
                                            <th width="22%">Judul</th>
                                            <th width="25%">Deskripsi</th>
                                            <th width="12%">Pembimbing</th>
                                            <th width="10%">Status</th>
                                            <th width="12%">Tanggal Pengajuan</th>
                                            <th width="14%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($riset_list)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox fa-3x mb-3 d-block text-gray-300"></i>
                                                    Belum ada pengajuan riset.
                                                </td>
                                            </tr>
                                        <?php else:
                                            foreach ($riset_list as $i => $r): ?>
                                                <tr>
                                                    <td class="text-center"><?= $i + 1 ?></td>
                                                    <td>
                                                        <strong><?= htmlspecialchars($r['judul']) ?></strong>
                                                    </td>
                                                    <td>
                                                        <div class="riset-description"
                                                            title="<?= htmlspecialchars($r['deskripsi'] ?? '') ?>">
                                                            <?= htmlspecialchars($r['deskripsi'] ?? 'Tidak ada deskripsi') ?>
                                                        </div>
                                                    </td>
                                                    <td><?= htmlspecialchars($r['pembimbing_nama'] ?? '-') ?></td>
                                                    <td class="text-center"><?= getStatusBadge($r['status']) ?></td>
                                                    <td class="text-center">
                                                        <small><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></small>
                                                    </td>
                                                    <td class="text-center">
                                                        <!-- Tombol Detail -->
                                                        <button class="btn btn-sm btn-info mb-1" title="Detail"
                                                            onclick="toggleDetail(<?= $r['riset_id'] ?>)">
                                                            <i class="fas fa-eye"></i>
                                                        </button>

                                                        <!-- Tombol Edit -->
                                                        <a href="edit_riset.php?id=<?= $r['riset_id'] ?>"
                                                            class="btn btn-sm btn-primary mb-1" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <!-- Tombol Hapus -->
                                                        <button class="btn btn-sm btn-danger mb-1" title="Hapus"
                                                            onclick="confirmDelete(<?= $r['riset_id'] ?>, '<?= htmlspecialchars($r['judul'], ENT_QUOTES) ?>')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                <!-- Row Detail (Expanded) -->
                                                <tr id="detail-<?= $r['riset_id'] ?>" style="display: none;" class="bg-light">
                                                    <td colspan="7">
                                                        <div class="p-3">
                                                            <h6 class="font-weight-bold text-primary mb-3">
                                                                <i class="fas fa-info-circle"></i> Detail Riset
                                                            </h6>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><strong>Judul:</strong><br><?= htmlspecialchars($r['judul']) ?>
                                                                    </p>
                                                                    <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($r['deskripsi'] ?? 'Tidak ada deskripsi')) ?>
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p><strong>Dosen
                                                                            Pembimbing:</strong><br><?= htmlspecialchars($r['pembimbing_nama'] ?? '-') ?>
                                                                    </p>
                                                                    <p><strong>Status:</strong><br><?= getStatusBadge($r['status']) ?>
                                                                    </p>
                                                                    <p><strong>Tanggal
                                                                            Pengajuan:</strong><br><?= date('d F Y, H:i', strtotime($r['created_at'])) ?>
                                                                        WIB</p>
                                                                    <?php if (!empty($r['tanggal_mulai']) && !empty($r['tanggal_selesai'])): ?>
                                                                        <p><strong>Periode
                                                                                Riset:</strong><br><?= date('d/m/Y', strtotime($r['tanggal_mulai'])) ?>
                                                                            s/d
                                                                            <?= date('d/m/Y', strtotime($r['tanggal_selesai'])) ?>
                                                                        </p>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
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

    <!-- Form Hidden untuk Delete -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="riset_id" id="deleteRisetId">
        <input type="hidden" name="action" value="delete">
    </form>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>
    <script>
        function toggleDetail(risetId) {
            const detailRow = document.getElementById('detail-' + risetId);
            if (detailRow.style.display === 'none') {
                // Tutup semua detail lain
                document.querySelectorAll('[id^="detail-"]').forEach(row => {
                    row.style.display = 'none';
                });
                detailRow.style.display = 'table-row';
            } else {
                detailRow.style.display = 'none';
            }
        }

        function confirmDelete(risetId, judulRiset) {
            if (confirm('Yakin ingin menghapus riset:\n"' + judulRiset + '"?\n\nTindakan ini tidak dapat dibatalkan!')) {
                document.getElementById('deleteRisetId').value = risetId;
                document.getElementById('deleteForm').submit();
            }
        }

        $(document).ready(function () {
            $('#risetTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "order": [[5, "desc"]], // Sort by tanggal pengajuan descending
                "pageLength": 10,
                "columnDefs": [
                    { "orderable": false, "targets": [2, 6] }
                ],
                "drawCallback": function () {
                    // Pastikan expanded rows tetap tersembunyi setelah sorting/filtering
                    $('[id^="detail-"]').hide();
                }
            });

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function () {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>

</html>