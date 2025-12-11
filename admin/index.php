<?php
session_start();
include 'inc/koneksi.php';

$error = '';
$logo_src = '';

// Ambil logo
$q = @pg_query($koneksi, "SELECT logo FROM logo ORDER BY id DESC LIMIT 1");
if ($q && pg_num_rows($q) > 0) {
    $r = pg_fetch_assoc($q);
    $logo_src = trim($r['logo'] ?? '');
    pg_free_result($q);
}

// Fungsi hash password + salt
function hashPassword($password, $salt) {
    return hash('sha256', $salt . $password);
}

// Proses login
if (isset($_POST['username'], $_POST['password'], $_POST['role'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role     = trim($_POST['role']);

    $query = 'SELECT user_id, username, password, salt, role FROM users WHERE username=$1 AND role=$2';
    $result = pg_query_params($koneksi, $query, [$username, $role]);

    if ($result && pg_num_rows($result) === 1) {
        $data = pg_fetch_assoc($result);
        $stored = $data['password'];
        $salt   = $data['salt'] ?? '';
        $user_id = $data['user_id'];

        $ok = false;

        if ($salt) {
            // password sudah hash
            if (hashPassword($password, $salt) === $stored) {
                $ok = true;
            }
        } else {
            // password masih plain
            if (hash_equals($stored, $password)) {
                $ok = true;
                // generate salt baru + hash password
                $new_salt = bin2hex(random_bytes(8)); // 16 karakter hex
                $new_hash = hashPassword($password, $new_salt);
                pg_query_params($koneksi, 'UPDATE users SET password=$1, salt=$2 WHERE user_id=$3', [$new_hash, $new_salt, $user_id]);
            }
        }

        if ($ok) {
            $_SESSION['user_id']  = $data['user_id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role']     = $data['role'];

            switch ($data['role']) {
                case 'berita': header('Location: admin_berita'); exit;
                case 'admin':  header('Location: admin_lab'); exit;
                case 'mahasiswa': header('Location: mahasiswa'); exit;
                case 'ketua':  header('Location: ketua_lab'); exit;
                default: header('Location: login.php'); exit;
            }
        } else {
            $error = 'Username, password, atau peran salah.';
        }

    } else {
        $error = 'Username, password, atau peran salah.';
    }

    if ($result) pg_free_result($result);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Login Admin - Lab IVSS</title>
<link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
<link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
<style>
.login-logo { width: 180px; height: 180px; object-fit: contain; }
</style>
</head>
<body class="bg-gradient-primary d-flex align-items-center justify-content-center min-vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card o-hidden border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Login Panel Lab IVSS</h1>
                        <?php if ($logo_src): ?>
                            <img src="assets/img/<?=htmlspecialchars($logo_src) ?>" alt="Logo Lab IVSS" class="login-logo">
                        <?php endif; ?>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form class="user" method="POST" action="">
                        <div class="form-group">
                            <input type="text" name="username" class="form-control form-control-user" placeholder="Username" required autofocus>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <select name="role" class="form-control" required>
                                <option value="">-- Pilih Peran --</option>
                                <option value="ketua">Ketua Lab</option>
                                <option value="admin">Admin Lab</option>
                                <option value="berita">Admin Berita</option>
                                <option value="mahasiswa">Mahasiswa</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </button>
                    </form>

                    <hr>
                    <div class="text-center small text-muted">
                        &copy; 2025 Lab Intelligent Vision and Smart System
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="assets/js/sb-admin-2.min.js"></script>
</body>
</html>
