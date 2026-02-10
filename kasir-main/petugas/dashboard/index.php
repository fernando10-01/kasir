<?php 
session_start();
include '../../main/connect.php';

// Proteksi Halaman: Pastikan yang masuk adalah Petugas
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'petugas') {
    header("location:../../admin/dashboard/index.php");
}

$username = $_SESSION['username'];
date_default_timezone_set('Asia/Jakarta');
$tgl_hari_ini = date('Y-m-d');

// --- DATA LOGIC ---
$query_trx = mysqli_query($conn, "SELECT COUNT(*) as total FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
$data_trx = mysqli_fetch_assoc($query_trx);

$query_harian = mysqli_query($conn, "SELECT SUM(TotalHarga) as total_hari FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
$data_harian = mysqli_fetch_assoc($query_harian);
$total_hari = $data_harian['total_hari'] ?? 0;

$stok_low = mysqli_query($conn, "SELECT COUNT(*) as limit_stok FROM produk WHERE Stok < 10");
$d_stok = mysqli_fetch_assoc($stok_low);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - Kasir Fernando</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --color-light-blue: #BDE8F5;
            --color-medium-blue: #4988C4;
            --color-dark-blue: #1C4D8D;
            --color-navy: #0F2854;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(73, 136, 196, 0.2);
        }

        body { 
            background: linear-gradient(135deg, var(--color-light-blue) 0%, #E8F6FB 50%, var(--color-medium-blue) 100%);
            min-height: 100vh;
            color: var(--color-navy); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(28, 77, 141, 0.1);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(28, 77, 141, 0.15);
        }

        .welcome-box { 
            background: linear-gradient(135deg, var(--color-dark-blue), var(--color-navy)); 
            color: white; 
            border-radius: 24px;
            position: relative;
            overflow: hidden;
        }

        .welcome-box::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(189, 232, 245, 0.1);
            border-radius: 50%;
        }

        .icon-circle {
            width: 55px; 
            height: 55px;
            display: flex; 
            align-items: center; 
            justify-content: center;
            border-radius: 18px;
        }

        .quick-action-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            color: var(--color-navy);
            border: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .quick-action-card:hover {
            transform: translateY(-8px);
            border-color: var(--color-medium-blue);
            color: var(--color-dark-blue);
            box-shadow: 0 10px 25px rgba(73, 136, 196, 0.3);
        }

        .status-pulse {
            width: 10px; 
            height: 10px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .btn-hover-premium {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid rgba(189, 232, 245, 0.5) !important;
            background: white !important;
            color: var(--color-dark-blue) !important;
        }

        .btn-hover-premium:hover {
            background: var(--color-medium-blue) !important;
            color: #ffffff !important;
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 10px 25px rgba(73, 136, 196, 0.4) !important;
            border-color: var(--color-dark-blue) !important;
        }

        .btn-hover-premium:hover i {
            animation: bounce 0.5s infinite alternate;
        }

        @keyframes bounce {
            from { transform: translateY(0); }
            to { transform: translateY(-3px); }
        }

        .table thead th {
            background-color: rgba(189, 232, 245, 0.3);
            color: var(--color-navy);
            font-weight: 600;
        }

        .table tbody tr:hover {
            background-color: rgba(189, 232, 245, 0.1);
        }

        .badge-time {
            background: linear-gradient(135deg, var(--color-light-blue), var(--color-medium-blue));
            color: var(--color-navy);
            font-weight: 600;
        }

        .btn-struk {
            background: white;
            border: 2px solid var(--color-light-blue);
            color: var(--color-dark-blue);
            transition: all 0.3s ease;
        }

        .btn-struk:hover {
            background: var(--color-dark-blue);
            color: white;
            border-color: var(--color-dark-blue);
            transform: translateY(-2px);
        }

        .tips-card {
            background: linear-gradient(135deg, rgba(189, 232, 245, 0.3), rgba(73, 136, 196, 0.2));
            border: 2px solid var(--color-light-blue);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="container-fluid p-4">
            <div class="welcome-box p-4 p-md-5 mb-4 shadow-lg border-0 position-relative">
                <div class="row align-items-center position-relative" style="z-index: 1;">
                    <div class="col-md-7">
                        <span class="badge bg-white bg-opacity-25 rounded-pill px-3 py-2 mb-3">
                            <span class="status-pulse"></span> Sesi Petugas Aktif
                        </span>
                        <h2 class="fw-bold mb-1">Selamat Bekerja, <?= strtoupper($username); ?>! 👋</h2>
                        <p class="opacity-75 mb-0">Siap melayani pelanggan dengan senyuman di Kasir Fernando hari ini?</p>
                    </div>
                    <div class="col-md-5 text-md-end mt-3 mt-md-0">
                       <a href="../penjualan/index.php" class="btn btn-hover-premium fw-bold py-3 px-4 rounded-4 shadow-sm">
                            <i class="fas fa-cart-plus me-2"></i> MULAI TRANSAKSI BARU
                       </a>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="glass-card p-4 h-100">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle me-3" style="background: linear-gradient(135deg, var(--color-medium-blue), var(--color-dark-blue)); color: white;">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-bold d-block">TRANSAKSI HARI INI</small>
                                <h3 class="fw-bold mb-0" style="color: var(--color-dark-blue);"><?= $data_trx['total']; ?> <small class="fs-6 fw-normal">Nota</small></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass-card p-4 h-100">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle me-3" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-bold d-block">TOTAL PENJUALAN</small>
                                <h3 class="fw-bold mb-0 text-success">Rp <?= number_format($total_hari, 0, ',', '.'); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass-card p-4 h-100">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle me-3" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-bold d-block">PERLU RESTOCK</small>
                                <h3 class="fw-bold mb-0 text-warning"><?= $d_stok['limit_stok']; ?> <small class="fs-6 fw-normal">Produk</small></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="glass-card border-0 h-100">
                        <div class="p-4 d-flex justify-content-between align-items-center" style="border-bottom: 2px solid var(--color-light-blue);">
                            <h5 class="fw-bold m-0" style="color: var(--color-dark-blue);">
                                <i class="fas fa-history me-2"></i>Riwayat Shift Ini
                            </h5>
                            <span class="small text-muted fw-bold"><?= date('d F Y'); ?></span>
                        </div>
                        <div class="p-4">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr class="small">
                                            <th>WAKTU</th>
                                            <th>PELANGGAN</th>
                                            <th>TOTAL</th>
                                            <th class="text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $log = mysqli_query($conn, "SELECT * FROM penjualan 
                                               JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                               WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'
                                               ORDER BY PenjualanID DESC LIMIT 5");
                                        
                                        if(mysqli_num_rows($log) == 0): ?>
                                            <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada penjualan hari ini. Ayo mulai jualan! 🚀</td></tr>
                                        <?php endif;
                                        while($l = mysqli_fetch_array($log)): ?>
                                        <tr>
                                            <td><span class="badge badge-time"><?= date('H:i', strtotime($l['TanggalPenjualan'])); ?></span></td>
                                            <td class="fw-bold" style="color: var(--color-navy);"><?= $l['NamaPelanggan']; ?></td>
                                            <td class="fw-bold" style="color: var(--color-medium-blue);">Rp <?= number_format($l['TotalHarga']); ?></td>
                                            <td class="text-center">
                                                <a href="../penjualan/detail.php?id=<?= $l['PenjualanID']; ?>" class="btn btn-sm btn-struk rounded-pill shadow-sm">
                                                    <i class="fas fa-print me-1"></i> Struk
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <h5 class="fw-bold mb-2" style="color: var(--color-navy);">Navigasi Cepat</h5>
                        </div>
                        <div class="col-6">
                            <a href="../penjualan/index.php" class="quick-action-card d-block shadow-sm">
                                <i class="fas fa-calculator fa-2x mb-2" style="color: var(--color-medium-blue);"></i>
                                <div class="small fw-bold">Kasir</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="../produk/index.php" class="quick-action-card d-block shadow-sm">
                                <i class="fas fa-search fa-2x mb-2 text-success"></i>
                                <div class="small fw-bold">Cek Stok</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="../laporan/index.php" class="quick-action-card d-block shadow-sm">
                                <i class="fas fa-file-pdf fa-2x mb-2 text-info"></i>
                                <div class="small fw-bold">Laporan</div>
                            </a>
                        </div>
                        <div class="col-6">
                          <a href="javascript:void(0)" onclick="confirmLogout()" class="quick-action-card d-block shadow-sm text-danger">
                            <i class="fas fa-sign-out-alt fa-2x mb-2"></i>
                            <div class="small fw-bold">Selesai</div>
                          </a>
                        </div>
                    </div>
                    
                    <div class="glass-card tips-card mt-4 p-4 border-0">
                        <h6 class="fw-bold" style="color: var(--color-dark-blue);">
                            <i class="fas fa-lightbulb me-2"></i>Tips Pelayanan Fernando
                        </h6>
                        <p class="small text-muted mb-0">Pastikan stok barang di rak selalu terisi sebelum jam ramai pengunjung tiba.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
function confirmLogout() {
    Swal.fire({
        title: 'Selesaikan Shift?',
        text: "Pastikan semua transaksi hari ini sudah tersimpan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1C4D8D',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        background: 'rgba(255, 255, 255, 0.95)',
        backdrop: `rgba(28, 77, 141, 0.1)`,
        customClass: {
            popup: 'rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "../../auth/logout.php";
        }
    })
}
</script>
</body>
</html>