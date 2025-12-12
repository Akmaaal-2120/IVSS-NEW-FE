<?php
session_start();
include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$alert = '';

// Ambil data mahasiswa (dosen pembimbing)
$q_mhs = pg_query_params($koneksi, "SELECT nim, nama, dosen_pembimbing FROM mahasiswa WHERE user_id = $1", [$user_id]);
$mhs = pg_fetch_assoc($q_mhs);
$dosen_pembimbing = $mhs['dosen_pembimbing'] ?? null;
$nama_mahasiswa = $mhs['nama'] ?? '';

// Ambil nama dosen pembimbing dan user_id nya
$nama_dosen = '-';
$dosen_user_id = null;
if ($dosen_pembimbing) {
    $q_dosen = pg_query_params($koneksi, "SELECT nama, user_id FROM dosen WHERE nip = $1", [$dosen_pembimbing]);
    if ($q_dosen && pg_num_rows($q_dosen) > 0) {
        $row_dosen = pg_fetch_assoc($q_dosen);
        $nama_dosen = $row_dosen['nama'];
        $dosen_user_id = $row_dosen['user_id'];
    }
}

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
        // Set nilai default untuk tanggal jika kosong
        $tanggal_mulai_val = !empty($tanggal_mulai) ? $tanggal_mulai : null;
        $tanggal_selesai_val = !empty($tanggal_selesai) ? $tanggal_selesai : null;

        // Status langsung 'pending' agar bisa direview oleh dosen/admin
        $insert = pg_query_params($koneksi, "
            INSERT INTO riset (judul, deskripsi, creator_id, tanggal_mulai, tanggal_selesai, status, approved_dosen_by)
            VALUES ($1, $2, $3, $4, $5, 'pending', $6)
        ", [$judul, $deskripsi, $user_id, $tanggal_mulai_val, $tanggal_selesai_val, $dosen_user_id]);

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
    <title>Ajukan Riset Baru</title>
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
                                        <strong>Informasi:</strong> Pengajuan riset akan dikirim ke dosen pembimbing
                                        Anda
                                        <strong>(<?= htmlspecialchars($nama_dosen) ?>)</strong> untuk direview dan
                                        disetujui.
                                    </div>

                                    <form method="POST" id="formRiset">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Judul Riset <span class="required">*</span>
                                            </label>
                                            <input type="text" name="judul" class="form-control"
                                                placeholder="Contoh: Implementasi Machine Learning untuk Deteksi Penyakit Tanaman"
                                                required maxlength="255"
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
                                                placeholder="Jelaskan secara singkat:&#10;• Latar belakang riset&#10;• Tujuan penelitian&#10;• Metode yang akan digunakan&#10;• Hasil yang diharapkan"><?= isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : '' ?></textarea>
                                            <small class="form-text text-muted">
                                                Berikan gambaran umum tentang riset yang akan dilakukan
                                            </small>
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
                                                    <small class="form-text text-muted">
                                                        Perkiraan tanggal mulai riset
                                                    </small>
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
                                                    <small class="form-text text-muted">
                                                        Estimasi waktu penyelesaian
                                                    </small>
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
                                                <i class="fas fa-redo mr-1"></i> Reset Form
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Info -->
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-user-graduate mr-2"></i>Informasi Mahasiswa
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Nama:</strong><br><?= htmlspecialchars($nama_mahasiswa) ?></p>
                                    <p><strong>NIM:</strong><br><?= htmlspecialchars($mhs['nim']) ?></p>
                                    <p><strong>Dosen Pembimbing:</strong><br><?= htmlspecialchars($nama_dosen) ?></p>
                                </div>
                            </div>

                            <div class="card shadow mb-4 border-left-warning">
                                <div class="card-body">
                                    <h6 class="font-weight-bold text-warning">
                                        <i class="fas fa-lightbulb mr-2"></i>Tips Pengajuan Riset
                                    </h6>
                                    <ul class="pl-3 mb-0" style="font-size: 0.9rem;">
                                        <li class="mb-2">Pastikan judul riset jelas dan spesifik</li>
                                        <li class="mb-2">Jelaskan tujuan riset dengan detail</li>
                                        <li class="mb-2">Sebutkan metode yang akan digunakan</li>
                                        <li class="mb-2">Tentukan timeline yang realistis</li>
                                        <li class="mb-0">Konsultasikan terlebih dahulu dengan dosen pembimbing</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card shadow mb-4 border-left-info">
                                <div class="card-body">
                                    <h6 class="font-weight-bold text-info">
                                        <i class="fas fa-tasks mr-2"></i>Proses Review
                                    </h6>
                                    <div style="font-size: 0.9rem;">
                                        <div class="mb-2">
                                            <i class="fas fa-circle text-warning" style="font-size: 0.5rem;"></i>
                                            <strong> Pending:</strong> Menunggu review dosen
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-circle text-success" style="font-size: 0.5rem;"></i>
                                            <strong> Approved:</strong> Riset disetujui
                                        </div>
                                        <div class="mb-0">
                                            <i class="fas fa-circle text-danger" style="font-size: 0.5rem;"></i>
                                            <strong> Rejected:</strong> Riset ditolak
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
            // Character counter untuk deskripsi
            $('#deskripsi').on('input', function () {
                var length = $(this).val().length;
                $('#charCount').text(length);

                if (length > 900) {
                    $('#charCount').css('color', '#dc3545');
                } else if (length > 800) {
                    $('#charCount').css('color', '#ffc107');
                } else {
                    $('#charCount').css('color', '#6c757d');
                }
            });

            // Trigger on load jika ada value
            $('#deskripsi').trigger('input');

            // Validasi tanggal
            $('#tanggalSelesai').on('change', function () {
                var mulai = $('#tanggalMulai').val();
                var selesai = $(this).val();

                if (mulai && selesai && selesai < mulai) {
                    alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai!');
                    $(this).val('');
                }
            });

            $('#tanggalMulai').on('change', function () {
                var mulai = $(this).val();
                var selesai = $('#tanggalSelesai').val();

                if (mulai && selesai && selesai < mulai) {
                    alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai!');
                    $('#tanggalSelesai').val('');
                }
            });

            // Konfirmasi sebelum submit
            $('#formRiset').on('submit', function (e) {
                var judul = $('input[name="judul"]').val();
                if (!confirm('Ajukan riset dengan judul "' + judul + '"?\n\nPastikan semua informasi sudah benar.\nRiset akan langsung dikirim ke dosen pembimbing untuk direview.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>