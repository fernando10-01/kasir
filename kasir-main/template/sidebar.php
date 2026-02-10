<?php
// Mengambil nama folder aktif untuk menentukan menu mana yang 'active'
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* 1. Kunci Utama: Class untuk mematikan animasi saat pertama kali load */
    .no-transition {
        transition: none !important;
    }

    /* Desain Utama Sidebar */
    .sidebar-custom {
        background: linear-gradient(180deg, #0F2854 0%, #1C4D8D 100%);
        min-height: 100vh;
        width: 260px;
        position: fixed;
        z-index: 1000;
        box-shadow: 4px 0 15px rgba(15, 40, 84, 0.3);
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-x: hidden;
    }

    /* Keadaan Sidebar saat Mengecil */
    .sidebar-custom.collapsed {
        width: 85px;
    }

    /* Header Sidebar */
    .sidebar-header {
        padding: 25px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        white-space: nowrap;
        background: rgba(189, 232, 245, 0.1);
        border-bottom: 2px solid rgba(73, 136, 196, 0.2);
        overflow: visible; /* Agar tombol tidak terpotong */
    }

    .sidebar-header h4 {
        letter-spacing: 1px;
        font-size: 1.2rem;
        color: #BDE8F5;
        margin: 0;
        transition: opacity 0.3s;
        flex: 1; /* Membuat h4 mengambil ruang yang tersedia */
    }

    /* Hilangkan elemen teks saat collapsed */
    .sidebar-custom.collapsed .sidebar-header h4,
    .sidebar-custom.collapsed .nav-link span,
    .sidebar-custom.collapsed .btn-logout-custom span {
        display: none;
    }

    /* Tombol Toggle - DIPERBAIKI */
    #toggleSidebar {
        cursor: pointer;
        color: #BDE8F5;
        font-size: 1.3rem;
        background: none;
        border: none;
        transition: all 0.3s ease;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        flex-shrink: 0; /* Mencegah tombol menyusut */
    }
    
    #toggleSidebar:hover { 
        color: #fff; 
        background-color: rgba(73, 136, 196, 0.2);
        transform: scale(1.1); /* Efek zoom saat hover */
    }

    #toggleSidebar:active {
        transform: scale(0.95); /* Efek press saat diklik */
    }

    /* --- STYLING NAVIGASI & HOVER --- */
    .nav-pills .nav-link {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 12px;
        margin: 4px 15px;
        padding: 12px 15px;
        color: #BDE8F5 !important;
        display: flex;
        align-items: center;
        font-weight: 500;
        white-space: nowrap;
    }

    /* Efek Hover saat Lebar */
    .nav-pills .nav-link:hover:not(.active) {
        background-color: rgba(73, 136, 196, 0.2) !important;
        color: #fff !important;
        transform: translateX(8px); 
        box-shadow: 0 2px 8px rgba(189, 232, 245, 0.1);
    }

    /* Efek Hover saat Mengecil (Collapsed) */
    .sidebar-custom.collapsed .nav-link:hover:not(.active) {
        background-color: rgba(73, 136, 196, 0.3) !important;
        transform: scale(1.1); 
    }

    /* Menu Aktif */
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #4988C4 0%, #BDE8F5 100%) !important;
        color: #0F2854 !important;
        box-shadow: 0 4px 15px rgba(73, 136, 196, 0.4);
        font-weight: 600;
    }

    /* Ikon Menu */
    .nav-link i {
        min-width: 25px;
        font-size: 1.1rem;
        text-align: center;
        margin-right: 12px;
        transition: 0.3s;
    }

    .sidebar-custom.collapsed .nav-link {
        justify-content: center;
        margin: 4px 10px;
        padding: 12px 0;
    }

    .sidebar-custom.collapsed .nav-link i {
        margin-right: 0;
    }

    /* Logout */
    .logout-container {
        position: absolute;
        bottom: 30px;
        width: 100%;
        padding: 0 15px;
    }

    .btn-logout-custom {
        color: #ff4d4d;
        background: rgba(255, 77, 77, 0.15);
        border-radius: 12px;
        padding: 12px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        transition: 0.3s;
        border: 1px solid rgba(255, 77, 77, 0.3);
    }

    .btn-logout-custom:hover {
        background: #ff4d4d;
        color: #fff !important;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(255, 77, 77, 0.3);
    }

    /* Spacer Konten */
    .sidebar-spacer {
        width: 260px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
    }
    
    .sidebar-spacer.collapsed {
        width: 85px;
    }

    /* Divider Line */
    .sidebar-custom hr {
        border-color: rgba(189, 232, 245, 0.2) !important;
    }

    /* Icon Warning Color */
    .text-warning-custom {
        color: #BDE8F5 !important;
    }
</style>

<div class="sidebar-custom shadow no-transition" id="mainSidebar">
    <div class="sidebar-header">
        <h4 class="fw-bold"><i class="fas fa-cash-register me-2 text-warning-custom"></i><span>KASIR NANDO</span></h4>
        <button id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <div class="px-3"><hr class="text-secondary opacity-25"></div>
    
    <ul class="nav nav-pills flex-column mb-auto mt-2">
        <li>
            <a href="../dashboard/index.php" class="nav-link <?= ($current_dir == 'dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="../penjualan/index.php" class="nav-link <?= ($current_dir == 'penjualan') ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i> <span>Penjualan</span>
            </a>
        </li>
        <li>
            <a href="../produk/index.php" class="nav-link <?= ($current_dir == 'produk') ? 'active' : ''; ?>">
                <i class="fas fa-boxes"></i> <span>Data Produk</span>
            </a>
        </li>
        <li>
            <a href="../pelanggan/index.php" class="nav-link <?= ($current_dir == 'pelanggan') ? 'active' : ''; ?>">
                <i class="fas fa-user-tag"></i> <span>Data Pelanggan</span>
            </a>
        </li>
        <?php if($_SESSION['role'] == 'admin'): ?>
        <li>
            <a href="../petugas/index.php" class="nav-link <?= ($current_dir == 'petugas') ? 'active' : ''; ?>">
                <i class="fas fa-users-cog"></i> <span>Registrasi</span>
            </a>
        </li>
        <?php endif; ?>
        <li>
            <a href="../laporan/index.php" class="nav-link <?= ($current_dir == 'laporan') ? 'active' : ''; ?>">
                <i class="fas fa-file-invoice-dollar"></i> <span>Laporan</span>
            </a>
        </li>
    </ul>

    <div class="logout-container">
        <a href="../../auth/logout.php" class="btn-logout-custom" onclick="return confirm('Yakin ingin keluar?')">
            <i class="fas fa-power-off me-2"></i> <span>Keluar</span>
        </a>
    </div>
</div>

<div class="sidebar-spacer d-none d-md-block no-transition" id="mainSpacer"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const sidebar = $("#mainSidebar");
    const spacer = $("#mainSpacer");
    const btn = $("#toggleSidebar");

    if (localStorage.getItem("sidebar_collapsed") === "true") {
        sidebar.addClass("collapsed");
        spacer.addClass("collapsed");
    }

    setTimeout(function() {
        sidebar.removeClass("no-transition");
        spacer.removeClass("no-transition");
    }, 50);

    btn.click(function() {
        sidebar.toggleClass("collapsed");
        spacer.toggleClass("collapsed");
        
        const isCollapsed = sidebar.hasClass("collapsed");
        localStorage.setItem("sidebar_collapsed", isCollapsed);
    });
});
</script>