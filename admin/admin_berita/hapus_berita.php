<?php
include '../inc/koneksi.php';
session_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: berita.php');
    exit;
}

// Hapus berita
$res = pg_query_params($koneksi, 'DELETE FROM berita WHERE berita_id = $1', [$id]);

header('Location: berita.php?msg=deleted');
exit;
