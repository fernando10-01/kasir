<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

// Logika Filter Tanggal
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Kasir Fernando</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(189, 232, 245, 0.5);
            --primary-color: #1C4D8D;
            --secondary-color: #4988C4;
            --accent-color: #BDE8F5;
            --dark-color: #0F2854;
            --report-gradient: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        body { 
            background: radial-gradient(circle at top right, var(--accent-color), var(--secondary-color)), 
                        linear-gradient(135deg, #BDE8F5 0%, #4988C4 100%);
            min-height: 100vh;
            color: var(--dark-color); 
            font-family: 'Plus Jakarta Sans', sans-serif;
            position: relative;
        }

        /* Dot Pattern Background */
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(var(--secondary-color) 0.5px, transparent 0.5px);
            background-size: 24px 24px;
            opacity: 0.1;
            z-index: -1;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(15, 40, 84, 0.08);
        }

        .stat-card {
            border: none;
            border-radius: 20px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .form-control-custom {
            border-radius: 12px;
            border: 1px solid var(--accent-color);
            padding: 0.6rem 1rem;
        }

        .form-control-custom:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(73, 136, 196, 0.1);
        }

        /* Print Optimization */
        @media print {
            .no-print, .sidebar-wrapper, .btn, .glass-card:not(.report-main) { 
                display: none !important; 
            }
            body { background: white !important; }
            body::before { display: none; }
            .container-fluid { width: 100%; margin: 0; padding: 0; }
            .report-main { 
                box-shadow: none !important; 
                border: none !important;
                background: white !important;
            }
        }

        .transition-all { transition: all 0.3s ease; }

        /* Efek Hover untuk Tombol Filter */
        .btn-filter-custom {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            background: var(--primary-color);
            border: none;
        }

        .btn-filter-custom:hover {
            background: var(--dark-color) !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(28, 77, 141, 0.4);
        }

        .btn-filter-custom:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .btn-filter-custom:hover i {
            animation: rotateFilter 0.5s ease;
        }

        @keyframes rotateFilter {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(15deg); }
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .text-dark-custom {
            color: var(--dark-color) !important;
        }

        .border-primary-custom {
            border-color: var(--primary-color) !important;
        }

        .border-success-custom {
            border-color: #27ae60 !important;
        }

        .bg-primary-light {
            background-color: rgba(28, 77, 141, 0.1) !important;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="no-print sidebar-wrapper">
            <?php include '../../template/sidebar.php'; ?>
        </div>
        
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                <div>
                    <h3 class="fw-bold mb-0 text-dark-custom">Laporan Penjualan</h3>
                    <p class="text-muted small mb-0">Pantau performa bisnis dan cetak arsip</p>
                </div>
                <button class="btn fw-bold px-4 py-2 shadow-sm transition-all" style="border-radius: 12px; background: var(--dark-color); color: white; border: none;" onclick="window.print()">
                    <i class="fas fa-print me-2 text-warning"></i>Cetak Laporan
                </button>
            </div>

            <div class="glass-card mb-4 no-print">
                <div class="card-body p-4">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted uppercase">Dari Tanggal</label>
                            <input type="date" name="tgl_mulai" class="form-control form-control-custom" value="<?= $tgl_mulai ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted uppercase">Sampai Tanggal</label>
                            <input type="date" name="tgl_selesai" class="form-control form-control-custom" value="<?= $tgl_selesai ?>">
                        </div>
                        <div class="col-md-6 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1 fw-bold btn-filter-custom" style="border-radius: 12px;">
                                <i class="fas fa-filter me-2"></i>Terapkan Filter
                            </button>
                            <a href="index.php" class="btn btn-outline-secondary px-4 fw-bold transition-all" style="border-radius: 12px; border-color: var(--secondary-color); color: var(--secondary-color);">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mb-4">
                <?php 
                $where = "";
                if($tgl_mulai != '' && $tgl_selesai != '') {
                    $where = " WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'";
                }
                $summary = mysqli_query($conn, "SELECT SUM(TotalHarga) as total, COUNT(*) as jml FROM penjualan $where");
                $ds = mysqli_fetch_assoc($summary);
                ?>
                <div class="col-md-6 mb-3">
                    <div class="stat-card glass-card p-4 border-start border-success-custom border-5">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-muted fw-bold d-block mb-1">TOTAL OMSET</small>
                                <h2 class="fw-bold text-success m-0">Rp <?= number_format($ds['total'] ?? 0, 0, ',', '.'); ?></h2>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-wallet fa-lg text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="stat-card glass-card p-4 border-start border-primary-custom border-5">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-muted fw-bold d-block mb-1">VOLUME TRANSAKSI</small>
                                <h2 class="fw-bold text-primary-custom m-0"><?= $ds['jml']; ?> <span class="small fw-normal">Selesai</span></h2>
                            </div>
                            <div class="bg-primary-light p-3 rounded-circle">
                                <i class="fas fa-shopping-cart fa-lg text-primary-custom"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card report-main">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="text-center mb-5 d-none d-print-block">
                        <h1 class="fw-bold m-0 text-dark-custom">LAPORAN PENJUALAN</h1>
                        <h4 class="fw-light text-primary-custom">KASIR FERNANDO SYSTEM</h4>
                        <div class="mt-3 mx-auto" style="width: 100px; height: 3px; background: var(--primary-gradient);"></div>
                        <?php if($tgl_mulai != ''): ?>
                            <p class="mt-3 mb-0 fw-bold">Periode: <?= date('d M Y', strtotime($tgl_mulai)) ?> — <?= date('d M Y', strtotime($tgl_selesai)) ?></p>
                        <?php else: ?>
                            <p class="mt-3 mb-0 fw-bold">Periode: Semua Waktu</p>
                        <?php endif; ?>
                        <small class="text-muted">Generate Date: <?= date('d/m/Y H:i'); ?></small>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-white">
                                <tr class="text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">
                                    <th class="py-3">NO</th>
                                    <th class="py-3">TANGGAL</th>
                                    <th class="py-3">NAMA PELANGGAN</th>
                                    <th class="py-3 text-end">TOTAL BAYAR</th>
                                    <th class="no-print text-center py-3">DETAIL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $query = mysqli_query($conn, "SELECT * FROM penjualan 
                                         JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                         $where ORDER BY TanggalPenjualan DESC");
                                
                                if(mysqli_num_rows($query) == 0) {
                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted'>Tidak ada data transaksi ditemukan.</td></tr>";
                                }

                                while($d = mysqli_fetch_array($query)){
                                ?>
                                <tr class="border-bottom border-light">
                                    <td class="fw-bold text-muted small"><?= str_pad($no++, 2, "0", STR_PAD_LEFT); ?></td>
                                    <td>
                                        <div class="fw-bold text-dark-custom" style="font-size: 0.9rem;"><?= date('d M Y', strtotime($d['TanggalPenjualan'])); ?></div>
                                        <small class="text-muted" style="font-size: 0.7rem;"><?= date('H:i', strtotime($d['TanggalPenjualan'])); ?> WIB</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                                <i class="fas fa-user-circle text-muted"></i>
                                            </div>
                                            <span class="fw-600"><?= $d['NamaPelanggan']; ?></span>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-dark-custom">Rp <?= number_format($d['TotalHarga'], 0, ',', '.'); ?></span>
                                    </td>
                                    <td class="no-print text-center">
                                        <a href="detail.php?id=<?= $d['PenjualanID']; ?>" class="btn btn-sm btn-light border shadow-sm px-3 transition-all" style="border-radius: 8px; border-color: var(--accent-color) !important;">
                                            <i class="fas fa-search-dollar me-1"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-none d-print-block mt-5">
                        <div class="row">
                            <div class="col-8"></div>
                            <div class="col-4 text-center">
                                <p class="mb-5">Dicetak Oleh,</p>
                                <br><br>
                                <h6 class="fw-bold border-top border-dark d-inline-block pt-2 px-4"><?= $_SESSION['Username'] ?? 'Administrator'; ?></h6>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>