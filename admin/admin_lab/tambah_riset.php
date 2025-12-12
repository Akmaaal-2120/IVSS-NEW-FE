<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$alert = '';

// Ambil data user (admin lab)
// Kita anggap admin lab bisa langsung mengajukan ke Ketua Lab
// Status langsung: approve_dosen_pembimbing (seolah sudah di-acc dosen, karena dia sendiri dosen/admin)

if (isset($_POST['submit'])) {
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_mulai = trim($_POST['tanggal_mulai']);
    $tanggal_selesai = trim($_POST['tanggal_selesai']);

    // Validasi
    if (empty($judul)) {
        $alert = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Judul riset harus diisi.</div>';
    } elseif (!empty($tanggal_mulai) && !empty($tanggal_selesai) && $tanggal_selesai < $tanggal_mulai) {
        $alert = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Tanggal selesai tidak boleh lebih awal dari tanggal mulai.</div>';
    } else {
        // Set nilai default untuk tanggal
        $tanggal_mulai_val = !empty($tanggal_mulai) ? $tanggal_mulai : null;
        $tanggal_selesai_val = !empty($tanggal_selesai) ? $tanggal_selesai : null;

        // Status langsung 'approve_dosen_pembimbing' agar langsung masuk ke dashboard Ketua Lab
        // approved_dosen_by diisi dengan ID sendiri (user_id admin/dosen ini)
        // Kita butuh NIP/ID user sebagai approved_dosen_by.
        // Cek struktur tabel riset, approved_dosen_by refer ke mana?
        // Di mahasiswa/riset.php join ke dosen d ON r.approved_dosen_by = d.user_id (atau nip?)
        // Cek admin/admin_lab/riset.php tadi: LEFT JOIN dosen d ON r.approved_dosen_by = d.nip (WAIT, check this logic again from reading step 13)
        // Step 13: LEFT JOIN dosen d ON r.approved_dosen_by = d.nip
        // Tapi di Step 6 (mahasiswa/riset.php): LEFT JOIN dosen d ON r.approved_dosen_by = d.user_id
        // Ini inkonsisten. Mari kita lihat tambah_riset.php mahasiswa (step 7): 
        // $dosen_user_id = $row_dosen['user_id']; INSERT ... approved_dosen_by = $dosen_user_id
        // Jadi approved_dosen_by menyimpan USER_ID (PK table users/dosen).

        // Namun, jika saya admin lab (yang mungkin juga dosen), saya pakai user_id saya sendiri.

        $insert = pg_query_params($koneksi, "
            INSERT INTO riset (judul, deskripsi, creator_id, tanggal_mulai, tanggal_selesai, status, approved_dosen_by)
            VALUES ($1, $2, $3, $4, $5, 'approve_dosen_pembimbing', $6)
        ", [$judul, $deskripsi, $user_id, $tanggal_mulai_val, $tanggal_selesai_val, $user_id]);

        if ($insert) {
            header('Location: riset.php?msg=created');
            exit;
        } else {
            $error_msg = pg_last_error($koneksi);
            $alert = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Gagal mengajukan riset. ' . htmlspecialchars($error_msg) . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Ajukan Riset Baru (Admin Lab)</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .info-box i {
            color: #2196F3;
            margin-right: 10px;
        }

        .required {
            color: #dc3545;
        }

        .form-label {
            font-weight: 600;
            color: #4e73df;
            margin-bottom: 8px;
        }

        .char-count {
            font-size: 0.875rem;
            color: #6c757d;
            float: right;
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
                        <i class="fas fa-flask mr-2"></i>Ajukan Riset Baru
                    </span>
                </nav>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Form Pengajuan Riset</h6>
                                </div>
                                <div class="card-body">
                                    <?= $alert ?>

                                    <div class="info-box">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Informasi:</strong> Sebagai Admin Lab / Dosen, pengajuan riset Anda akan
                                        langsung berstatus <strong>"Disetujui Dosen"</strong> dan diteruskan ke
                                        <strong>Ketua Lab</strong> untuk persetujuan akhir.
                                    </div>

                                    <form method="POST" id="formRiset">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Judul Riset <span class="required">*</span>
                                            </label>
                                            <input type="text" name="judul" class="form-control"
                                                placeholder="Contoh: Penelitian Keamanan Jaringan Wireless" required
                                                maxlength="255"
                                                value="<?= isset($_POST['judul']) ? htmlspecialchars($_POST['judul']) : '' ?>">
                                            <small class="form-text text-muted">
                                                Tuliskan judul riset yang jelas dan deskriptif
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">
                                                Deskripsi Riset
                                                <span class="char-count">
                                                    <span id="charCount">0</span>/1000 karakter
                                                </span>
                                            </label>
                                            <textarea name="deskripsi" class="form-control" rows="6" id="deskripsi"
                                                maxlength="1000"
                                                placeholder="Jelaskan secara singkat latar belakang, tujuan, dan metode penelitian."><?= isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : '' ?></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="far fa-calendar-alt mr-1"></i>Tanggal Mulai
                                                    </label>
                                                    <input type="date" name="tanggal_mulai" class="form-control"
                                                        id="tanggalMulai"
                                                        value="<?= isset($_POST['tanggal_mulai']) ? htmlspecialchars($_POST['tanggal_mulai']) : '' ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="far fa-calendar-check mr-1"></i>Tanggal Selesai
                                                        (Target)
                                                    </label>
                                                    <input type="date" name="tanggal_selesai" class="form-control"
                                                        id="tanggalSelesai"
                                                        value="<?= isset($_POST['tanggal_selesai']) ? htmlspecialchars($_POST['tanggal_selesai']) : '' ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <button type="submit" name="submit" class="btn btn-success">
                                                    <i class="fas fa-paper-plane mr-1"></i> Ajukan Riset
                                                </button>
                                                <a href="riset.php" class="btn btn-secondary">
                                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                                </a>
                                            </div>
                                            <button type="reset" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-redo mr-1"></i> Reset
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Info -->
                        <div class="col-lg-4">
                            <div class="card shadow mb-4 border-left-info">
                                <div class="card-body">
                                    <h6 class="font-weight-bold text-info">
                                        <i class="fas fa-tasks mr-2"></i>Alur Proses
                                    </h6>
                                    <div style="font-size: 0.9rem;">
                                        <div class="mb-2 text-muted">
                                            <i class="fas fa-circle text-gray-300" style="font-size: 0.5rem;"></i>
                                            <strike>Pending Dosen</strike> (Dilewati)
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-check-circle text-info" style="font-size: 0.9rem;"></i>
                                            <strong> Approved Dosen:</strong> Otomatis (Anda sendiri)
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-arrow-right text-warning" style="font-size: 0.9rem;"></i>
                                            <strong> Pending Ketua Lab:</strong> Menunggu persetujuan Ketua Lab
                                        </div>
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
        $(document).ready(function () {
            $('#deskripsi').on('input', function () {
                var length = $(this).val().length;
                $('#charCount').text(length);
                if (length > 900) $('#charCount').css('color', '#dc3545');
                else if (length > 800) $('#charCount').css('color', '#ffc107');
                else $('#charCount').css('color', '#6c757d');
            });

            $('#formRiset').on('submit', function (e) {
                var judul = $('input[name="judul"]').val();
                if (!confirm('Ajukan riset ini?\n\nJudul: ' + judul + '\n\nStatus akan langsung "Approved Dosen" dan diteruskan ke Ketua Lab.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>