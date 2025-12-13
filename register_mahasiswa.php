<?php
include './backEnd/prosesRegisterMahasiswa.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

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

<?php include('inc/navbar.php')?>

<section class="py-5" style="background-color: #f8f9fa; min-height: vh; margin-top: 20px;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 p-4 p-md-5" style="border-radius: 20px;">

                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-dark mb-3" style="font-size: 1.8rem;">
                            Registrasi Akun Mahasiswa & Member Laboratorium
                        </h2>
                        <div class="mx-auto" style="width: 900px; height: 4px; background-color: #FFBC3B; margin: 15px auto 0;"></div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-7">
                            <form action="#" method="POST">
                                <div class="mb-4">
                                    <label for="namaLengkap" class="form-label fw-bold text-dark">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control form-control-lg border-0 shadow-sm" id="namaLengkap" 
                                            placeholder="Masukkan Nama Lengkap" required
                                            style="background-color: #e9ecef;  font-size: 0.9rem;">
                                </div>

                                <div class="mb-4">
                                    <label for="nim" class="form-label fw-bold text-dark">NIM</label>
                                    <input type="text" name="nim" class="form-control form-control-lg border-0 shadow-sm" id="nim" 
                                            placeholder="Masukkan NIM" required
                                            style="background-color: #e9ecef;  font-size: 0.9rem;">
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label for="jenisKelamin" class="form-label fw-bold text-dark">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-select form-control-lg border-0 shadow-sm" id="jenisKelamin" required
                                                style="background-color: #e9ecef;  font-size: 0.9rem;">
                                            <option selected disabled>Select</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="email" class="form-label fw-bold text-dark">Email</label>
                                        <input type="email" name="email" class="form-control form-control-lg border-0 shadow-sm" id="email" 
                                                placeholder="Masukkan Email" required
                                                style="background-color: #e9ecef;  font-size: 0.9rem;">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="noTelp" class="form-label fw-bold text-dark">No. Telp</label>
                                    <input type="tel" name="no_telp" class="form-control form-control-lg border-0 shadow-sm" id="noTelp" 
                                            placeholder="Masukkan No. Telp" required
                                            style="background-color: #e9ecef;  font-size: 0.9rem;">
                                </div>

                                <div class="mb-4">
                                    <label for="keperluan" class="form-label fw-bold text-dark">Keperluan yang Ingin Diajukan</label>
                                    <textarea name="keperluan" class="form-control border-0 shadow-sm" id="keperluan" rows="5" 
                                                placeholder="Deskripsi keperluan" required
                                                style="background-color: #e9ecef;  font-size: 0.9rem;"></textarea>
                                </div>

                                <div class="mb-5">
                                    <label for="dosenPembimbing" class="form-label fw-bold text-dark">Dosen Pembimbing</label>
                                    <select name="dosen_pembimbing" class="form-select form-control-lg border-0 shadow-sm" id="dosenPembimbing" required
                                            style="background-color: #e9ecef; font-size: 0.9rem;">
                                        <option selected disabled value="">Pilih Dosen Pembimbing</option>
                                        <?php
                                            if ($result_dosen && pg_num_rows($result_dosen) > 0) {
                                                while ($dosen = pg_fetch_assoc($result_dosen)) {
                                                    $nip = htmlspecialchars($dosen['nip']);
                                                    $nama_dosen = htmlspecialchars($dosen['nama']);
                                                    echo "<option value=\"$nip\">$nama_dosen ($nip)</option>";
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>

                                <div class="card shadow-lg border-0 p-4 mt-5" style="border-radius: 20px;">
                                    <div class="p-3">
                                        <div class="d-flex align-items-start mb-3">
                                            <span class="icon-warning me-3" style="font-size: 1.3rem; color: #FFBC3B;">
                                                <i class="lni lni-warning"></i>
                                            </span>
                                            <h5 class="fw-bold text-dark m-0">Proses Persetujuan Keanggotaan:</h5>
                                        </div>
                                        
                                        <ol style="padding-left: 20px;">
                                            <li class="mb-2">Pendaftaran Anda akan direview oleh <b>Dosen Pembimbing</b> yang telah Anda pilih.</li>
                                            <li class="mb-2">Setelah disetujui oleh Dosen Pembimbing, pengajuan akan dilanjutkan untuk direview oleh <b>Ketua Laboratorium</b> </li>
                                            <li>Setelah mendapatkan persetujuan dari kedua pihak, akun Anda akan diaktifkan sehingga Anda dapat melakukan <b>login</b>.</li>
                                        </ol>

                                    </div>
                                </div>
                                
                                <p class="mt-4 mb-3">
                                Sudah Registrasi? Silahkan cek status registrasi anda
                                <a href="cekRegistrasi.php">disini</a>.
                                </p>
                                
                                <div class="d-flex justify-content-start gap-3">
                                    <button type="button" class="btn btn-sm fw-bold text-white shadow"
                                            style="background-color: #1A1A37; border-radius: 10px;"
                                            onclick="window.location.href='index.php';">
                                        Kembali
                                    </button>
                                    <button type="submit" class="btn btn-sm fw-bold text-white shadow"
                                            style="background-color: #1A1A37; border-radius: 10px;">
                                        Kirim
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-5 d-flex flex-column align-items-center justify-content-center text-center">
                            <img src="admin/assets/img/<?php echo $logo_data['logo'] ?>"
                                alt="Logo IVSS" class="img-fluid mb-3" style="max-width: 400px;">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('inc/footer.php')?>

</body>
</html>