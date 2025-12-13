<?php
include 'inc/koneksi.php';
$query_visi = "SELECT isi FROM visimisi WHERE nama = 'visi' LIMIT 1";
$query_misi = "SELECT isi FROM visimisi WHERE nama = 'misi' LIMIT 1";
$query_dosen = "SELECT * FROM dosen";
$query_peralatan_lab = "SELECT gambar, nama, isi FROM fasilitas 
                        WHERE nama NOT IN ('Area Mushola', 'AC', 'Whiteboard', 'Locker')";
$query_fasilitas_umum = "SELECT gambar, nama, isi FROM fasilitas WHERE nama = 'Area Mushola' OR nama = 'AC' OR nama = 'Whiteboard' OR nama = 'Locker'";
$query_berita_terbaru = "SELECT * FROM berita ORDER BY berita_id DESC LIMIT 3";
$sql = "SELECT * FROM logo";
$result = pg_query($koneksi, $sql);
$logo_data = pg_fetch_assoc($result);



$result_visi = pg_query($koneksi, $query_visi);
$result_misi = pg_query($koneksi, $query_misi);
$result_dosen = pg_query($koneksi, $query_dosen);
$result_peralatan_lab = pg_query($koneksi, $query_peralatan_lab);
$result_fasilitas_umum = pg_query($koneksi, $query_fasilitas_umum);
$result_berita_terbaru = pg_query($koneksi, $query_berita_terbaru);

$data_visi = pg_fetch_assoc($result_visi);
$data_misi = pg_fetch_assoc($result_misi);
$data_dosen = pg_fetch_assoc($result_dosen);
$data_dosen = pg_fetch_assoc($result_dosen);
$dosen_id_url = $data_dosen['nidn'];

// Query Hero Section
$query_hero = "SELECT * FROM hero_section LIMIT 1";
$result_hero = pg_query($koneksi, $query_hero);
$data_hero = pg_fetch_assoc($result_hero);

// Default hero values (fallback if DB is empty)
$hero_judul = $data_hero['judul'] ?? 'Intelligent Vision & <span style="color: #FFBC3B;">Smart System</span>';
$hero_isi = $data_hero['isi'] ?? 'Pusat penelitian dan pengembangan teknologi Computer Vision, Artificial Intelligence (AI), dan Internet of Things (IoT)';

$isi_visi = $data_visi['isi'] ?? "Visi belum tersedia di database.";
$isi_misi = $data_misi['isi'] ?? "Misi belum tersedia di database.";
$batas_karakter = 300;
?>