<?php
session_start();

include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard Visi Misi - Admin</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .truncate { max-width: 520px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .btn-xs { padding: .25rem .5rem; font-size: .75rem; border-radius: .2rem; }
    </style>
</head>
<body id="page-top">

<div id="wrapper">

    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <span class="h5 mb-0 text-primary font-weight-bold">
                    Data Visi & Misi
                </span>
            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <h1 class="h3 mb-4 text-gray-800">Admin - Manajemen Visi & Misi</h1>

                <!-- Ambil data dari database -->
                <?php
                $query = "SELECT * FROM visimisi ORDER BY id ASC";
                $result = pg_query($koneksi, $query);
                ?>

                <!-- Tabel -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Visi & Misi</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="visiTable" width="100%" cellspacing="0">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Isi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    while ($row = pg_fetch_assoc($result)): 
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['nama']); ?></td>
                                        <td class="truncate"><?= ($row['isi']); ?>
                                        </td>
                                        <td class="text-center">

                                            <a href="edit_visimisi.php?id=<?= $row['id']; ?>" 
                                                class="btn btn-primary btn-xs">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->
        </div>

        <?php include '../inc/footer.php'; ?>
    </div>
</div>

<!-- Scripts -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>

<script>
$(document).ready(function() {
    $('#visiTable').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": [2,3] }
        ]
    });
});
</script>

</body>
</html>
