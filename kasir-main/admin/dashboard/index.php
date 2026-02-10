<?php 
session_start();
if($_SESSION['status'] != "login"){
    header("location:../../auth/login.php?pesan=belum_login");
}
include '../../main/connect.php';

$tgl_hari_ini = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Kasir Fernando</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(189, 232, 245, 0.4);
            --primary-color: #1C4D8D;
            --secondary-color: #4988C4;
            --accent-color: #BDE8F5;
            --dark-color: #0F2854;
        }

        body { 
            background: radial-gradient(circle at top right, var(--accent-color), var(--secondary-color)), 
                        linear-gradient(135deg, #BDE8F5 0%, #4988C4 100%);
            min-height: 100vh;
            color: var(--dark-color); 
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            transition: all 0.4s ease;
        }

        .glass-card:hover { transform: translateY(-5px); }

        .icon-box {
            width: 50px; height: 50px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            color: white;
        }

        .gradient-blue { background: linear-gradient(45deg, var(--secondary-color), var(--accent-color)); }
        .gradient-green { background: linear-gradient(45deg, #2ecc71, #27ae60); }
        .gradient-orange { background: linear-gradient(45deg, var(--primary-color), var(--secondary-color)); }

        .table-glass-container {
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            overflow: hidden;
        }

        .btn-quick {
            background: white;
            border: 1px solid rgba(28, 77, 141, 0.1);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: 0.3s;
            text-decoration: none;
            color: var(--dark-color);
        }

        .btn-quick:hover {
            background: var(--primary-color);
            color: white !important;
            box-shadow: 0 10px 20px rgba(28, 77, 141, 0.3);
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .bg-primary-custom {
            background-color: var(--secondary-color) !important;
        }

        .badge-primary-custom {
            background-color: rgba(73, 136, 196, 0.1) !important;
            color: var(--primary-color) !important;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include '../../template/sidebar.php'; ?>

    <div class="container-fluid p-4">
        
        <header class="row mb-4">
            <div class="col-md-8">
                <h3 class="fw-bold mb-0" style="color: var(--dark-color);">Ringkasan Sistem</h3>
                <p class="text-muted">Selamat bertugas, sistem berjalan normal hari ini.</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="glass-card d-inline-block px-4 py-3 shadow-sm border-0">
                    <div class="d-flex align-items-center">
                        <div class="text-end me-3">
                            <div class="fw-bold" style="color: var(--dark-color);"><?= $_SESSION['username']; ?></div>
                            <small class="fw-bold text-uppercase text-primary-custom" style="font-size: 0.6rem;"><?= $_SESSION['role']; ?></small>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['username']; ?>&background=1C4D8D&color=fff" class="rounded-circle" width="45">
                    </div>
                </div>
            </div>
        </header>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="glass-card p-4 shadow-sm border-0">
                    <div class="icon-box gradient-green mb-3"><i class="fas fa-wallet"></i></div>
                    <div class="text-muted small fw-bold text-uppercase">Omzet Hari Ini</div>
                    <?php 
                        $q_omset = mysqli_query($conn, "SELECT SUM(TotalHarga) as total FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
                        $d_omset = mysqli_fetch_assoc($q_omset);
                    ?>
                    <h2 class="fw-bold mb-0" style="color: var(--dark-color);">Rp <?= number_format($d_omset['total'] ?? 0); ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4 shadow-sm border-0">
                    <div class="icon-box gradient-blue mb-3"><i class="fas fa-shopping-basket"></i></div>
                    <div class="text-muted small fw-bold text-uppercase">Barang Terjual</div>
                    <?php 
                        $q_pdk = mysqli_query($conn, "SELECT SUM(JumlahProduk) as terjual FROM detailpenjualan 
                                                      JOIN penjualan ON detailpenjualan.PenjualanID = penjualan.PenjualanID 
                                                      WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
                        $d_pdk = mysqli_fetch_assoc($q_pdk);
                    ?>
                    <h2 class="fw-bold mb-0" style="color: var(--dark-color);"><?= number_format($d_pdk['terjual'] ?? 0); ?> <small class="fs-6 fw-normal">Unit</small></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4 shadow-sm border-0">
                    <div class="icon-box gradient-orange mb-3"><i class="fas fa-user-tag"></i></div>
                    <div class="text-muted small fw-bold text-uppercase">Pelanggan Baru</div>
                    <?php 
                        $q_plg = mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggan");
                    ?>
                    <h2 class="fw-bold mb-0" style="color: var(--dark-color);"><?= $q_plg ? mysqli_fetch_assoc($q_plg)['total'] : 0; ?> <small class="fs-6 fw-normal">Member</small></h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="table-glass-container h-100">
                    <div class="p-4 bg-white bg-opacity-50 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold m-0" style="color: var(--dark-color);"><i class="fas fa-fire text-danger me-2"></i>Produk Paling Laris</h5>
                        <a href="../laporan/index.php" class="btn btn-sm fw-bold border-0" style="background-color: var(--secondary-color); color: white;">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="small text-muted text-uppercase">
                                    <th class="ps-4">Produk</th>
                                    <th class="text-center">Total Terjual</th>
                                    <th class="text-end pe-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $best = mysqli_query($conn, "SELECT NamaProduk, SUM(JumlahProduk) as total, SUM(Subtotal) as uang 
                                                            FROM detailpenjualan JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                                            GROUP BY detailpenjualan.ProdukID ORDER BY total DESC LIMIT 5");
                                while($b = mysqli_fetch_assoc($best)): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold" style="color: var(--dark-color);"><?= $b['NamaProduk']; ?></div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-primary-custom rounded-pill px-3"><?= $b['total']; ?> Pcs</span>
                                    </td>
                                    <td class="text-end pe-4 fw-bold" style="color: var(--dark-color);">Rp <?= number_format($b['uang']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <h5 class="fw-bold mb-3" style="color: var(--dark-color);">Akses Cepat</h5>
                <div class="row g-3">
                    <div class="col-6">
                        <a href="../penjualan/index.php" class="btn-quick d-block shadow-sm">
                            <i class="fas fa-cart-plus fa-2x mb-3 text-primary-custom"></i>
                            <div class="small fw-bold">Kasir</div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="../produk/index.php" class="btn-quick d-block shadow-sm">
                            <i class="fas fa-plus-square fa-2x mb-3 text-success"></i>
                            <div class="small fw-bold">Stok</div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="../laporan/index.php" class="btn-quick d-block shadow-sm">
                            <i class="fas fa-file-pdf fa-2x mb-3 text-danger"></i>
                            <div class="small fw-bold">Laporan</div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="../petugas/index.php" class="btn-quick d-block shadow-sm">
                            <i class="fas fa-user-cog fa-2x mb-3 text-warning"></i>
                            <div class="small fw-bold">User</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>