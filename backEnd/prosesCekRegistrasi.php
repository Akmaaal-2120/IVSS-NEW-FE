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

        // Cek apakah mahasiswa pernah ditolak
        $sqlReject = "SELECT 1 FROM mahasiswa_reject_log WHERE nim = $1 LIMIT 1";
        $resReject = pg_query_params($koneksi, $sqlReject, [$nim]);

        if ($resReject && pg_num_rows($resReject) > 0) {
            $hasil = "Maaf, pendaftaran Anda telah <strong>ditolak</strong>. 
                    Silakan hubungi Admin Laboratorium untuk informasi lebih lanjut.";
        } else {
        // Lanjutkan proses cek mahasiswa
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
}
?>
