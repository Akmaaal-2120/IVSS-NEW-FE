<?php
session_start();

include '../inc/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ketua') {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$alert = '';

// ambil daftar dosen yang belum punya user
$dosen_q = pg_query_params($koneksi, "SELECT nip, nama FROM dosen WHERE user_id IS NULL ORDER BY nama ASC", array());
$dosen_list = $dosen_q ? pg_fetch_all($dosen_q) : [];
if ($dosen_list === false) $dosen_list = [];

// ambil mahasiswa yang belum punya user dan sudah acc_ketua
$mhs_q = pg_query_params($koneksi,
    "SELECT nim, nama FROM mahasiswa WHERE user_id IS NULL AND status_pendaftaran = $1 ORDER BY nama ASC",
    array('acc_ketua')
);
$mhs_list = $mhs_q ? pg_fetch_all($mhs_q) : [];
if ($mhs_list === false) $mhs_list = [];

// allowed roles
$allowed_roles = ['ketua', 'admin', 'berita', 'mahasiswa'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entity_type = trim($_POST['entity_type'] ?? '');
    // tetap ambil entity_id dari satu field entity_id — JS akan memastikan hanya select aktif yang tidak disabled
    $entity_id   = trim($_POST['entity_id'] ?? '');
    $username    = trim($_POST['username'] ?? '');
    $password    = trim($_POST['password'] ?? '');
    $role        = trim($_POST['role'] ?? '');

    // basic validation
    if ($username === '' || $password === '' || $role === '') {
        $alert = '<div class="alert alert-danger">Username, password, dan role wajib diisi.</div>';
    } elseif (!in_array($role, $allowed_roles, true)) {
        $alert = '<div class="alert alert-danger">Role tidak valid.</div>';
    } else {
        // insert user (plain password as requested)
        $res = pg_query_params($koneksi, 'INSERT INTO users(username,password,role) VALUES($1,$2,$3) RETURNING user_id', array($username, $password, $role));

        if ($res && pg_num_rows($res) === 1) {
            $new_user = pg_fetch_assoc($res);
            $new_id = (int)$new_user['user_id'];
            pg_free_result($res);

            // langsung update dosen/mahasiswa sesuai entity
            if ($entity_type === 'dosen' && $entity_id !== '') {
                $u = pg_query_params($koneksi, 'UPDATE dosen SET user_id=$1 WHERE nip=$2', array($new_id, $entity_id));
                $alert = $u ?
                    '<div class="alert alert-success">User berhasil dibuat dan dikaitkan ke dosen.</div>' :
                    '<div class="alert alert-warning">User dibuat tapi gagal dikaitkan ke dosen.</div>';
            } elseif ($entity_type === 'mahasiswa' && $entity_id !== '') {
                $u = pg_query_params($koneksi, 'UPDATE mahasiswa SET user_id=$1 WHERE nim=$2', array($new_id, $entity_id));
                $alert = $u ?
                    '<div class="alert alert-success">User berhasil dibuat dan dikaitkan ke mahasiswa.</div>' :
                    '<div class="alert alert-warning">User dibuat tapi gagal dikaitkan ke mahasiswa.</div>';
            } else {
                $alert = '<div class="alert alert-success">User berhasil dibuat.</div>';
            }

            // refresh lists: dosen & mahasiswa (mah hanya yang acc_ketua dan belum punya user)
            $dosen_q = pg_query_params($koneksi, "SELECT nip, nama FROM dosen WHERE user_id IS NULL ORDER BY nama ASC", array());
            $dosen_list = $dosen_q ? pg_fetch_all($dosen_q) : [];
            if ($dosen_list === false) $dosen_list = [];

            $mhs_q = pg_query_params($koneksi,
                "SELECT nim, nama FROM mahasiswa WHERE user_id IS NULL AND status_pendaftaran = $1 ORDER BY nama ASC",
                array('acc_ketua')
            );
            $mhs_list = $mhs_q ? pg_fetch_all($mhs_q) : [];
            if ($mhs_list === false) $mhs_list = [];

        } else {
            $pgerr = pg_last_error($koneksi);
            if (stripos($pgerr, 'duplicate') !== false || stripos($pgerr, 'unique') !== false) {
                $alert = '<div class="alert alert-danger">Username sudah terpakai.</div>';
            } else {
                $alert = '<div class="alert alert-danger">Gagal membuat user. ' . htmlspecialchars($pgerr) . '</div>';
            }
        }
    }
}

// ambil semua users beserta info dosen/mahasiswa
$users_q = pg_query($koneksi, "
    SELECT u.user_id,u.username,u.role,u.created_at,
        d.nip,d.nama AS dosen_nama,
        m.nim,m.nama AS mhs_nama
    FROM users u
    LEFT JOIN dosen d ON d.user_id=u.user_id
    LEFT JOIN mahasiswa m ON m.user_id=u.user_id
    ORDER BY u.user_id DESC
");
$users = $users_q ? pg_fetch_all($users_q) : [];
if ($users === false) $users = [];

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Manajemen Users</title>
<link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
<link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>.small-muted { font-size:.875rem;color:#6c757d; }</style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include 'inc/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                <span class="h5 mb-0 text-primary">Manajemen Users</span>
            </nav>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Users</h1>
                <?= $alert ?>

                <div class="row">
                    <div class="col-lg-5">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header"><strong>Tambah User</strong></div>
                            <div class="card-body">
                                <form method="POST" class="needs-validation" novalidate>
                                    <div class="form-group">
                                        <label>Pilih Entitas (opsional)</label>
                                        <select id="entity_type" name="entity_type" class="form-control" onchange="toggleEntitySelect()">
                                            <option value="">-- tidak ada --</option>
                                            <option value="dosen">Dosen</option>
                                            <option value="mahasiswa">Mahasiswa</option>
                                        </select>
                                    </div>

                                    <div class="form-group" id="select-dosen" style="display:none;">
                                        <label>Dosen (belum punya akun)</label>
                                        <select name="entity_id" class="form-control">
                                            <option value="">-- pilih dosen --</option>
                                            <?php foreach ($dosen_list as $d) : ?>
                                                <option value="<?= htmlspecialchars($d['nip']) ?>"><?= htmlspecialchars($d['nama'] . ' (' . $d['nip'] . ')') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="form-text text-muted">Hanya dosen yang belum punya akun yang muncul.</small>
                                    </div>

                                    <div class="form-group" id="select-mhs" style="display:none;">
                                        <label>Mahasiswa (belum punya akun & sudah disetujui Ketua)</label>
                                        <select name="entity_id" class="form-control">
                                            <option value="">-- pilih mahasiswa --</option>
                                            <?php foreach ($mhs_list as $m) : ?>
                                                <option value="<?= htmlspecialchars($m['nim']) ?>"><?= htmlspecialchars($m['nama'] . ' (' . $m['nim'] . ')') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="form-text text-muted">Hanya mahasiswa dengan status pendaftaran <strong>acc_ketua</strong> dan tanpa user yang muncul.</small>
                                    </div>

                                    <div class="form-group">
                                        <label>Username</label>
                                        <input name="username" type="text" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Password</label>
                                        <input name="password" type="password" class="form-control" required>
                                        <small class="form-text text-muted"><strong>Plain password</strong> (User disarankan login agar berubah menjadi Hash+salt dan ubah password pada halaman profil)</small>
                                    </div>

                                    <div class="form-group">
                                        <label>Role</label>
                                        <select name="role" class="form-control" required>
                                            <option value="">-- pilih role --</option>
                                            <option value="ketua">Ketua Lab</option>
                                            <option value="admin">Admin Lab</option>
                                            <option value="berita">Admin Berita</option>
                                            <option value="mahasiswa">Mahasiswa</option>
                                        </select>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button class="btn btn-success" type="submit"><i class="fas fa-save"></i> Simpan</button>
                                        <button type="reset" class="btn btn-light" onclick="resetEntitySelect()">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header"><strong>Daftar Users</strong></div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                                        <thead class="table-primary text-center">
                                            <tr>
                                                <th>No</th>
                                                <th>Username</th>
                                                <th>Role</th>
                                                <th>Terkait</th>
                                                <th>Created</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($users)) : ?>
                                                <tr><td colspan="5" class="text-center text-muted">Belum ada user.</td></tr>
                                            <?php else :
                                                foreach ($users as $i => $u) : ?>
                                                    <tr>
                                                        <td class="text-center"><?= $i + 1 ?></td>
                                                        <td><?= htmlspecialchars($u['username']) ?></td>
                                                        <td><?= htmlspecialchars($u['role']) ?></td>
                                                        <td>
                                                            <?php
                                                            if (!empty($u['nip'])) echo 'Dosen: ' . htmlspecialchars($u['dosen_nama']) . ' (' . $u['nip'] . ')';
                                                            elseif (!empty($u['nim'])) echo 'Mahasiswa: ' . htmlspecialchars($u['mhs_nama']) . ' (NIM:' . $u['nim'] . ')';
                                                            else echo '<span class="small-muted">Tidak terhubung</span>';
                                                            ?>
                                                        </td>
                                                        <td><?= htmlspecialchars($u['created_at']) ?></td>
                                                    </tr>
                                                <?php endforeach;
                                            endif; ?>
                                        </tbody>
                                    </table>
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
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>
<script>
    function toggleEntitySelect() {
        var t = document.getElementById('entity_type').value;
        var sd = document.getElementById('select-dosen');
        var sm = document.getElementById('select-mhs');

        // tampil/hidden block
        sd.style.display = (t === 'dosen') ? 'block' : 'none';
        sm.style.display = (t === 'mahasiswa') ? 'block' : 'none';

        // disable select yang tidak aktif supaya tidak terkirim saat submit
        var selD = sd.querySelector('select[name="entity_id"]');
        var selM = sm.querySelector('select[name="entity_id"]');
        if (selD) selD.disabled = (t !== 'dosen');
        if (selM) selM.disabled = (t !== 'mahasiswa');
    }

    function resetEntitySelect() {
        document.getElementById('entity_type').value = '';
        toggleEntitySelect();
    }

    $(document).ready(function() {
        // pastikan state awal benar (mis. setelah reload)
        toggleEntitySelect();

        $('#usersTable').DataTable({
            "columnDefs": [{
                "orderable": false,
                "targets": [3]
            }]
        });
    });
</script>
</body>
</html>
