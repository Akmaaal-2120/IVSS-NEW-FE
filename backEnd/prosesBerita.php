<?php
include 'inc/koneksi.php';

// Tentukan mode
$mode = isset($_GET['id']) ? 'detail' : 'list';

switch ($mode) {

    /* ===============================
       MODE: LIST BERITA
    =============================== */
    case 'list':

        $search_query = "";
        $where_clause = "";
        $batas_karakter = 500;
        $limit = 7;

        // 1. Logika Pencarian
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $search_query = pg_escape_string($koneksi, $_GET['keyword']);
            $where_clause = " WHERE judul ILIKE '%{$search_query}%' ";
        }

        // 2. Hitung total data
        $sql_count = "SELECT COUNT(*) AS total FROM berita {$where_clause}";
        $result_count = pg_query($koneksi, $sql_count);

        if ($result_count) {
            $row_count = pg_fetch_assoc($result_count);
            $total_items = (int)$row_count['total'];
        } else {
            $total_items = 0;
        }

        // 3. Pagination
        $total_pages = $total_items > 0 ? ceil($total_items / $limit) : 1;

        $current_page = isset($_GET['page']) && is_numeric($_GET['page'])
            ? (int)$_GET['page']
            : 1;

        if ($current_page < 1) {
            $current_page = 1;
        } elseif ($current_page > $total_pages) {
            $current_page = $total_pages;
        }

        $offset = ($current_page - 1) * $limit;

        // 4. Query data
        $sql_data = "SELECT * FROM berita {$where_clause} ORDER BY tanggal DESC LIMIT {$limit} OFFSET {$offset}";
        $result_data = pg_query($koneksi, $sql_data);

        $paginated_items = [];
        if ($result_data) {
            while ($row = pg_fetch_assoc($result_data)) {
                $row['tanggal_formatted'] = date('d M Y', strtotime($row['tanggal']));
                $paginated_items[] = $row;
            }
        }

        $featured = null;
        $other_items = [];

        // 5. Featured logic
        if (!empty($paginated_items)) {
            if ($current_page == 1 && $total_items > 0) {
                $featured = $paginated_items[0];

                $isi_featured_clean = strip_tags($featured['isi']);
                if (strlen($isi_featured_clean) > $batas_karakter) {
                    $potongan_isi = substr($isi_featured_clean, 0, $batas_karakter);
                    $featured['isi_tampilan'] =
                        substr($potongan_isi, 0, strrpos($potongan_isi, ' ')) . '...';
                } else {
                    $featured['isi_tampilan'] = $isi_featured_clean;
                }

                $other_items = array_slice($paginated_items, 1);
            } else {
                $other_items = $paginated_items;
            }
        }

        break;


    /* ===============================
       MODE: DETAIL BERITA
    =============================== */
    case 'detail':

        if (!isset($_GET['id'])) {
            echo "<h3>Berita tidak ditemukan.</h3>";
            exit;
        }
    
        $id = intval($_GET['id']);
    
        /* ===============================
        1. AMBIL DETAIL BERITA
        =============================== */
        $sql = "
            SELECT *,
                TO_CHAR(tanggal, 'DD Month YYYY') AS tanggal_format
            FROM berita 
            WHERE berita_id = $id
        ";
    
        $result = pg_query($koneksi, $sql);
        $berita = pg_fetch_assoc($result);
    
        if (!$berita) {
            echo "<h3>Berita tidak ditemukan.</h3>";
            exit;
        }
    
        /* ===============================
        2. AMBIL BERITA TERBARU (SIDEBAR)
        =============================== */
        $latestBerita = [];
    
        $q = pg_query($koneksi, "
            SELECT berita_id, judul, gambar,
            TO_CHAR(tanggal, 'DD Mon YYYY') AS tanggal_format
            FROM berita
            WHERE berita_id != $id
            ORDER BY tanggal DESC
            LIMIT 5
        ");
    
        while ($row = pg_fetch_assoc($q)) {
            $latestBerita[] = $row;
        }
    
        break;
    
}
?>
