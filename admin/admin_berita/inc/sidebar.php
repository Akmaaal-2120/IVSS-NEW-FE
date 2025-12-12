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
                <img src="../assets/img/<?= htmlspecialchars($logo_src) ?>" alt="Logo IVSS"style="width:40px; height:40px; object-fit:cover; border-radius:6px;">
            <?php else: ?>
                <div style="width:40px; height:40px; background:#ccc; border-radius:6px;"></div>
            <?php endif; ?>
        </div>
        <div class="sidebar-brand-text mx-3">Lab IVSS Admin</div>
    </a>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="index.php">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Data Berita -->
    <li class="nav-item">
        <a class="nav-link" href="berita.php">
            <i class="fas fa-newspaper"></i>
            <span>Data Berita</span>
        </a>
    </li>

    <!-- Tambah Berita -->
    <li class="nav-item">
        <a class="nav-link" href="tambah_berita.php">
            <i class="fas fa-plus"></i>
            <span>Tambah Berita</span>
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
