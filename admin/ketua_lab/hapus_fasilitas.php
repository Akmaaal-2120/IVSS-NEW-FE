<?php
session_start();

include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { header('Location: fasilitas.php?msg=error'); exit; }

// (opsional) ambil nama file lalu unlink jika ada dan bukan URL
$q = pg_query_params($koneksi, 'SELECT gambar FROM fasilitas WHERE id = $1', array($id));
if ($q && pg_num_rows($q) === 1) {
    $r = pg_fetch_assoc($q);
    $g = $r['gambar'];
    if ($g && !preg_match('#^https?://#i', $g)) {
        $path = '../uploads/' . $g;
        if (is_file($path)) @unlink($path);
    }
    pg_free_result($q);
}

// hapus row
$res = pg_query_params($koneksi, 'DELETE FROM fasilitas WHERE id = $1', array($id));
if ($res && pg_affected_rows($res) > 0) {
    pg_free_result($res);
    header('Location: fasilitas.php?msg=deleted');
    exit;
} else {
    header('Location: fasilitas.php?msg=error');
    exit;
}
