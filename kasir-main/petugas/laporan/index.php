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
    <title>Laporan Penjualan - Kasir Fernando</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --color-light-blue: #BDE8F5;
            --color-medium-blue: #4988C4;
            --color-dark-blue: #1C4D8D;
            --color-navy: #0F2854;
        }

        body { 
            background: linear-gradient(135deg, var(--color-light-blue) 0%, #E8F6FB 50%, var(--color-medium-blue) 100%);
            min-height: 100vh;
        }

        .card-report { 
            border-radius: 20px; 
            border: none; 
            transition: transform 0.3s;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .card-report:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(28, 77, 141, 0.2);
        }

        .header-section {
            background: linear-gradient(135deg, var(--color-dark-blue), var(--color-navy));
            color: white;
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(189, 232, 245, 0.1);
            border-radius: 50%;
        }

        .filter-card {
            background: white;
            border-radius: 20px;
            border: 2px solid var(--color-light-blue);
            box-shadow: 0 10px 30px rgba(28, 77, 141, 0.1);
        }

        .form-control, .form-select {
            border: 2px solid var(--color-light-blue);
            border-radius: 12px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--color-medium-blue);
            box-shadow: 0 0 0 4px rgba(73, 136, 196, 0.1);
        }

        .btn-filter {
            background: linear-gradient(135deg, var(--color-medium-blue), var(--color-dark-blue));
            color: white;
            border: none;
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            background: linear-gradient(135deg, var(--color-dark-blue), var(--color-navy));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(28, 77, 141, 0.3);
        }

        .btn-reset {
            background: white;
            color: var(--color-dark-blue);
            border: 2px solid var(--color-light-blue);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            background: var(--color-light-blue);
            border-color: var(--color-medium-blue);
            transform: translateY(-2px);
        }

        .btn-print-report {
            background: var(--color-navy);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-print-report:hover {
            background: var(--color-dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 40, 84, 0.3);
        }
        
        .btn-detail-custom {
            background: #ffffff;
            border: 2px solid var(--color-light-blue) !important;
            color: var(--color-medium-blue);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 50px;
            padding: 6px 18px;
            text-decoration: none;
            display: inline-block;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-detail-custom:hover {
            background: var(--color-dark-blue) !important;
            color: #ffffff !important;
            border-color: var(--color-dark-blue) !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(28, 77, 141, 0.3);
        }

        .btn-detail-custom:hover i { 
            transform: scale(1.2); 
        }

        .stats-card {
            border-radius: 20px;
            padding: 25px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.1;
        }

        .stats-card-revenue {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .stats-card-revenue::before {
            background: white;
        }

        .stats-card-transaction {
            background: linear-gradient(135deg, var(--color-medium-blue), var(--color-dark-blue));
            color: white;
        }

        .stats-card-transaction::before {
            background: white;
        }

        .table thead th { 
            background: linear-gradient(135deg, rgba(189, 232, 245, 0.4), rgba(73, 136, 196, 0.2));
            color: var(--color-navy);
            text-transform: uppercase; 
            font-size: 0.75rem; 
            letter-spacing: 0.5px;
            font-weight: 700;
            border: none;
            padding: 15px;
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(189, 232, 245, 0.3);
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: rgba(189, 232, 245, 0.15);
            transform: scale(1.01);
        }

        @media print {
            .no-print, .sidebar, .btn-print-trigger { display: none !important; }
            .container-fluid { width: 100%; padding: 0; }
            body { background-color: white; }
            .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="no-print">
            <?php include '../../template/sidebar.php'; ?>
        </div>
        
        <div class="container-fluid p-4">
            <div class="header-section no-print position-relative">
                <div class="row align-items-center position-relative" style="z-index: 1;">
                    <div class="col-md-7">
                        <h3 class="fw-bold mb-2">
                            <i class="fas fa-chart-line me-2"></i>Laporan Penjualan
                        </h3>
                        <p class="mb-0 opacity-75">Pantau performa penjualan Kasir Fernando secara real-time</p>
                    </div>
                    <div class="col-md-5 text-md-end mt-3 mt-md-0">
                        <button class="btn btn-print-report shadow" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Cetak Laporan
                        </button>
                    </div>
                </div>
            </div>

            <div class="filter-card mb-4 no-print">
                <div class="card-body p-4">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-uppercase" style="color: var(--color-navy);">
                                <i class="fas fa-calendar-alt me-2"></i>Dari Tanggal
                            </label>
                            <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-uppercase" style="color: var(--color-navy);">
                                <i class="fas fa-calendar-check me-2"></i>Sampai Tanggal
                            </label>
                            <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-filter flex-grow-1">
                                <i class="fas fa-filter me-2"></i>Filter Data
                            </button>
                            <a href="index.php" class="btn btn-reset px-4">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <?php 
            $where = "";
            if($tgl_mulai != '' && $tgl_selesai != '') {
                $where = " WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'";
            }
            $summary = mysqli_query($conn, "SELECT SUM(TotalHarga) as total, COUNT(*) as jml FROM penjualan $where");
            $ds = mysqli_fetch_assoc($summary);
            ?>

            <div class="row mb-4 g-4">
                <div class="col-md-6">
                    <div class="stats-card stats-card-revenue shadow-sm h-100 position-relative">
                        <div class="d-flex align-items-center position-relative" style="z-index: 1;">
                            <div class="rounded-circle bg-white bg-opacity-20 p-3 me-3">
                                <i class="fas fa-wallet fa-2x"></i>
                            </div>
                            <div>
                                <small class="d-block opacity-75 fw-bold text-uppercase mb-1">Omset Periode Ini</small>
                                <h2 class="fw-bold m-0">Rp <?= number_format($ds['total'] ?? 0, 0, ',', '.'); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stats-card stats-card-transaction shadow-sm h-100 position-relative">
                        <div class="d-flex align-items-center position-relative" style="z-index: 1;">
                            <div class="rounded-circle bg-white bg-opacity-20 p-3 me-3">
                                <i class="fas fa-shopping-bag fa-2x"></i>
                            </div>
                            <div>
                                <small class="d-block opacity-75 fw-bold text-uppercase mb-1">Total Transaksi</small>
                                <h2 class="fw-bold m-0"><?= $ds['jml']; ?> <span class="fs-5 fw-normal">Pesanan</span></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-report shadow-sm">
                <div class="card-body p-0">
                    <div class="text-center py-4 d-none d-print-block">
                        <h2 class="fw-bold m-0" style="color: var(--color-navy);">LAPORAN PENJUALAN KASIR FERNANDO</h2>
                        <p class="m-0 text-muted">Periode: <?= ($tgl_mulai ?: 'Semua') ?> s/d <?= ($tgl_selesai ?: 'Sekarang') ?></p>
                        <hr class="mx-auto w-75" style="border-color: var(--color-medium-blue);">
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4" width="60">No</th>
                                    <th>Waktu Transaksi</th>
                                    <th>Nama Pelanggan</th>
                                    <th class="text-end">Total Bayar</th>
                                    <th class="no-print text-center" width="150">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $query = mysqli_query($conn, "SELECT * FROM penjualan JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID $where ORDER BY TanggalPenjualan DESC");
                                if(mysqli_num_rows($query) > 0) {
                                    while($d = mysqli_fetch_array($query)){
                                ?>
                                <tr>
                                    <td class="ps-4 text-muted fw-bold"><?= $no++; ?></td>
                                    <td>
                                        <div class="fw-bold" style="color: var(--color-navy);">
                                            <?= date('d M Y', strtotime($d['TanggalPenjualan'])); ?>
                                        </div>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i><?= date('H:i', strtotime($d['TanggalPenjualan'])); ?> WIB
                                        </small>
                                    </td>
                                    <td>
                                        <div class="text-uppercase fw-bold" style="color: var(--color-dark-blue);">
                                            <?= $d['NamaPelanggan']; ?>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-end" style="color: var(--color-medium-blue);">
                                        Rp <?= number_format($d['TotalHarga'], 0, ',', '.'); ?>
                                    </td>
                                    <td class="no-print text-center">
                                        <a href="detail.php?id=<?= $d['PenjualanID']; ?>" class="btn-detail-custom">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted'><i class='fas fa-inbox fa-3x mb-3 d-block' style='opacity: 0.3;'></i>Tidak ada data transaksi pada periode ini.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-none d-print-block mt-5 pt-4" style="border-top: 2px solid var(--color-medium-blue);">
                <div class="row">
                    <div class="col-6">
                        <p class="small text-muted mb-1">Dicetak pada:</p>
                        <p class="fw-bold" style="color: var(--color-navy);"><?= date('d/m/Y H:i'); ?> WIB</p>
                    </div>
                    <div class="col-6 text-end">
                        <p class="small text-muted mb-4">Disetujui oleh:</p>
                        <br><br>
                        <p class="fw-bold mb-0" style="color: var(--color-navy);">( ____________________ )</p>
                        <p class="small text-muted">Manajer Kasir Fernando</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>