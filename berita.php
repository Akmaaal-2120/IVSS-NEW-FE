<?php
// Pastikan file koneksi.php sudah terhubung dengan database PostgreSQL
include 'inc/koneksi.php';

$search_query = "";
$where_clause = "";
$batas_karakter = 500;
// BATAS TELAH DIUBAH:
$limit = 7; // Batas item per halaman (1 Featured + 6 Other Items)

// 1. Logika Pencarian (Mendapatkan kata kunci dari URL)
if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $search_query = pg_escape_string($koneksi, $_GET['keyword']);
    $where_clause = " WHERE judul ILIKE '%{$search_query}%' ";
}

// 2. Hitung total data berita (WAJIB ADA)
$sql_count = "SELECT COUNT(*) AS total FROM berita {$where_clause}";
$result_count = pg_query($koneksi, $sql_count);

if ($result_count) {
    $row_count = pg_fetch_assoc($result_count);
    $total_items = (int)$row_count['total'];
} else {
    $total_items = 0;
}

// 3. Logika Pagination
// total_pages hanya akan > 0 jika total_items > 0
$total_pages = $total_items > 0 ? ceil($total_items / $limit) : 1; 

// Menentukan halaman saat ini
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Validasi halaman saat ini
if ($current_page < 1) {
    $current_page = 1;
} elseif ($current_page > $total_pages) {
    $current_page = $total_pages;
}

$offset = ($current_page - 1) * $limit;

// 4. Query untuk Mengambil Data Halaman Saat Ini (menggunakan LIMIT dan OFFSET)
$sql_data = "SELECT * FROM berita {$where_clause} ORDER BY berita_id DESC LIMIT {$limit} OFFSET {$offset}";

$result_data = pg_query($koneksi, $sql_data);

$paginated_items = [];
if ($result_data) {
    while ($row = pg_fetch_assoc($result_data)) {
        // Memformat tanggal
        $row['tanggal_formatted'] = date('d M Y', strtotime($row['tanggal']));
        $paginated_items[] = $row;
    }
} else {
    // Log error jika query database gagal
    error_log("Database Data Query Error: " . pg_last_error($koneksi));
}

$featured = null;
$other_items = [];

