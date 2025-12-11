<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$error = '';
$nim = isset($_GET['nim']) ? trim($_GET['nim']) : '';
if ($nim === '') { header('Location: mahasiswa.php'); exit; }

/* ambil data mahasiswa existing */
$q = pg_query_params($koneksi, 'SELECT * FROM mahasiswa WHERE nim = $1', [$nim]);
if (!$q || pg_num_rows($q) === 0) { header('Location: mahasiswa.php?msg=not_found'); exit; }
$row = pg_fetch_assoc($q);
pg_free_result($q);

/* ambil daftar dosen & users */
$dosen_list = pg_fetch_all(pg_query($koneksi, "SELECT nip,nama FROM dosen ORDER BY nama ASC")) ?: [];
$users_list = pg_fetch_all(pg_query($koneksi, "SELECT user_id, username FROM users ORDER BY username ASC")) ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama              = trim($_POST['nama'] ?? '');
    $no_telp           = trim($_POST['no_telp'] ?? '');
    $email             = trim($_POST['email'] ?? '');
    $keperluan         = trim($_POST['keperluan'] ?? '');
    $status_mahasiswa  = trim($_POST['status_mahasiswa'] ?? '') ?: null;

    if ($nama === '') {
        $error = 'Nama wajib diisi.';
    }

    if ($error === '') {
        $sql = 'UPDATE mahasiswa SET nama=$1, no_telp=$2, email=$3, keperluan=$4, status_mahasiswa=$5 WHERE nim=$6';
        $params = [
            $nama,
            $no_telp !== '' ? $no_telp : null,
            $email !== '' ? $email : null,
            $keperluan !== '' ? $keperluan : null,
            $status_mahasiswa,
            $nim
        ];
        $res = pg_query_params($koneksi, $sql, $params);
        if ($res) {
            if ($res) pg_free_result($res);
            header('Location: mahasiswa.php?msg=updated');
            exit;
        } else {
            $error = 'Gagal mengupdate data mahasiswa.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Edit Mahasiswa</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Edit Mahasiswa</span>
            </nav>
            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
                        <form method="POST">
                            <div class="form-group">
                                <label>NIM (tidak bisa diubah)</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row['nim']) ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($_POST['nama'] ?? $row['nama']) ?>" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Jenis Kelamin</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($row['jenis_kelamin'] ?? '-') ?>" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>No. Telp</label>
                                    <input type="text" name="no_telp" class="form-control" value="<?= htmlspecialchars($_POST['no_telp'] ?? $row['no_telp']) ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? $row['email']) ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Keperluan</label>
                                <textarea name="keperluan" class="form-control" rows="3"><?= htmlspecialchars($_POST['keperluan'] ?? $row['keperluan']) ?></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Dosen Pembimbing</label>
                                    <input type="text" class="form-control" 
                                        value="<?= htmlspecialchars(($row['dosen_pembimbing'] && isset($dosen_list)) 
                                            ? array_values(array_filter($dosen_list, fn($d)=>$d['nip']==$row['dosen_pembimbing']))[0]['nama'] . ' (' . $row['dosen_pembimbing'] . ')' 
                                            : '-') ?>" readonly>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Status Mahasiswa</label>
                                    <select name="status_mahasiswa" class="form-control">
                                        <option value="">-- pilih --</option>
                                        <option value="member" <?= (($row['status_mahasiswa'] ?? '')==='member')?'selected':'' ?>>member</option>
                                        <option value="alumni" <?= (($row['status_mahasiswa'] ?? '')==='alumni')?'selected':'' ?>>alumni</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Tanggal Daftar</label>
                                    <input type="date" class="form-control" value="<?= htmlspecialchars($_POST['tanggal_daftar'] ?? $row['tanggal_daftar']) ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>User Account</label>
                                <input type="text" class="form-control" 
                                    value="<?= htmlspecialchars(($row['user_id'] && isset($users_list)) 
                                        ? array_values(array_filter($users_list, fn($u)=>$u['user_id']==$row['user_id']))[0]['username'] 
                                        : '-') ?>" readonly>
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Update</button>
                                <a href="mahasiswa.php" class="btn btn-light">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include '../inc/footer.php'; ?>
    </div>
</div>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
