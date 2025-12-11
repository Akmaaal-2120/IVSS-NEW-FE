<?php
session_start();
include '../inc/koneksi.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php'); exit;
}

$error = '';

// ambil daftar dosen untuk pilih pembimbing
$dosen_q = pg_query($koneksi, "SELECT nip, nama FROM dosen ORDER BY nama ASC");
$dosen_list = $dosen_q ? pg_fetch_all($dosen_q) : [];
if ($dosen_list === false) $dosen_list = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim               = trim($_POST['nim'] ?? '');
    $nama              = trim($_POST['nama'] ?? '');
    $jenis_kelamin     = trim($_POST['jenis_kelamin'] ?? '');
    $no_telp           = trim($_POST['no_telp'] ?? '');
    $email             = trim($_POST['email'] ?? '');
    $keperluan         = trim($_POST['keperluan'] ?? '');
    $dosen_pembimbing  = trim($_POST['dosen_pembimbing'] ?? '') ?: null;
    $tanggal_daftar    = trim($_POST['tanggal_daftar'] ?? '') ?: null;

    if ($nim === '' || $nama === '') {
        $error = 'NIM dan Nama wajib diisi.';
    } else {
        // cek duplikat nim
        $check = pg_query_params($koneksi, 'SELECT 1 FROM mahasiswa WHERE nim = $1', [$nim]);
        if ($check && pg_num_rows($check) > 0) {
            $error = 'NIM sudah terdaftar.';
            pg_free_result($check);
        } else {
            // set status_pendaftaran = 'pending', status_mahasiswa = NULL
            $sql = 'INSERT INTO mahasiswa (nim, nama, jenis_kelamin, no_telp, email, keperluan, dosen_pembimbing, status_mahasiswa, status_pendaftaran, tanggal_daftar)
                    VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10)';
            $params = [
                $nim,
                $nama,
                $jenis_kelamin !== '' ? $jenis_kelamin : null,
                $no_telp !== '' ? $no_telp : null,
                $email !== '' ? $email : null,
                $keperluan !== '' ? $keperluan : null,
                $dosen_pembimbing,
                null,
                'pending',
                $tanggal_daftar !== '' ? $tanggal_daftar : null
            ];
            $res = pg_query_params($koneksi, $sql, $params);
            if ($res) {
                if ($res) pg_free_result($res);
                header('Location: mahasiswa.php?msg=created');
                exit;
            } else {
                $error = 'Gagal menyimpan data mahasiswa: ' . htmlspecialchars(pg_last_error($koneksi));
            }
        }
    }
}
?>
<!-- HTML sama seperti versi sebelumnya, tapi hapus field status_mahasiswa & status_pendaftaran -->
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Tambah Mahasiswa</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Tambah Mahasiswa</span>
            </nav>
            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label>NIM</label>
                                <input type="text" name="nim" class="form-control" value="<?= htmlspecialchars($_POST['nim'] ?? '') ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-control">
                                        <option value="">-- pilih --</option>
                                        <option value="L" <?= (($_POST['jenis_kelamin'] ?? '') === 'L') ? 'selected' : '' ?>>L</option>
                                        <option value="P" <?= (($_POST['jenis_kelamin'] ?? '') === 'P') ? 'selected' : '' ?>>P</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>No. Telp</label>
                                    <input type="text" name="no_telp" class="form-control" value="<?= htmlspecialchars($_POST['no_telp'] ?? '') ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Keperluan</label>
                                <textarea name="keperluan" class="form-control" rows="3"><?= htmlspecialchars($_POST['keperluan'] ?? '') ?></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Dosen Pembimbing (opsional)</label>
                                    <select name="dosen_pembimbing" class="form-control">
                                        <option value="">-- pilih pembimbing --</option>
                                        <?php foreach ($dosen_list as $d): ?>
                                            <option value="<?= htmlspecialchars($d['nip']) ?>" <?= (($_POST['dosen_pembimbing'] ?? '') === $d['nip']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($d['nama'] . ' (' . $d['nip'] . ')') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Tanggal Daftar</label>
                                    <input type="date" name="tanggal_daftar" class="form-control" value="<?= htmlspecialchars($_POST['tanggal_daftar'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-success" type="submit"><i class="fas fa-save"></i> Simpan</button>
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
