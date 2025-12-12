<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = (int) ($_SESSION['user_id']);
$role = $_SESSION['role'] ?? '';

// ---------- Handler: set visibility to publik (approve) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'set_public') {
    // only ketua allowed
    if ($role !== 'ketua') {
        header('Location: dataset.php?msg=forbidden'); exit;
    }

    $dataset_id = (int) ($_POST['dataset_id'] ?? 0);
    if ($dataset_id <= 0) {
        header('Location: dataset.php?msg=error'); exit;
    }

    // cek apakah dataset ada, visibility privat, dan uploader adalah mahasiswa
    $q = pg_query_params($koneksi, "
        SELECT d.dataset_id, d.visibility, m.user_id AS mahasiswa_user_id
        FROM dataset d
        LEFT JOIN users u ON d.uploader_by = u.user_id
        LEFT JOIN mahasiswa m ON u.user_id = m.user_id
        WHERE d.dataset_id = $1
    ", [$dataset_id]);

    if (!$q || pg_num_rows($q) === 0) {
        header('Location: dataset.php?msg=error'); exit;
    }

    $row = pg_fetch_assoc($q);

    if ($row['visibility'] !== 'privat') {
        header('Location: dataset.php?msg=already_public'); exit;
    }

    if (empty($row['mahasiswa_user_id'])) {
        // uploader bukan mahasiswa -> forbidden
        header('Location: dataset.php?msg=forbidden_uploader'); exit;
    }

    // update jadi publik
    $u = pg_query_params($koneksi, "UPDATE dataset SET visibility = 'publik' WHERE dataset_id = $1", [$dataset_id]);
    if ($u) {
        header('Location: dataset.php?msg=set_public_ok'); exit;
    } else {
        header('Location: dataset.php?msg=error'); exit;
    }
}

// - Untuk ketua: semua publik OR privat yg diunggah oleh mahasiswa
// - Untuk user lain: publik OR milik user itu sendiri
if ($role === 'ketua') {
    $q = pg_query($koneksi, "
        SELECT
            d.dataset_id,
            d.judul,
            d.deskripsi,
            d.file_path,
            d.uploader_by,
            d.tanggal_upload,
            d.visibility,
            u.username,
            m.nama AS mahasiswa_nama,
            dos.nama AS dosen_nama,
            COALESCE(m.nama, dos.nama, u.username) AS uploader_name,
            CASE 
                WHEN m.user_id IS NOT NULL THEN 'mahasiswa'
                WHEN dos.user_id IS NOT NULL THEN 'dosen'
                ELSE 'user'
            END AS uploader_type
        FROM dataset d
        LEFT JOIN users u ON d.uploader_by = u.user_id
        LEFT JOIN mahasiswa m ON u.user_id = m.user_id
        LEFT JOIN dosen dos ON u.user_id = dos.user_id
        WHERE d.visibility = 'publik'
            OR (d.visibility = 'privat' AND m.user_id IS NOT NULL)
        ORDER BY d.tanggal_upload DESC
    ");
} else {
    $q = pg_query_params($koneksi, "
        SELECT
            d.dataset_id,
            d.judul,
            d.deskripsi,
            d.file_path,
            d.uploader_by,
            d.tanggal_upload,
            d.visibility,
            u.username,
            m.nama AS mahasiswa_nama,
            dos.nama AS dosen_nama,
            COALESCE(m.nama, dos.nama, u.username) AS uploader_name,
            CASE
                WHEN m.user_id IS NOT NULL THEN 'mahasiswa'
                WHEN dos.user_id IS NOT NULL THEN 'dosen'
                ELSE 'user'
            END AS uploader_type
        FROM dataset d
        LEFT JOIN users u ON d.uploader_by = u.user_id
        LEFT JOIN mahasiswa m ON u.user_id = m.user_id
        LEFT JOIN dosen dos ON u.user_id = dos.user_id
        WHERE d.visibility = 'publik' OR d.uploader_by = $1
        ORDER BY d.tanggal_upload DESC
    ", [$user_id]);
}

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
                            } elseif ($msg === 'set_public_ok') {
                                echo '<div class="alert alert-success">Dataset berhasil diset menjadi publik.</div>';
                            } elseif ($msg === 'forbidden') {
                                echo '<div class="alert alert-warning">Aksi tidak diizinkan. Hanya ketua yang boleh melakukan ini.</div>';
                            } elseif ($msg === 'forbidden_uploader') {
                                echo '<div class="alert alert-warning">Aksi tidak diizinkan. Hanya dataset yang diunggah oleh mahasiswa dapat diset publik.</div>';
                            } elseif ($msg === 'already_public') {
                                echo '<div class="alert alert-info">Dataset sudah publik.</div>';
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
                                        <td>
                                            <?= htmlspecialchars($d['uploader_name']) ?>
                                            <small class="text-muted">(<?= htmlspecialchars($d['uploader_type']) ?>)</small>
                                        </td>
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
                                                <?php
                                                // Tombol "Set Publik" hanya muncul untuk ketua, dataset privat, uploader mahasiswa
                                                $canSetPublic = ($role === 'ketua' && $d['visibility'] === 'privat' && $d['uploader_type'] === 'mahasiswa');
                                                if ($canSetPublic): ?>
                                                    <form method="POST" action="dataset.php" style="display:inline-block;" onsubmit="return confirm('Set dataset ini menjadi publik?')">
                                                        <input type="hidden" name="action" value="set_public">
                                                        <input type="hidden" name="dataset_id" value="<?= (int)$d['dataset_id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-globe"></i> Set Publik</button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
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
