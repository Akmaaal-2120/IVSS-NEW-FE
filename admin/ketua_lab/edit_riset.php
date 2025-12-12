<?php
session_start();
include '../inc/koneksi.php';

// Cek role - dosen, ketua, atau admin yang bisa akses
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'ketua', 'mahasiswa'])) {
    header('Location: ../index.php');
    exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username']; // NIP untuk dosen, username untuk yang lain
$alert = '';

// Ambil ID riset dari URL
$riset_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($riset_id <= 0) {
    header('Location: list_riset.php');
    exit;
}

// Ambil data riset
$q_riset = pg_query_params($koneksi, "
    SELECT r.riset_id, r.judul, r.deskripsi, r.status, 
           r.tanggal_mulai, r.tanggal_selesai, r.created_at,
           r.creator_id, r.approved_dosen_by,
           m.nim, m.nama AS mahasiswa_nama, m.email, m.dosen_pembimbing,
           d.nama AS dosen_nama, d.nip
    FROM riset r
    LEFT JOIN mahasiswa m ON r.creator_id = m.user_id
    LEFT JOIN dosen d ON r.approved_dosen_by = d.nip
    WHERE r.riset_id = $1
", [$riset_id]);

if (!$q_riset || pg_num_rows($q_riset) === 0) {
    header('Location: list_riset.php?msg=not_found');
    exit;
}

$riset = pg_fetch_assoc($q_riset);

// Cek apakah user berhak mereview riset ini
$can_review = false;
$review_level = '';

if ($role === 'dosen') {
    // Dosen hanya bisa review riset mahasiswa bimbingannya yang status 'pending'
    if ($riset['status'] === 'pending' && $riset['dosen_pembimbing'] === $username) {
        $can_review = true;
        $review_level = 'dosen';
    }
} elseif ($role === 'ketua') {
    // Ketua hanya bisa review riset yang sudah di-approve dosen
    if ($riset['status'] === 'approve_dosen_pembimbing') {
        $can_review = true;
        $review_level = 'ketua';
    }
} elseif ($role === 'admin') {
    // Admin bisa review semua level
    if (in_array($riset['status'], ['pending', 'approve_dosen_pembimbing'])) {
        $can_review = true;
        if ($riset['status'] === 'pending') {
            $review_level = 'dosen'; // Admin bertindak sebagai dosen
        } else {
            $review_level = 'ketua'; // Admin bertindak sebagai ketua
        }
    }
}

// Handle POST untuk approve atau reject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $can_review) {
    $action = $_POST['action'] ?? '';
    $catatan = trim($_POST['catatan'] ?? '');
    
    if ($action === 'approve') {
        $new_status = '';
        $approved_by_field = '';
        
        if ($review_level === 'dosen') {
            // Dosen/Admin approve ke level berikutnya
            $new_status = 'approve_dosen_pembimbing';
            $approved_by_field = 'approved_dosen_by';
            $approved_value = ($role === 'dosen') ? $username : $riset['dosen_pembimbing'];
            
            $update = pg_query_params($koneksi, "
                UPDATE riset
                SET status = $1, approved_dosen_by = $2
                WHERE riset_id = $3 AND status = 'pending'
            ", [$new_status, $approved_value, $riset_id]);
            
            $success_msg = 'approved_dosen';
            
        } elseif ($review_level === 'ketua') {
            // Ketua/Admin approve ke status final
            $new_status = 'approve_ketua_lab';
            
            $update = pg_query_params($koneksi, "
                UPDATE riset
                SET status = $1, approved_ketua_by = $2
                WHERE riset_id = $3 AND status = 'approve_dosen_pembimbing'
            ", [$new_status, $user_id, $riset_id]);
            
            $success_msg = 'approved_ketua';
        }
        
        if ($update && pg_affected_rows($update) > 0) {
            header('Location: list_riset.php?msg=' . $success_msg);
            exit;
        } else {
            $alert = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Gagal approve riset.</div>';
        }
        
    } elseif ($action === 'reject') {
        // Hapus riset jika direject di level manapun
        $delete = pg_query_params($koneksi, "
            DELETE FROM riset
            WHERE riset_id = $1
        ", [$riset_id]);
        
        if ($delete && pg_affected_rows($delete) > 0) {
            header('Location: list_riset.php?msg=rejected');
            exit;
        } else {
            $alert = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Gagal reject riset.</div>';
        }
    }
}

// Fungsi status badge
function getStatusBadge($status) {
    switch($status) {
        case 'pending': 
            return '<span class="badge badge-warning"><i class="fas fa-clock"></i> Pending Dosen</span>';
        case 'approve_dosen_pembimbing': 
            return '<span class="badge badge-info"><i class="fas fa-check"></i> Approved Dosen</span>';
        case 'approve_ketua_lab': 
            return '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Approved Ketua Lab</span>';
        default: 
            return '<span class="badge badge-secondary">'.$status.'</span>';
    }
}

function getStatusInfo($status) {
    switch($status) {
        case 'pending':
            return [
                'title' => 'Menunggu Persetujuan Dosen Pembimbing',
                'desc' => 'Riset ini perlu disetujui oleh dosen pembimbing terlebih dahulu.',
                'icon' => 'fa-user-tie',
                'color' => 'warning'
            ];
        case 'approve_dosen_pembimbing':
            return [
                'title' => 'Menunggu Persetujuan Ketua Lab',
                'desc' => 'Riset sudah disetujui dosen pembimbing, menunggu persetujuan ketua lab.',
                'icon' => 'fa-user-shield',
                'color' => 'info'
            ];
        case 'approve_ketua_lab':
            return [
                'title' => 'Riset Disetujui (Final)',
                'desc' => 'Riset telah disetujui oleh dosen pembimbing dan ketua lab.',
                'icon' => 'fa-check-circle',
                'color' => 'success'
            ];
        default:
            return [
                'title' => $status,
                'desc' => '',
                'icon' => 'fa-question',
                'color' => 'secondary'
            ];
    }
}

$statusInfo = getStatusInfo($riset['status']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Review Riset - <?= htmlspecialchars($riset['judul']) ?></title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .info-card {
            border-left: 4px solid #4e73df;
            background: #f8f9fc;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .action-section {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 25px;
            margin-top: 30px;
        }
        .btn-action {
            min-width: 150px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
        }
        .detail-row {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e3e6f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 5px;
        }
        .value {
            color: #3a3b45;
            font-size: 15px;
        }
        .approval-flow {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }
        .approval-step {
            flex: 1;
            text-align: center;
            padding: 15px;
            position: relative;
        }
        .approval-step::after {
            content: '→';
            position: absolute;
            right: -20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            color: #d1d3e2;
        }
        .approval-step:last-child::after {
            content: '';
        }
        .approval-step.active {
            background: #fff3cd;
            border-radius: 8px;
        }
        .approval-step.completed {
            background: #d4edda;
            border-radius: 8px;
        }
        .approval-step .icon {
            font-size: 32px;
            margin-bottom: 10px;
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
                    <i class="fas fa-clipboard-check mr-2"></i>Review Riset
                </span>
            </nav>

            <div class="container-fluid">
                <?= $alert ?>

                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="list_riset.php">Daftar Riset</a></li>
                        <li class="breadcrumb-item active">Review Riset</li>
                    </ol>
                </nav>

                <!-- Approval Flow -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-primary mb-3">Alur Persetujuan</h6>
                        <div class="approval-flow">
                            <div class="approval-step <?= $riset['status'] === 'pending' ? 'active' : 'completed' ?>">
                                <div class="icon text-warning">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <strong>Pengajuan</strong><br>
                                <small class="text-muted">Mahasiswa</small>
                            </div>
                            <div class="approval-step <?= $riset['status'] === 'pending' ? 'active' : ($riset['status'] === 'approve_dosen_pembimbing' || $riset['status'] === 'approve_ketua_lab' ? 'completed' : '') ?>">
                                <div class="icon <?= $riset['status'] === 'pending' ? 'text-warning' : 'text-success' ?>">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <strong>Approval Dosen</strong><br>
                                <small class="text-muted">Dosen Pembimbing</small>
                            </div>
                            <div class="approval-step <?= $riset['status'] === 'approve_dosen_pembimbing' ? 'active' : ($riset['status'] === 'approve_ketua_lab' ? 'completed' : '') ?>">
                                <div class="icon <?= $riset['status'] === 'approve_dosen_pembimbing' ? 'text-info' : ($riset['status'] === 'approve_ketua_lab' ? 'text-success' : 'text-muted') ?>">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <strong>Approval Ketua Lab</strong><br>
                                <small class="text-muted">Ketua Laboratorium</small>
                            </div>
                            <div class="approval-step <?= $riset['status'] === 'approve_ketua_lab' ? 'completed' : '' ?>">
                                <div class="icon <?= $riset['status'] === 'approve_ketua_lab' ? 'text-success' : 'text-muted' ?>">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <strong>Disetujui</strong><br>
                                <small class="text-muted">Final</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Main Content -->
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-file-alt mr-2"></i>Detail Pengajuan Riset
                                </h6>
                            </div>
                            <div class="card-body">
                                
                                <!-- Status Saat Ini -->
                                <div class="alert alert-<?= $statusInfo['color'] ?> mb-4">
                                    <h5 class="alert-heading">
                                        <i class="fas <?= $statusInfo['icon'] ?> mr-2"></i>
                                        <?= $statusInfo['title'] ?>
                                    </h5>
                                    <p class="mb-0"><?= $statusInfo['desc'] ?></p>
                                    <hr>
                                    <p class="mb-0">
                                        <strong>Status:</strong> <?= getStatusBadge($riset['status']) ?>
                                    </p>
                                </div>

                                <!-- Judul -->
                                <div class="detail-row">
                                    <div class="label">Judul Riset</div>
                                    <div class="value">
                                        <h5 class="mb-0"><?= htmlspecialchars($riset['judul']) ?></h5>
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div class="detail-row">
                                    <div class="label">Deskripsi Riset</div>
                                    <div class="value">
                                        <?= nl2br(htmlspecialchars($riset['deskripsi'] ?? 'Tidak ada deskripsi')) ?>
                                    </div>
                                </div>

                                <!-- Periode -->
                                <?php if (!empty($riset['tanggal_mulai']) || !empty($riset['tanggal_selesai'])): ?>
                                <div class="detail-row">
                                    <div class="label">Periode Riset</div>
                                    <div class="value">
                                        <i class="far fa-calendar-alt text-primary mr-2"></i>
                                        <?php if (!empty($riset['tanggal_mulai'])): ?>
                                            <strong>Mulai:</strong> <?= date('d F Y', strtotime($riset['tanggal_mulai'])) ?>
                                        <?php endif; ?>
                                        <?php if (!empty($riset['tanggal_selesai'])): ?>
                                            <br><i class="far fa-calendar-check text-success mr-2"></i>
                                            <strong>Target Selesai:</strong> <?= date('d F Y', strtotime($riset['tanggal_selesai'])) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Tanggal Pengajuan -->
                                <div class="detail-row">
                                    <div class="label">Tanggal Pengajuan</div>
                                    <div class="value">
                                        <i class="far fa-clock text-muted mr-2"></i>
                                        <?= date('d F Y, H:i', strtotime($riset['created_at'])) ?> WIB
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Action Section -->
                        <?php if ($can_review): ?>
                        <div class="action-section">
                            <h5 class="font-weight-bold text-warning mb-3">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Tindakan Review
                                <?php if ($review_level === 'dosen'): ?>
                                    - Level Dosen Pembimbing
                                <?php else: ?>
                                    - Level Ketua Lab
                                <?php endif; ?>
                            </h5>
                            <p class="text-muted mb-4">
                                <?php if ($review_level === 'dosen'): ?>
                                    Silakan review pengajuan riset mahasiswa bimbingan Anda.
                                    Jika di-approve, riset akan diteruskan ke Ketua Lab.
                                <?php else: ?>
                                    Silakan review riset yang sudah disetujui dosen pembimbing.
                                    Jika di-approve, riset akan disetujui secara final.
                                <?php endif; ?>
                            </p>

                            <form method="POST" id="reviewForm">
                                <div class="form-group">
                                    <label class="font-weight-bold">Catatan Review (Opsional)</label>
                                    <textarea name="catatan" class="form-control" rows="4" 
                                        placeholder="Berikan feedback, saran, atau alasan penolakan kepada mahasiswa..."></textarea>
                                    <small class="form-text text-muted">
                                        Catatan akan dikirimkan ke mahasiswa sebagai feedback
                                    </small>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="submit" name="action" value="approve" 
                                            class="btn btn-success btn-action" 
                                            onclick="return confirmAction('approve', '<?= htmlspecialchars($riset['judul'], ENT_QUOTES) ?>', '<?= $review_level ?>')">
                                            <i class="fas fa-check mr-2"></i> 
                                            <?= $review_level === 'dosen' ? 'Approve (Lanjut ke Ketua)' : 'Approve (Final)' ?>
                                        </button>
                                        <button type="submit" name="action" value="reject" 
                                            class="btn btn-danger btn-action" 
                                            onclick="return confirmAction('reject', '<?= htmlspecialchars($riset['judul'], ENT_QUOTES) ?>', '<?= $review_level ?>')">
                                            <i class="fas fa-times mr-2"></i> Reject & Hapus
                                        </button>
                                    </div>
                                    <a href="list_riset.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <?php if ($riset['status'] === 'approve_ketua_lab'): ?>
                                Riset ini sudah disetujui secara final.
                            <?php elseif ($role === 'dosen' && $riset['status'] !== 'pending'): ?>
                                Riset ini sudah Anda approve dan sedang menunggu persetujuan Ketua Lab.
                            <?php elseif ($role === 'ketua' && $riset['status'] === 'pending'): ?>
                                Riset ini masih menunggu persetujuan dari Dosen Pembimbing.
                            <?php else: ?>
                                Anda tidak memiliki akses untuk mereview riset ini.
                            <?php endif; ?>
                            <div class="mt-2">
                                <a href="list_riset.php" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Sidebar Info -->
                    <div class="col-lg-4">
                        <!-- Info Mahasiswa -->
                        <div class="card shadow mb-4 border-left-primary">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-user-graduate mr-2"></i>Informasi Mahasiswa
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Nama:</strong><br>
                                    <?= htmlspecialchars($riset['mahasiswa_nama']) ?>
                                </div>
                                <div class="mb-3">
                                    <strong>NIM:</strong><br>
                                    <?= htmlspecialchars($riset['nim']) ?>
                                </div>
                                <?php if (!empty($riset['email'])): ?>
                                <div class="mb-3">
                                    <strong>Email:</strong><br>
                                    <a href="mailto:<?= htmlspecialchars($riset['email']) ?>">
                                        <i class="fas fa-envelope mr-1"></i>
                                        <?= htmlspecialchars($riset['email']) ?>
                                    </a>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($riset['dosen_nama'])): ?>
                                <div>
                                    <strong>Dosen Pembimbing:</strong><br>
                                    <?= htmlspecialchars($riset['dosen_nama']) ?>
                                    <br><small class="text-muted">NIP: <?= htmlspecialchars($riset['nip']) ?></small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Panduan Review -->
                        <div class="card shadow mb-4 border-left-info">
                            <div class="card-body">
                                <h6 class="font-weight-bold text-info mb-3">
                                    <i class="fas fa-info-circle mr-2"></i>Panduan Review
                                </h6>
                                <ul class="pl-3 mb-0" style="font-size: 0.9rem;">
                                    <li class="mb-2">Periksa kelengkapan judul dan deskripsi riset</li>
                                    <li class="mb-2">Pastikan topik riset sesuai dengan bidang studi</li>
                                    <li class="mb-2">Evaluasi kelayakan timeline yang diajukan</li>
                                    <li class="mb-2">Berikan feedback konstruktif jika menolak</li>
                                    <li class="mb-0"><strong class="text-danger">Perhatian:</strong> Riset yang di-reject akan langsung dihapus dari sistem</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Status Info -->
                        <div class="card shadow mb-4 border-left-warning">
                            <div class="card-body">
                                <h6 class="font-weight-bold text-warning mb-3">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>Konsekuensi Tindakan
                                </h6>
                                <div style="font-size: 0.9rem;">
                                    <p class="mb-2">
                                        <strong class="text-success">Approve:</strong><br>
                                        <small>
                                            <?php if ($review_level === 'dosen'): ?>
                                                Riset akan diteruskan ke Ketua Lab untuk persetujuan final
                                            <?php else: ?>
                                                Riset akan disetujui secara final dan mahasiswa dapat melanjutkan
                                            <?php endif; ?>
                                        </small>
                                    </p>
                                    <p class="mb-0">
                                        <strong class="text-danger">Reject:</strong><br>
                                        <small>Riset akan <u>dihapus permanen</u> dari sistem. Mahasiswa perlu mengajukan ulang dengan perbaikan.</small>
                                    </p>
                                </div>
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
<script src="../assets/js/sb-admin-2.min.js"></script>
<script>
function confirmAction(action, judulRiset, reviewLevel) {
    let message = '';
    
    if (action === 'approve') {
        if (reviewLevel === 'dosen') {
            message = 'Yakin ingin APPROVE riset:\n"' + judulRiset + '"?\n\n' +
                      'Riset akan diteruskan ke Ketua Lab untuk persetujuan final.';
        } else {
            message = 'Yakin ingin APPROVE riset:\n"' + judulRiset + '"?\n\n' +
                      'Ini adalah persetujuan FINAL. Riset akan disetujui secara penuh.';
        }
    } else if (action === 'reject') {
        message = 'PERHATIAN! Yakin ingin REJECT riset:\n"' + judulRiset + '"?\n\n' +
                  '⚠️ RISET AKAN DIHAPUS PERMANEN dari sistem!\n' +
                  'Mahasiswa harus mengajukan ulang jika ingin melanjutkan.\n\n' +
                  'Tindakan ini TIDAK DAPAT DIBATALKAN!';
    }
    
    return confirm(message);
}

// Auto-dismiss alerts
$(document).ready(function() {
    setTimeout(function() {
        $('.alert:not(.alert-<?= $statusInfo["color"] ?>)').fadeOut('slow');
    }, 5000);
});
</script>
</body>
</html>