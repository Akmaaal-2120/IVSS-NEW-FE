<?php
include 'inc/koneksi.php';

if (!$koneksi) {
    die("Koneksi database gagal.\n");
}

// 1. Create Table if not exists
$query_create = "
CREATE TABLE IF NOT EXISTS hero_section (
    id SERIAL PRIMARY KEY,
    judul TEXT,
    isi TEXT
);
";

$res_create = pg_query($koneksi, $query_create);
if ($res_create) {
    echo "Tabel hero_section berhasil dibuat atau sudah ada.\n";
} else {
    echo "Gagal membuat tabel: " . pg_last_error($koneksi) . "\n";
}

// 2. Check if data exists
$query_check = "SELECT COUNT(*) FROM hero_section";
$res_check = pg_query($koneksi, $query_check);
$row_check = pg_fetch_row($res_check);
$count = $row_check[0];

if ($count == 0) {
    // 3. Insert default data
    $judul_default = 'Intelligent Vision & <span style="color: #FFBC3B;">Smart System</span>';
    $isi_default = 'Pusat penelitian dan pengembangan teknologi Computer Vision, Artificial Intelligence (AI), dan Internet of Things (IoT)';

    $query_insert = "INSERT INTO hero_section (judul, isi) VALUES ($1, $2)";
    $res_insert = pg_query_params($koneksi, $query_insert, array($judul_default, $isi_default));

    if ($res_insert) {
        echo "Data default berhasil ditambahkan.\n";
    } else {
        echo "Gagal menambahkan data default: " . pg_last_error($koneksi) . "\n";
    }
} else {
    echo "Data hero_section sudah ada, tidak perlu insert default.\n";
}
?>