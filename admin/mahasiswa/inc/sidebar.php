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

    <!-- List ACC -->
    <div class="sidebar-heading">Persetujuan Bimbingan</div>
    <li class="nav-item">
        <a class="nav-link" href="listacc.php">
            <i class="fas fa-tasks"></i>
            <span>Daftar ACC Riset</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Dataset & fasilitas -->
    <div class="sidebar-heading">Publikasi & Informasi</div>
    <li class="nav-item">
        <a class="nav-link" href="dataset.php">
            <i class="fas fa-newspaper"></i>
            <span>Dataset</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="fasilitas.php">
            <i class="fas fa-building"></i>
            <span>Fasilitas</span>
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
