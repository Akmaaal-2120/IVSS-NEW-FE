<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// ambil data dosen
$sql = 'SELECT d.nip, d.nama, d.nidn, d.email, d.jabatan, d.foto, d.user_id, d.linkedin, d.google_scholar, d.sinta, d.pendidikan, u.username
        FROM dosen d
        LEFT JOIN users u ON u.user_id = d.user_id
        ORDER BY d.nama ASC';
$res = pg_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Manajemen Dosen - Admin</title>
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
        <span class="h5 mb-0 text-primary font-weight-bold">Data Dosen / Member Lab</span>
    </nav>

    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Manajemen Dosen</h1>
        <div class="mb-3 text-right">
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daftar Dosen</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="dosenTable" width="100%" cellspacing="0">
                    <thead class="table-primary text-center">
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>NIDN</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Pendidikan</th>
                        <th>User</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    if ($res):
                        while ($row = pg_fetch_assoc($res)):
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>

                        <td class="text-center">
                            <?php if (!empty($row['foto']) && is_file('../assets/img/' . $row['foto'])): ?>
                                <img src="../assets/img/<?= htmlspecialchars($row['foto']) ?>" class="img-thumb" alt="foto">
                            <?php else: ?>
                                <span class="text-muted">Tidak ada foto</span>
                            <?php endif; ?>
                        </td>

                        <td><?= htmlspecialchars($row['nip']); ?></td>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= htmlspecialchars($row['nidn']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['jabatan']); ?></td>
                        <td><?= ($row['pendidikan']) ?></td>

                        <td><?= htmlspecialchars($row['username'] ?? ('user_id:' . ($row['user_id'] ?? '-'))); ?></td>
                        </td>
                    </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <tr><td colspan="10" class="text-center text-muted">Tidak ada data</td></tr>
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
    $('#dosenTable').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [1,9] }
        ]
    });
});
</script>

</body>
</html>
