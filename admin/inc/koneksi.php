<?php
$host = "localhost";
$user = "postgres"; // default user PostgreSQL
$pass = "nauraPostgreSQL"; // ganti dengan password PostgreSQL kamu
$db   = "pbl_ivss";
$port = "5432"; // port default PostgreSQL

// Membuat koneksi ke PostgreSQL
$koneksi = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");

// Mengecek koneksi
if (!$koneksi) {
    die("Koneksi ke PostgreSQL gagal: " . pg_last_error());
} else {
    // echo "Koneksi berhasil"; // untuk uji koneksi
}
?>
