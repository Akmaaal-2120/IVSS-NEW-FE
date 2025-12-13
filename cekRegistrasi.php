<?php
require_once 'inc/koneksi.php';

// Ambil logo laboratorium
$sql = "SELECT * FROM logo LIMIT 1";
$result = pg_query($koneksi, $sql);
$logo_data = pg_fetch_assoc($result);

$hasil = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = trim($_POST['nim'] ?? '');

    if ($nim === '') {
        $hasil = "Masukkan NIM terlebih dahulu.";
    } else {
        // Ambil data mahasiswa + data user bila sudah dibuat
        $sql = "SELECT m.*, u.username, u.password AS pass_user
                FROM mahasiswa m
                LEFT JOIN users u ON u.user_id = m.user_id
                WHERE m.nim = $1
                LIMIT 1";
        $res = pg_query_params($koneksi, $sql, [$nim]);

        if ($res && pg_num_rows($res) === 1) {
            $row = pg_fetch_assoc($res);

            $status = strtolower($row['status_pendaftaran'] ?? '');
            $akun_ada = !empty($row['username']);

            // Tentukan output
            if ($status === '' || $status === 'pending') {
                $hasil = "Maaf, persetujuan registrasi Anda masih pending.";
            } elseif ($status === 'acc_ketua') {
                if (!$akun_ada) {
                    $hasil = "Registrasi Anda sudah disetujui oleh dosen pembimbing, namun akun belum dibuat.";
                } else {
                    $hasil  = "Selamat, akun Anda sudah dibuat.<br>";
                    $hasil .= "Username: <strong>" . htmlspecialchars($row['username']) . "</strong><br>";
                    $hasil .= "Password: <strong>" . htmlspecialchars($row['pass_user']) . "</strong><br>";
                    $hasil .= "Silakan login dan segera ganti password.";
                }
            } else {
                $hasil = "Status registrasi Anda: " . htmlspecialchars($row['status_pendaftaran']);
            }

        } else {
            $hasil = "Maaf, Anda belum registrasi.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
   <title>Cek Status Registrasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="img/favicon.ico" rel="icon">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@600;700&family=Open+Sans&display=swap"
    rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">

<link href="lib/animate/animate.min.css" rel="stylesheet">
<link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

<link href="css/bootstrap.min.css" rel="stylesheet">

<link href="css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card shadow-lg border-0 p-4" style="border-radius: 15px;">

                <div class="text-center mb-3">
                    <img src="admin/assets/img/<?php echo $logo_data['logo'] ?>"
                    alt="Logo IVSS" class="img-fluid d-block mx-auto mb-3" style="max-width: 160px;">
                </div>
                
                <div class="text-center mb-4">
                    <h4 class="fw-bold text-dark">Cek Status Registrasi</h4>
                    <p class="text-muted">Masukkan NIM Anda untuk mengetahui status pengajuan.</p>
                </div>

                <form method="post">
                    <div class="mb-4">
                        <label for="nimInput" class="form-label fw-bold">Masukkan NIM:</label>
                        <input type="text" name="nim" id="nimInput" class="form-control form-control-sm shadow-sm" placeholder="Masukkan NIM yang didaftarkan" required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-sm fw-bold text-white shadow" style="background-color: #1A1A37;">
                            Cek Status
                        </button>
                        <a href="index.php" class="btn btn-outline-secondary btn-sm">Kembali ke Beranda</a>
                    </div>
                </form>

                <?php if ($hasil): ?>
                    <hr class="mt-4 mb-3">
                    <?php 
                        // Tentukan variabel untuk status, ikon, dan warna
                        $icon_class = 'lni lni-question-circle'; // Default
                        $alert_class = 'bg-info-subtle text-info border border-info'; // Default: Info
                        
                        if (strpos($hasil, 'disetujui') !== false || strpos($hasil, 'akun Anda sudah dibuat') !== false) {
                            $alert_class = 'bg-success-subtle text-success border border-success';
                            $icon_class = 'lni lni-checkmark-circle';
                        } elseif (strpos($hasil, 'pending') !== false) {
                            $alert_class = 'bg-warning-subtle text-warning border border-warning';
                            $icon_class = 'lni lni-timer'; // Ikon jam untuk pending
                        } elseif (strpos($hasil, 'Maaf, Anda belum registrasi') !== false || strpos($hasil, 'Masukkan NIM') !== false) {
                             $alert_class = 'bg-secondary-subtle text-secondary border border-secondary';
                             $icon_class = 'lni lni-search-alt'; // Ikon pencarian untuk belum registrasi/input
                        } else {
                            // Untuk kasus umum atau kegagalan
                            $alert_class = 'bg-danger-subtle text-danger border border-danger';
                            $icon_class = 'lni lni-close';
                        }
                    ?>
                    
                    <div class="p-3 rounded-3 <?= $alert_class ?>">
                        <div class="d-flex align-items-start">
                            <span class="fs-4 me-3 mt-1">
                                <i class="<?= $icon_class ?>"></i>
                            </span>
                            <p class="mb-0 fw-semibold text-break pt-1"><?= $hasil ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</div>

</body>
</html>