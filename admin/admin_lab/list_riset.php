<?php
session_start();
include '../inc/koneksi.php';

// Cek role - dosen, ketua, atau admin
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'ketua', 'dosen'])) {
    header('Location: ../index.php');
    exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username']; // NIP untuk dosen
$alert = '';

// Handle messages dari redirect
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'approved_dosen':
            $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> Riset berhasil di-approve! Diteruskan ke Ketua Lab untuk persetujuan final.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
            break;
        case 'approved_ketua':
            $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> Riset berhasil disetujui secara FINAL! Mahasiswa dapat melanjutkan penelitian.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
            break;
        case 'rejected':
            $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle"></i> Riset telah ditolak dan dihapus dari sistem.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
            break;
        case 'not_found':
            $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Riset tidak ditemukan.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
            break;
    }
}

// Query berdasarkan role
if ($role === 'admin') {
    // Admin bisa lihat semua riset
    $q = pg_query($koneksi, "
        SELECT r.riset_id, r.judul, r.deskripsi, r.status, 
               r.tanggal_mulai, r.tanggal_selesai, r.created_at,
               m.nim, m.nama AS mahasiswa_nama, m.email,
               d.nama AS dosen_nama, d.nip
        FROM riset r
        LEFT JOIN mahasiswa m ON r.creator_id = m.user_id
        LEFT JOIN dosen d ON m.dosen_pembimbing = d.nip
        ORDER BY 
            CASE 
                WHEN r.status = 'pending' THEN 1
                WHEN r.status = 'approve_dosen_pembimbing' THEN 2
                WHEN r.status = 'approve_ketua_lab' THEN 3
                ELSE 4
            END,
            r.created_at DESC
    ");
} elseif ($role === 'dosen') {
    // Dosen lihat riset mahasiswa bimbingannya
    $q = pg_query_params($koneksi, "
        SELECT r.riset_id, r.judul, r.deskripsi, r.status, 
               r.tanggal_mulai, r.tanggal_selesai, r.created_at,
               m.nim, m.nama AS mahasiswa_nama, m.email
        FROM riset r
        INNER JOIN mahasiswa m ON r.creator_id = m.user_id
        WHERE m.dosen_pembimbing = $1
        ORDER BY 
            CASE 
                WHEN r.status = 'pending' THEN 1
                WHEN r.status = 'approve_dosen_pembimbing' THEN 2
                WHEN r.status = 'approve_ketua_lab' THEN 3
                ELSE 4
            END,
            r.created_at DESC
    ", [$username]);
} else { // ketua
    // Ketua lihat semua riset
    $q = pg_query($koneksi, "
        SELECT r.riset_id, r.judul, r.deskripsi, r.status, 
               r.tanggal_mulai, r.tanggal_selesai, r.created_at,
               m.nim, m.nama AS mahasiswa_nama, m.email,
               d.nama AS dosen_nama, d.nip
        FROM riset r
        LEFT JOIN mahasiswa m ON r.creator_id = m.user_id
        LEFT JOIN dosen d ON m.dosen_pembimbing = d.nip
        ORDER BY 
            CASE 
                WHEN r.status = 'approve_dosen_pembimbing' THEN 1
                WHEN r.status = 'pending' THEN 2
                WHEN r.status = 'approve_ketua_lab' THEN 3
                ELSE 4
            END,
            r.created_at DESC
    ");
}

$riset_list = $q ? pg_fetch_all($q) : [];
if ($riset_list === false) $riset_list = [];

// Fungsi status badge
function getStatusBadge($status) {
    switch($status) {
        case 'pending': 
            return '<span class="badge badge-warning"><i class="fas fa-clock"></i> Pending Dosen</span>';
        case 'approve_dosen_pembimbing': 
            return '<span class="badge badge-info"><i class="fas fa-check"></i> Approved Dosen</span>';
        case 'approve_ketua_lab': 
            return '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Approved Ketua</span>';
        default: 
            return '<span class="badge badge-secondary">'.$status.'</span>';
    }
}

// Filter status
$filter_status = $_GET['status'] ?? 'all';
$filtered_list = $riset_list;
if ($filter_status !== 'all') {
    $filtered_list = array_filter($riset_list, function($r) use ($filter_status) {
        return $r['status'] === $filter_status;
    });
}

// Hitung statistik
$stats = [
    'total' => count($riset_list),
    'pending' => count(array_filter($riset_list, fn($r) => $r['status'] === 'pending')),
    'approve_dosen' => count(array_filter($riset_list, fn($r) => $r['status'] === 'approve_dosen_pembimbing')),
    'approve_ketua' => count(array_filter($riset_list, fn($r) => $r['status'] === 'approve_ketua_lab')),
];

// Hitung yang perlu direview oleh user saat ini
$need_review = 0;
if ($role === 'dosen') {
    $need_review = $stats['pending']; // Dosen review yang pending
} elseif ($role === 'ketua') {
    $need_review = $stats['approve_dosen']; // Ketua review yang sudah approved dosen
} else { // admin
    $need_review = $stats['pending'] + $stats['approve_dosen']; // Admin bisa review semua
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Riset</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .stats-card {
            border-left: 4px solid;
            transition: transform 0.2s;
            cursor: pointer;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        .filter-btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .riset-description {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .table td {
            vertical-align: middle;
        }
        .pending-highlight {
            background-color: #fff3cd !important;
        }
        .approve-dosen-highlight {
            background-color: #d1ecf1 !important;
        }
        .badge-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary font-weight-bold">
                    <i class="fas fa-list-alt mr-2"></i>Daftar Riset
                    <?php if ($need_review > 0): ?>
                        <span class="badge badge-warning badge-pulse ml-2">
                            <?= $need_review ?> Perlu Review
                        </span>
                    <?php endif; ?>
                </span>
            </nav>

            <div class="container-fluid">
                
                <?= $alert ?>
                
                <!-- Info Box -->
                <?php if ($need_review > 0): ?>
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <strong>Perhatian!</strong> Ada <strong><?= $need_review ?></strong> riset yang menunggu review Anda
                    <?php if ($role === 'dosen'): ?>
                        sebagai Dosen Pembimbing.
                    <?php elseif ($role === 'ketua'): ?>
                        sebagai Ketua Lab.
                    <?php else: ?>
                        .
                    <?php endif; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <?php endif; ?>

                <!-- Statistik Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <a href="?status=all" class="text-decoration-none">
                            <div class="card stats-card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Riset
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= $stats['total'] ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-flask fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <a href="?status=pending" class="text-decoration-none">
                            <div class="card stats-card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Pending Dosen
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= $stats['pending'] ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <a href="?status=approve_dosen_pembimbing" class="text-decoration-none">
                            <div class="card stats-card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Approved Dosen
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= $stats['approve_dosen'] ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <a href="?status=approve_ketua_lab" class="text-decoration-none">
                            <div class="card stats-card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Approved Final
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= $stats['approve_ketua'] ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-table mr-2"></i>Daftar Riset Mahasiswa
                        </h6>
                    </div>
                    <div class="card-body">
                        
                        <!-- Filter Buttons -->
                        <div class="mb-3">
                            <label class="font-weight-bold mb-2">Filter Status:</label><br>
                            <a href="?status=pending" class="btn btn-sm btn-outline-warning filter-btn <?= $filter_status === 'pending' ? 'active' : '' ?>">
                                <i class="fas fa-clock"></i> Pending Dosen (<?= $stats['pending'] ?>)
                            </a>
                            <a href="?status=approve_dosen_pembimbing" class="btn btn-sm btn-outline-info filter-btn <?= $filter_status === 'approve_dosen_pembimbing' ? 'active' : '' ?>">
                                <i class="fas fa-check"></i> Approved Dosen (<?= $stats['approve_dosen'] ?>)
                            </a>
                            <a href="?status=approve_ketua_lab" class="btn btn-sm btn-outline-success filter-btn <?= $filter_status === 'approve_ketua_lab' ? 'active' : '' ?>">
                                <i class="fas fa-check-circle"></i> Approved Final (<?= $stats['approve_ketua'] ?>)
                            </a>
                            <a href="?status=all" class="btn btn-sm btn-outline-primary filter-btn <?= $filter_status === 'all' ? 'active' : '' ?>">
                                <i class="fas fa-list"></i> Semua (<?= $stats['total'] ?>)
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="risetTable" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th width="3%">No</th>
                                        <th width="15%">Mahasiswa</th>
                                        <th width="20%">Judul</th>
                                        <th width="20%">Deskripsi</th>
                                        <?php if ($role !== 'dosen'): ?>
                                        <th width="10%">Pembimbing</th>
                                        <?php endif; ?>
                                        <th width="10%">Status</th>
                                        <th width="10%">Tanggal</th>
                                        <th width="12%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($filtered_list)): ?>
                                    <tr>
                                        <td colspan="<?= $role === 'dosen' ? '7' : '8' ?>" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block text-gray-300"></i>
                                            Belum ada riset<?= $filter_status !== 'all' ? ' dengan status tersebut' : '' ?>.
                                        </td>
                                    </tr>
                                <?php else: 
                                    $no = 1;
                                    foreach ($filtered_list as $r): 
                                        $rowClass = '';
                                        if ($role === 'dosen' && $r['status'] === 'pending') {
                                            $rowClass = 'pending-highlight';
                                        } elseif ($role === 'ketua' && $r['status'] === 'approve_dosen_pembimbing') {
                                            $rowClass = 'approve-dosen-highlight';
                                        } elseif ($role === 'admin') {
                                            if ($r['status'] === 'pending') {
                                                $rowClass = 'pending-highlight';
                                            } elseif ($r['status'] === 'approve_dosen_pembimbing') {
                                                $rowClass = 'approve-dosen-highlight';
                                            }
                                        }
                                        
                                        $canReview = false;
                                        if ($role === 'dosen' && $r['status'] === 'pending') {
                                            $canReview = true;
                                        } elseif ($role === 'ketua' && $r['status'] === 'approve_dosen_pembimbing') {
                                            $canReview = true;
                                        } elseif ($role === 'admin' && in_array($r['status'], ['pending', 'approve_dosen_pembimbing'])) {
                                            $canReview = true;
                                        }
                                ?>
                                    <tr class="<?= $rowClass ?>">
                                        <td class="text-center"><?= $no++ ?></td>
                                        
                                        <td>
                                            <strong><?= htmlspecialchars($r['mahasiswa_nama']) ?></strong><br>
                                            <small class="text-muted">NIM: <?= htmlspecialchars($r['nim']) ?></small>
                                        </td>
                                        
                                        <td>
                                            <strong><?= htmlspecialchars($r['judul']) ?></strong><br>
                                            <small class="text-muted">
                                                <i class="far fa-calendar"></i> <?= date('d/m/Y', strtotime($r['created_at'])) ?>
                                            </small>
                                        </td>
                                        
                                        <td>
                                            <div class="riset-description" title="<?= htmlspecialchars($r['deskripsi'] ?? '') ?>">
                                                <?= htmlspecialchars($r['deskripsi'] ?? 'Tidak ada deskripsi') ?>
                                            </div>
                                        </td>
                                        
                                        <?php if ($role !== 'dosen'): ?>
                                        <td><?= htmlspecialchars($r['dosen_nama'] ?? '-') ?></td>
                                        <?php endif; ?>
                                        
                                        <td class="text-center">
                                            <?= getStatusBadge($r['status']) ?>
                                        </td>
                                        
                                        <td class="text-center">
                                            <small><?= date('d M Y', strtotime($r['created_at'])) ?></small>
                                        </td>
                                        
                                        <td class="text-center">
                                            <!-- Tombol Detail -->
                                            <button class="btn btn-sm btn-info mb-1" title="Detail" onclick="toggleDetail(<?= $r['riset_id'] ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <!-- Tombol Review -->
                                            <?php if ($canReview): ?>
                                            <a href="edit_riset.php?id=<?= $r['riset_id'] ?>" 
                                               class="btn btn-sm btn-warning mb-1" title="Review">
                                                <i class="fas fa-clipboard-check"></i> Review
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    
                                    <!-- Row Detail (Expanded) -->
                                    <tr id="detail-<?= $r['riset_id'] ?>" style="display: none;" class="bg-light">
                                        <td colspan="<?= $role === 'dosen' ? '7' : '8' ?>">
                                            <div class="p-3">
                                                <h6 class="font-weight-bold text-primary mb-3">
                                                    <i class="fas fa-info-circle"></i> Detail Riset
                                                </h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Judul:</strong><br><?= htmlspecialchars($r['judul']) ?></p>
                                                        <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($r['deskripsi'] ?? 'Tidak ada deskripsi')) ?></p>
                                                        <?php if (!empty($r['tanggal_mulai']) && !empty($r['tanggal_selesai'])): ?>
                                                        <p><strong>Periode:</strong><br>
                                                            <?= date('d/m/Y', strtotime($r['tanggal_mulai'])) ?> - 
                                                            <?= date('d/m/Y', strtotime($r['tanggal_selesai'])) ?>
                                                        </p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Mahasiswa:</strong><br>
                                                            <?= htmlspecialchars($r['mahasiswa_nama']) ?> (<?= htmlspecialchars($r['nim']) ?>)
                                                        </p>
                                                        <?php if ($role !== 'dosen' && !empty($r['dosen_nama'])): ?>
                                                        <p><strong>Dosen Pembimbing:</strong><br>
                                                            <?= htmlspecialchars($r['dosen_nama']) ?>
                                                        </p>
                                                        <?php endif; ?>
                                                        <p><strong>Status:</strong><br><?= getStatusBadge($r['status']) ?></p>
                                                        <p><strong>Tanggal Pengajuan:</strong><br>
                                                            <?= date('d F Y, H:i', strtotime($r['created_at'])) ?> WIB
                                                        </p>
                                                    </div>
                                                </div>
                                                <?php if ($canReview): ?>
                                                <div class="mt-3">
                                                    <a href="edit_riset.php?id=<?= $r['riset_id'] ?>" 
                                                       class="btn btn-warning">
                                                        <i class="fas fa-clipboard-check mr-2"></i>Review Riset Ini
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Informasi Alur -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow mb-4 border-left-info">
                            <div class="card-body">
                                <h6 class="font-weight-bold text-info mb-3">
                                    <i class="fas fa-info-circle mr-2"></i>Alur Persetujuan Riset (2 Level)
                                </h6>
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <i class="fas fa-file-alt fa-3x text-primary mb-2"></i>
                                        <h6 class="font-weight-bold">1. Pengajuan</h6>
                                        <small>Mahasiswa mengajukan riset</small>
                                    </div>
                                    <div class="col-md-3">
                                        <i class="fas fa-user-tie fa-3x text-warning mb-2"></i>
                                        <h6 class="font-weight-bold">2. Dosen Pembimbing</h6>
                                        <small>Review dan approve/reject</small>
                                    </div>
                                    <div class="col-md-3">
                                        <i class="fas fa-user-shield fa-3x text-info mb-2"></i>
                                        <h6 class="font-weight-bold">3. Ketua Lab</h6>
                                        <small>Persetujuan final</small>
                                    </div>
                                    <div class="col-md-3">
                                        <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                                        <h6 class="font-weight-bold">4. Disetujui</h6>
                                        <small>Riset dapat dimulai</small>
                                    </div>
                                </div>
                                <hr class="my-3">
                                <p class="mb-0 text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Riset yang di-reject di level manapun akan <strong class="text-danger">dihapus permanen</strong> dari sistem
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include '../inc/footer.php'; ?>
    </div>
</div>

<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
<script>
function toggleDetail(risetId) {
    const detailRow = document.getElementById('detail-' + risetId);
    // Tutup semua detail lain
    document.querySelectorAll('[id^="detail-"]').forEach(row => {
        if (row.id !== 'detail-' + risetId) {
            row.style.display = 'none';
        }
    });
    // Toggle detail yang dipilih
    if (detailRow.style.display === 'none') {
        detailRow.style.display = 'table-row';
    } else {
        detailRow.style.display = 'none';
    }
}

$(document).ready(function(){
    $('#risetTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[6, "desc"]],
        "pageLength": 25,
        "columnDefs": [
            { "orderable": false, "targets": [-1] }
        ],
        "drawCallback": function() {
            $('[id^="detail-"]').hide();
        }
    });
    
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
</body>
</html>