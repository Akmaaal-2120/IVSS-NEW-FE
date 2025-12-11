<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Ambil semua dataset publik + privat milik sendiri
$q = pg_query_params($koneksi, "
    SELECT 
        d.dataset_id, d.judul, d.deskripsi, d.file_path, d.uploader_by, d.tanggal_upload, d.visibility,
        COALESCE(m.nama, dos.nama, u.username) AS uploader_name
    FROM dataset d
    LEFT JOIN users u ON d.uploader_by = u.user_id
    LEFT JOIN mahasiswa m ON u.user_id = m.user_id
    LEFT JOIN dosen dos ON u.user_id = dos.user_id
    WHERE d.visibility = 'publik' OR d.uploader_by = $1
    ORDER BY d.tanggal_upload DESC
", [$user_id]);

$datasets = $q ? pg_fetch_all($q) : [];
if ($datasets === false) $datasets = [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dataset</title>
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
                <span class="h5 mb-0 text-primary">Data Dataset</span>
            </nav>

            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <!-- Tombol Tambah di atas tabel -->
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Dataset</h6>
                            <a href="tambah_dataset.php" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        </div>

                        <?php
                        // Notifikasi aksi
                        if (isset($_GET['msg'])) {
                            $msg = $_GET['msg'];
                            if ($msg === 'created') {
                                echo '<div class="alert alert-success">Dataset berhasil ditambahkan.</div>';
                            } elseif ($msg === 'updated') {
                                echo '<div class="alert alert-success">Dataset berhasil diupdate.</div>';
                            } elseif ($msg === 'deleted') {
                                echo '<div class="alert alert-success">Dataset berhasil dihapus.</div>';
                            } elseif ($msg === 'error') {
                                echo '<div class="alert alert-danger">Terjadi kesalahan.</div>';
                            }
                        }
                        ?>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="datasetTable" width="100%" cellspacing="0">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>File</th>
                                        <th>Uploader</th>
                                        <th>Tanggal Upload</th>
                                        <th>Visibility</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($datasets)): ?>
                                    <tr><td colspan="8" class="text-center text-muted">Belum ada dataset.</td></tr>
                                <?php else: foreach ($datasets as $i => $d): ?>
                                    <tr>
                                        <td class="text-center"><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($d['judul']) ?></td>
                                        <td><?= htmlspecialchars(mb_strimwidth($d['deskripsi'] ?? '', 0, 80, '...')) ?></td>
                                        <td class="text-center">
                                            <?php if (!empty($d['file_path'])): ?>
                                                <a href="<?= htmlspecialchars($d['file_path'], ENT_QUOTES) ?>" target="_blank">Link</a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($d['uploader_name']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($d['tanggal_upload']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($d['visibility']) ?></td>
                                        <td class="text-center">
                                            <?php if ($d['uploader_by'] == $user_id): ?>
                                                <a href="edit_dataset.php?id=<?= $d['dataset_id'] ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="hapus_dataset.php?id=<?= $d['dataset_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus dataset ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!-- /.container -->
        </div><!-- /#content -->

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
    $('#datasetTable').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [2,3,7] }
        ]
    });
});
</script>
</body>
</html>