// 5. Logika Item Unggulan (Hanya item pertama pada HALAMAN PERTAMA)
if (!empty($paginated_items)) {
    if ($current_page == 1 && $total_items > 0) {
        $featured = $paginated_items[0]; 
        
        // Bersihkan tag HTML dari isi featured sebelum dipotong
        $isi_featured_clean = strip_tags($featured['isi']); 
        
        if (strlen($isi_featured_clean) > $batas_karakter) {
            $potongan_isi = substr($isi_featured_clean, 0, $batas_karakter);
            // Pastikan pemotongan dilakukan pada kata terakhir yang utuh
            $featured['isi_tampilan'] = substr($potongan_isi, 0, strrpos($potongan_isi, ' ')) . '...';
        } else {
            $featured['isi_tampilan'] = $isi_featured_clean;
        }

        // Item lainnya adalah semua item di halaman ini kecuali yang pertama (featured)
        // Jika limit = 7, maka other_items akan berisi 6 item
        $other_items = array_slice($paginated_items, 1);
    } else {
        // Jika bukan halaman pertama, semua item di halaman ini dianggap 'other_items'
        $other_items = $paginated_items;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laboratorium Visi Cerdas dan Sistem Cerdas</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="img/favicon.ico" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@600;700&family=Open+Sans&display=swap"
        rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>
    <?php include('inc/navbar.php');?>
    <div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center py-4">
            <h1 class="display-3 animated slideInDown">Berita</h1>
            <nav aria-label="breadcrumb animated slideInDown">
            </nav>
        </div>
    </div>
    <div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-0">
                <div class="col-lg-12">
                    <div class="h-100 py-5 d-flex flex-column align-items-center justify-content-center text-center">
                        <h3 class="text-white mb-4">Cari Berita Terbaru dari Lab IVSS</h3>

                        <form action="" method="get" class="w-75">
                            <div class="input-group shadow-sm">
                                <input type="text" class="form-control p-3 border-0" placeholder="Cari disini..."
                                    name="keyword"
                                    value="<?php echo htmlspecialchars(isset($_GET['keyword']) ? $_GET['keyword'] : ''); ?>"
                                    aria-label="Kata kunci pencarian berita">

                                <button class="btn btn-secondary px-4 text-white" type="submit">
                                    <i class="fa fa-search me-2"></i> Cari Berita
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="container-fluid py-5" id="news-content-section">
        <div class="container">
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="section-title bg-white text-center text-primary px-3">Berita</p>
                <h1 class="display-6 mb-4">Berita Terbaru</h1>
            </div>

            <div class="py-5">
                <div class="container">

                    <?php if (!empty($paginated_items)): ?>

                    <?php if ($featured): ?>
                    <div class="mb-5 wow fadeIn" data-wow-delay="0.1s">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="position-relative overflow-hidden" style="height: 400px;">
                                        <img src="admin/assets/img/<?php echo htmlspecialchars($featured['gambar']); ?>"
                                            alt="<?= htmlspecialchars($featured['judul']); ?>"
                                            class="w-100 h-100 object-fit-cover">
                                    </div>
                                </div>
                                <div class="col-lg-6 d-flex align-items-center">
                                    <div class="card-body p-5">
                                        <div class="d-flex flex-wrap gap-3 mb-3 text-muted" style="font-size: 0.85rem;">
                                            <span><i class="lni lni-calendar text-primary me-1"></i>
                                                <?= $featured['tanggal_formatted']; ?></span>
                                            <span><i class="lni lni-pencil text-primary me-1"></i>
                                                penulis: <?= htmlspecialchars($featured['penulis']); ?></span>
                                        </div>
                                        <h2 class="h3 fw-bold mb-3">
                                            <a href="detailBerita.php?id=<?= htmlspecialchars($featured['berita_id']); ?>"
                                                class="text-dark text-decoration-none hover-primary">
                                                <?= htmlspecialchars($featured['judul']); ?>
                                            </a>
                                        </h2>
                                        <p class="text-muted mb-4">
                                            <?= htmlspecialchars($featured['isi_tampilan']); ?>
                                        </p>
                                        <a href="detailBerita.php?id=<?= htmlspecialchars($featured['berita_id']); ?>"
                                            class=" fw-bold text-decoration-none d-inline-flex align-items-center"
                                            style="color: #FFBC3B;">
                                            Baca Selengkapnya <i class="lni lni-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row g-4">
                        <?php 
                            foreach ($other_items as $item): 
                                // Membersihkan tag HTML dari isi item sebelum dipotong (Sudah ada di logika atas, 
                                // tapi diulang di sini untuk memastikan pemotongan terjadi pada versi bersih)
                                $isi_item_clean = strip_tags($item['isi']);
                                
                                $batas_karakter_item = 500;
                                if (strlen($isi_item_clean) > $batas_karakter_item) {
                                    $potongan_isi_item = substr($isi_item_clean, 0, $batas_karakter_item);
                                    $isi_tampilan_item = substr($potongan_isi_item, 0, strrpos($potongan_isi_item, ' ')) . '...';
                                } else {
                                    $isi_tampilan_item = $isi_item_clean;
                                }
                        ?>
                        <div class="col-lg-4 col-md-6 mb-4 wow fadeIn" data-wow-delay="0.3s">
                            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden d-flex flex-column">

                                <div class="position-relative overflow-hidden" style="height: 250px;">
                                    <img src="admin/assets/img/<?php echo htmlspecialchars($item['gambar']); ?>"
                                        alt="<?= htmlspecialchars($item['judul']); ?>"
                                        class="w-100 h-100 object-fit-cover">
                                </div>

                                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                    <div class="d-flex flex-wrap gap-3 mb-2 text-muted" style="font-size: 0.8rem;">
                                        <span><i class="lni lni-calendar text-primary me-1"></i>
                                            <?= $item['tanggal_formatted']; ?></span>
                                        <span><i class="lni lni-pencil text-primary me-1"></i>
                                            penulis: <?= htmlspecialchars($item['penulis']); ?></span>
                                    </div>
                                    <h4 class="h5 fw-bold mb-3">
                                        <a href="detailBerita.php?id=<?= htmlspecialchars($item['berita_id']); ?>"
                                            class="text-dark text-decoration-none hover-primary">
                                            <?= htmlspecialchars($item['judul']); ?>
                                        </a>
                                    </h4>
                                    <p class="text-muted small mb-4 flex-grow-1">
                                        <?= htmlspecialchars($isi_tampilan_item); ?>
                                    </p>

                                    <a href="detailBerita.php?id=<?= htmlspecialchars($item['berita_id']); ?>"
                                        class=" fw-bold text-decoration-none d-inline-flex align-items-center mt-auto"
                                        style="color: #FFBC3B;">
                                        Baca Selengkapnya <i class="lni lni-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($total_pages > 1): ?>
                    <nav aria-label="News page navigation" class="mt-5">
                        <ul class="pagination justify-content-center">

                            <?php 
                                $prev_link = '?page=' . ($current_page - 1);
                                if (!empty($search_query)) {
                                    $prev_link .= '&keyword=' . urlencode($search_query);
                                }
                            ?>
                            <li class="page-item <?= ($current_page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= $prev_link; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <?php for ($i = 1; $i <= $total_pages; $i++): 
                                $page_link = '?page=' . $i;
                                if (!empty($search_query)) {
                                    $page_link .= '&keyword=' . urlencode($search_query);
                                }
                            ?>
                            <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?= $page_link; ?>"
                                    style="<?= ($i == $current_page) ? 'background-color: #FFBC3B; border-color: #FFBC3B; color: white;' : 'color: #1A1A37;'; ?>">
                                    <?= $i; ?>
                                </a>
                            </li>
                            <?php endfor; ?>

                            <?php 
                                $next_link = '?page=' . ($current_page + 1);
                                if (!empty($search_query)) {
                                    $next_link .= '&keyword=' . urlencode($search_query);
                                }
                            ?>
                            <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= $next_link; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                    <?php else: ?>
                    <div class="alert alert-warning text-center wow fadeIn" role="alert" data-wow-delay="0.1s">
                        <h4 class="alert-heading">Hasil Tidak Ditemukan</h4>
                        <?php if (!empty($search_query)): ?>
                        <p>Silakan coba kata kunci lain atau <a href="?" class="alert-link">kembali ke semua berita</a>.
                        </p>
                        <?php else: ?>
                        <p>Saat ini belum ada data berita yang tersedia.</p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </section>

    <?php include('inc/footer.php')?>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>

    <script src="js/main.js"></script>
</body>

</html>