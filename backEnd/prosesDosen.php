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

        // =========================
        // 1. Ambil NIDN dari URL
        // =========================
        $nidn = isset($_GET['nidn']) ? $_GET['nidn'] : '';
    
        if (empty($nidn)) {
            die("NIDN dosen tidak valid");
        }
    
        // =========================
        // 2. Ambil Data Dosen
        // =========================
        $sql = "SELECT * FROM dosen WHERE nidn = $1";
        $result = pg_query_params($koneksi, $sql, [$nidn]);
    
        if (!$result) {
            die("Query error dosen: " . pg_last_error($koneksi));
        }
    
        $dosen_detail = pg_fetch_assoc($result);
    
        // =========================
        // 3. Ambil Riset Terkini
        // =========================
        $riset_terkini = [];

        if ($dosen_detail) {

            // ambil user_id dosen yang diklik
            $user_id_dosen = $dosen_detail['user_id'];

            $sqlRiset = "
                SELECT
                    r.riset_id,
                    r.judul,
                    r.status,
                    u.role,
                    d.nidn,
                    d.nama AS nama_dosen
                FROM riset r
                JOIN users u ON r.creator_id = u.user_id
                JOIN dosen d ON u.user_id = d.user_id
                WHERE r.status = 'approve_ketua_lab'
                AND r.creator_id = $1
                ORDER BY r.riset_id DESC
                LIMIT 3
            ";

            $resultRiset = pg_query_params($koneksi, $sqlRiset, [$user_id_dosen]);

            if ($resultRiset) {
                while ($row = pg_fetch_assoc($resultRiset)) {
                    $riset_terkini[] = $row;
                }
            }
        }
    
        break;
    

    }
?>
