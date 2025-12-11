<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

// Ambil semua data view user_info
$q = pg_query($koneksi, "SELECT * FROM user_info");
$users = $q ? pg_fetch_all($q) : [];
if ($users === false) $users = [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar User Member</title>
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
                <span class="h5 mb-0 text-primary">Daftar User & Member</span>
            </nav>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">User & Member</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data View yang terkait antara User & Member</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="usersMemberTable" width="100%" cellspacing="0">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Nama</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada user.</td>
                                    </tr>
                                <?php else: foreach ($users as $i => $u): ?>
                                    <tr>
                                        <td class="text-center"><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($u['username']) ?></td>
                                        <td><?= htmlspecialchars($u['nama'] ?? '-') ?></td>
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

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
<script>
$(document).ready(function(){
    $('#usersMemberTable').DataTable();
});
</script>
</body>
</html>
