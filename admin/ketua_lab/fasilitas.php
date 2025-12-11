<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

// ambil data fasilitas
$res = pg_query($koneksi, 'SELECT id, nama, gambar, isi FROM fasilitas ORDER BY id ASC');
$fasilitas = $res ? pg_fetch_all($res) : [];
if ($fasilitas === false) $fasilitas = [];
if ($res) pg_free_result($res);

// set path gambar sesuai model
foreach ($fasilitas as &$f) {
    if (!empty($f['gambar'])) {
        $f['gambar'] = '../assets/img/' . $f['gambar']; // path relatif
    }
}
unset($f);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Manajemen Fasilitas - Admin</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .img-thumb { width:120px; height:auto; border-radius:6px; object-fit:cover; }
        .truncate { max-width:560px; white-space: nowrap; overflow:hidden; text-overflow: ellipsis; }
        .btn-xs { padding:.25rem .5rem; font-size:.75rem; border-radius:.2rem; }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <span class="h5 mb-0 text-primary font-weight-bold">Data Fasilitas</span>
            </nav>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Admin - Manajemen Fasilitas</h1>

                <div class="mb-3 text-right">
                    <a href="tambah_fasilitas.php" class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Fasilitas
                    </a>
                </div>
                <?php
                if (isset($_GET['msg'])) {
                    $m = $_GET['msg'];
                    if ($m === 'created') echo '<div class="alert alert-success">Data berhasil ditambahkan.</div>';
                    if ($m === 'updated') echo '<div class="alert alert-success">Data berhasil diubah.</div>';
                    if ($m === 'deleted') echo '<div class="alert alert-success">Data berhasil dihapus.</div>';
                    if ($m === 'error') echo '<div class="alert alert-danger">Terjadi kesalahan.</div>';
                }
                ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daftar Fasilitas</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="fasilitasTable" width="100%" cellspacing="0">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Gambar</th>
                                        <th>Isi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; if(!empty($fasilitas)): foreach($fasilitas as $row): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                            <td class="text-center">
                                                <?php if (!empty($row['gambar'])): ?>
                                                    <img src="<?= htmlspecialchars($row['gambar'], ENT_QUOTES) ?>" class="img-thumb" alt="gambar">
                                                <?php else: ?>
                                                    <span class="text-muted small">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="truncate" title="<?= htmlspecialchars($row['isi']) ?>"><?= htmlspecialchars($row['isi']) ?></td>
                                            <td class="text-center">
                                                <a href="edit_fasilitas.php?id=<?=($row['id']) ?>" class="btn btn-primary btn-xs">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="hapus_fasilitas.php?id=<?=($row['id']) ?>" class="btn btn-danger btn-xs"
                                                    onclick="return confirm('Yakin ingin menghapus fasilitas ini?');">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="5" class="text-center text-muted">Tidak ada data</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div> <!-- container -->
        </div> <!-- content -->

        <?php include '../inc/footer.php'; ?>
    </div>
</div>

<!-- scripts -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
<script>
$(document).ready(function(){
    $('#fasilitasTable').DataTable({
        "columnDefs": [{ "orderable": false, "targets": [2,4] }]
    });
});
</script>
</body>
</html>
