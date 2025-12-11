<?php
session_start();

include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Manajemen Mahasiswa - Admin</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>
    .img-thumb { width:100px; height:auto; border-radius:6px; object-fit:cover; }
    .truncate { max-width:420px; white-space: nowrap; overflow:hidden; text-overflow: ellipsis; }
    .btn-xs { padding:.25rem .5rem; font-size:.75rem; border-radius:.2rem; }
</style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <span class="h5 mb-0 text-primary font-weight-bold">Data Mahasiswa</span>
            </nav>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Manajemen Mahasiswa</h1>
                <div class="mb-3 text-right">
                    <a href="tambah_mahasiswa.php" class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Mahasiswa
                    </a>
                </div>

                <?php
                if (isset($_GET['msg'])) {
                    $m = $_GET['msg'];
                    if ($m === 'created') echo '<div class="alert alert-success">Mahasiswa berhasil ditambahkan.</div>';
                    if ($m === 'updated') echo '<div class="alert alert-success">Mahasiswa berhasil diubah.</div>';
                    if ($m === 'deleted') echo '<div class="alert alert-success">Mahasiswa berhasil dihapus.</div>';
                    if ($m === 'error') echo '<div class="alert alert-danger">Terjadi kesalahan.</div>';
                }

                $sql = '
                    SELECT m.nim, m.nama, m.jenis_kelamin, m.no_telp, m.email, m.keperluan,
                            m.dosen_pembimbing, d.nama AS pembimbing_nama,
                            m.status_mahasiswa, m.status_pendaftaran, m.tanggal_daftar, m.user_id,
                            u.username
                    FROM mahasiswa m
                    LEFT JOIN dosen d ON d.nip = m.dosen_pembimbing
                    LEFT JOIN users u ON u.user_id = m.user_id
                    ORDER BY m.nama ASC
                ';
                $res = pg_query($koneksi, $sql);
                ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daftar Mahasiswa</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-bordered" id="mahasiswaTable" width="100%" cellspacing="0">
                            <thead class="table-primary text-center">
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>JK</th>
                                <th>Email</th>
                                <th>No. Telp</th>
                                <th>Pembimbing</th>
                                <th>Status Mahasiswa</th>
                                <th>User</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            if ($res) :
                                while ($row = pg_fetch_assoc($res)) :
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>

                                <td><?= htmlspecialchars($row['nim']) ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['jenis_kelamin'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['email'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['no_telp'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['pembimbing_nama'] ?? '-') ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['status_mahasiswa'] ?? '-') ?></td>
                                <td>
                                    <?= htmlspecialchars($row['username'] ?? ('user_id:' . ($row['user_id'] ?? '-'))) ?>
                                </td>

                                <td class="text-center">
                                    <a href="edit_mahasiswa.php?nim=<?= urlencode($row['nim']) ?>" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="hapus_mahasiswa.php?nim=<?= urlencode($row['nim']) ?>" class="btn btn-danger btn-xs" onclick="return confirm('Hapus mahasiswa <?= addslashes(htmlspecialchars($row['nama'])) ?> ?');">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                            <tr><td colspan="11" class="text-center text-muted">Tidak ada data</td></tr>
                            <?php endif;
                            if ($res) pg_free_result($res);
                            ?>
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
$(document).ready(function() {
    $('#mahasiswaTable').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [10] } // aksi tidak bisa di-sort
        ]
    });
});
</script>

</body>
</html>
