<?php
include '../inc/koneksi.php';

$q = @pg_query($koneksi, "SELECT logo FROM logo ORDER BY id DESC LIMIT 1");
if ($q && pg_num_rows($q) > 0) {
    $r = pg_fetch_assoc($q);
    $val = trim($r['logo'] ?? '');
    if ($val !== '') {
        $logo_src = $val; // nama file
    }
    pg_free_result($q);
}
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
            <?php if ($logo_src): ?>
                <img src="../../assets/img/<?= htmlspecialchars($logo_src) ?>" alt="Logo IVSS"style="width:40px; height:40px; object-fit:cover; border-radius:6px;">
            <?php else: ?>
                <div style="width:40px; height:40px; background:#ccc; border-radius:6px;"></div>
            <?php endif; ?>
        </div>
        <div class="sidebar-brand-text mx-3">Lab IVSS Admin</div>
    </a>


    <hr class="sidebar-divider d-none d-md-block">

    <!-- Beranda -->
    <div class="sidebar-heading">Beranda</div>
    <li class="nav-item">
        <a class="nav-link" href="index.php">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Data Akademik -->
    <div class="sidebar-heading">Data Akademik</div>
    <li class="nav-item">
        <a class="nav-link" href="mahasiswa.php">
            <i class="fas fa-user-graduate"></i>
            <span>Data Mahasiswa</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="dosen.php">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Data Dosen / Member Lab</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- List ACC -->
    <div class="sidebar-heading">Persetujuan Bimbingan</div>
    <li class="nav-item">
        <a class="nav-link" href="acc_mhs.php">
            <i class="fas fa-tasks"></i>
            <span>Daftar ACC Mahasiswa</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="acc_riset.php">
            <i class="fas fa-tasks"></i>
            <span>Daftar ACC Riset</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Publikasi -->
    <div class="sidebar-heading">Publikasi & Informasi</div>
    <li class="nav-item">
        <a class="nav-link" href="dataset.php">
            <i class="fas fa-newspaper"></i>
            <span>Dataset</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="berita.php">
            <i class="fas fa-newspaper"></i>
            <span>Berita</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="fasilitas.php">
            <i class="fas fa-building"></i>
            <span>Fasilitas</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Manajemen Admin -->
    <div class="sidebar-heading">Manajemen Admin</div>

    <li class="nav-item">
        <a class="nav-link" href="users.php">
            <i class="fas fa-users-cog"></i>
            <span>Users</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="usermember.php">
            <i class="fas fa-users-cog"></i>
            <span>Users & Members</span>
        </a>
    </li>

        <hr class="sidebar-divider d-none d-md-block">

    <!-- Tentang Lab -->
    <div class="sidebar-heading">Tentang Lab</div>

    <li class="nav-item">
        <a class="nav-link" href="logo.php">
            <i class="fas fa-image"></i>
            <span>Logo Website</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="visimisi.php">
            <i class="fas fa-bullseye"></i>
            <span>Visi & Misi</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="../logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

</ul>
<!-- End of Sidebar -->
