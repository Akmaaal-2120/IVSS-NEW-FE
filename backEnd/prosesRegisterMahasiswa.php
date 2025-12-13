<?php
require_once 'inc/koneksi.php';

// Ambil logo laboratorium
$sql = "SELECT * FROM logo LIMIT 1";
$result = pg_query($koneksi, $sql);
$logo_data = pg_fetch_assoc($result);

// ambil daftar dosen - ambil nip & nama (value = nip)
$sql_dosen = "SELECT nip, nama FROM dosen ORDER BY nama ASC";
$result_dosen = pg_query($koneksi, $sql_dosen);

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $namaLengkap      = trim($_POST['nama']);
    $nim              = trim($_POST['nim']);
    $jenisKelamin     = trim($_POST['jenis_kelamin']);
    $noTelp           = trim($_POST['no_telp']);
    $email            = trim($_POST['email']);
    $keperluan        = trim($_POST['keperluan']);
    $dosenPembimbing  = trim($_POST['dosen_pembimbing']);
    $status_mahasiswa = null; // default
    $status_pendaftaran = 'pending'; // default

    // Validasi sederhana
    if ($namaLengkap && $nim && $jenisKelamin && $noTelp && $email && $keperluan && $dosenPembimbing) {
        $sql_insert = "INSERT INTO mahasiswa
            (nama, nim, jenis_kelamin, no_telp, email, keperluan, dosen_pembimbing, tanggal_daftar, status_pendaftaran)
            VALUES ($1, $2, $3, $4, $5, $6, $7, CURRENT_DATE, $8)";
        $result_insert = pg_query_params($koneksi, $sql_insert, [
            $namaLengkap,
            $nim,
            $jenisKelamin,
            $noTelp,
            $email,
            $keperluan,
            $dosenPembimbing,
            $status_pendaftaran
        ]);

        if ($result_insert) {
            echo "<script>alert('Data berhasil disimpan!'); window.location='index.php';</script>";
            exit;
        } else {
            echo "<script>alert('Terjadi kesalahan saat menyimpan data.');</script>";
        }
    } else {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    }
}
?>
