<?php
session_start();

include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$nip = isset($_GET['nip']) ? trim($_GET['nip']) : '';
if ($nip === '') { header('Location: dosen.php?msg=error'); exit; }

// ambil foto supaya bisa dihapus jika tersimpan sebagai file
$q = pg_query_params($koneksi, 'SELECT foto FROM dosen WHERE nip = $1', array($nip));
if ($q && pg_num_rows($q) === 1) {
    $r = pg_fetch_assoc($q);
    $foto = $r['foto'];
    if ($foto && !preg_match('#^https?://#i', $foto)) {
        $path = '../uploads/dosen/' . $foto;
        if (is_file($path)) @unlink($path);
    }
    pg_free_result($q);
}

// hapus record
$res = pg_query_params($koneksi, 'DELETE FROM dosen WHERE nip = $1', array($nip));
if ($res && pg_affected_rows($res) > 0) {
    pg_free_result($res);
    header('Location: dosen.php?msg=deleted');
    exit;
} else {
    header('Location: dosen.php?msg=error');
    exit;
}
