<?php
include 'inc/koneksi.php';

// Tentukan mode
$mode = isset($_GET['nidn']) ? 'detail' : 'list';

switch ($mode) {

     /* ===============================
       MODE: LIST DOSEN
    =============================== */
    case 'list':
        // Ambil data Member Lab 
        $member_lab_res = pg_query($koneksi, "SELECT * FROM dosen WHERE jabatan!='Kepala Lab' ORDER BY nama ASC");
        $member_lab_items = [];
        while ($row = pg_fetch_assoc($member_lab_res)) {
            $member_lab_items[] = $row;
        }

        // Ambil data Ketua Lab
        $result = pg_query($koneksi, "SELECT * FROM dosen WHERE jabatan='Kepala Lab' LIMIT 1");
        $ketua_lab = pg_fetch_assoc($result);
        
        break;

    /* ===============================
       MODE: DETAIL DOSEN
    =============================== */
    case 'detail':
        // Data detail dosen yang akan ditampilkan di halaman ini
        // AMBIL ID DARI URL
        $nidn = isset($_GET['nidn']) ? $_GET['nidn'] : '';

        if (empty($nidn)) {
            die("NIDN dosen tidak valid");
        }


        $sql = "SELECT * FROM dosen WHERE nidn = $1";
        $result = pg_query_params($koneksi, $sql, [$nidn]);

        if (!$result) {
            die("Query error: " . pg_last_error($koneksi));
        }

        // Ambil data
        $dosen_detail = pg_fetch_assoc($result);
    
        break;

    }
?>

