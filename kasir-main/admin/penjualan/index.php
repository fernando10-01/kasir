<?php 
session_start();
include '../../main/connect.php';

// Proteksi Halaman
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'admin') header("location:../../petugas/dashboard/index.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penjualan - Kasir Fernando</title>
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
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(15, 40, 84, 0.06);
        }

        .table-glass-container {
            background: rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            overflow: hidden;
        }

        .table thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: #64748b;
            background: rgba(255,255,255,0.4);
            border-bottom: 2px solid var(--accent-color);
            padding: 1.2rem 1rem;
        }

        .form-control-custom {
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 0.6rem 1rem;
            font-weight: 600;
        }

        .form-control-custom:focus {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 0 4px rgba(73, 136, 196, 0.15);
            border-color: var(--secondary-color);
        }

        .btn-modern {
            border-radius: 12px;
            padding: 0.6rem 1.2rem;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(28, 77, 141, 0.2);
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            body::before { display: none; }
            .glass-card { border: none; box-shadow: none; background: white; }
            .table-glass-container { border: 1px solid #dee2e6; }
        }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="no-print">
        <?php include '../../template/sidebar.php'; ?>
    </div>

    <div class="container-fluid p-4">
        
        <header class="row mb-4 pt-3 no-print">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0" style="color: var(--dark-color);">Data Penjualan</h3>
                    <p class="text-muted small">Kelola dan pantau riwayat transaksi toko Anda</p>
                </div>
                <?php if(isset($_GET['tgl_mulai'])): ?>
                <button onclick="window.print()" class="btn btn-modern" style="background: var(--dark-color); color: white; border: none;">
                    <i class="fas fa-print me-2"></i>Cetak Laporan
                </button>
                <?php endif; ?>
            </div>
        </header>

        <div class="glass-card p-4 mb-4 no-print shadow-sm">
            <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px; color: #64748b;">
                <i class="fas fa-filter me-2"></i>Filter Periode Penjualan
            </h6>
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-bold mb-2">Mulai Tanggal</label>
                    <input type="date" name="tgl_mulai" class="form-control form-control-custom" value="<?= $_GET['tgl_mulai'] ?? ''; ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold mb-2">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" class="form-control form-control-custom" value="<?= $_GET['tgl_selesai'] ?? ''; ?>" required>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-modern flex-grow-1" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border: none;">
                        Terapkan Filter
                    </button>
                    <a href="index.php" class="btn btn-light btn-modern border">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </form>
        </div>

        <div class="glass-card shadow-sm">
            <?php 
            $tgl_mulai = $_GET['tgl_mulai'] ?? '';
            $tgl_selesai = $_GET['tgl_selesai'] ?? '';

            if ($tgl_mulai != '' && $tgl_selesai != '') {
                $query_str = "SELECT * FROM penjualan 
                              JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                              WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'
                              ORDER BY PenjualanID DESC";
                echo "<div class='p-4 pb-0 no-print'>
                        <div class='alert border-0 rounded-4 small fw-bold mb-0' style='background-color: rgba(28, 77, 141, 0.1); color: var(--primary-color);'>
                            <i class='fas fa-calendar-check me-2'></i>Laporan Penjualan: $tgl_mulai s/d $tgl_selesai
                        </div>
                      </div>";
            } else {
                $query_str = "SELECT * FROM penjualan 
                              JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                              ORDER BY PenjualanID DESC";
            }
            $sql = mysqli_query($conn, $query_str);
            ?>

            <div class="p-4">
                <div class="table-glass-container">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">No. Nota</th>
                                <th>Tanggal & Waktu</th>
                                <th>Pelanggan</th>
                                <th class="text-end">Total Bayar</th>
                                <th class="text-center no-print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(mysqli_num_rows($sql) == 0){
                                echo "<tr><td colspan='5' class='text-center py-5 text-muted'>
                                        <i class='fas fa-search-minus d-block mb-3 fa-3x opacity-25'></i>
                                        <p class='fw-bold mb-0'>Data tidak ditemukan</p>
                                        <small>Coba sesuaikan tanggal filter Anda</small>
                                      </td></tr>";
                            }
                            while($d = mysqli_fetch_array($sql)){
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="badge rounded-pill px-3 py-2 fw-bold" style="background-color: rgba(15, 40, 84, 0.1); color: var(--dark-color);">
                                        #<?= $d['PenjualanID']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold" style="font-size: 0.9rem; color: var(--dark-color);"><?= date('d M Y', strtotime($d['TanggalPenjualan'])); ?></div>
                                    <div class="text-muted small"><?= date('H:i', strtotime($d['TanggalPenjualan'])); ?> WIB</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;"><?= $d['NamaPelanggan']; ?></div>
                                    <div class="text-muted" style="font-size: 0.7rem;">ID: CL-<?= $d['PelangganID']; ?></div>
                                </td>
                                <td class="text-end fw-extrabold text-primary-custom">
                                    Rp <?= number_format($d['TotalHarga'], 0, ',', '.'); ?>
                                </td>
                                <td class="text-center no-print">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="detail.php?id=<?= $d['PenjualanID']; ?>" class="btn btn-sm btn-light border rounded-3 shadow-sm px-3">
                                            <i class="fas fa-eye text-primary-custom"></i>
                                        </a>
                                        <a href="hapus.php?id=<?= $d['PenjualanID']; ?>" class="btn btn-sm btn-light border rounded-3 shadow-sm px-3" 
                                           onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-none d-print-block mt-4 text-end">
            <p class="small text-muted">Dicetak pada: <?= date('d/m/Y H:i'); ?></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>