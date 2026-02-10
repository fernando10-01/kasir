<?php 
session_start();
include '../../main/connect.php';

// Proteksi: Hanya Admin yang boleh masuk
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'admin') header("location:../../petugas/dashboard/index.php");

$id = $_GET['id'];

// Ambil data penjualan & pelanggan
$query = mysqli_query($conn, "SELECT * FROM penjualan 
          JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
          WHERE PenjualanID = '$id'");
$data = mysqli_fetch_array($query);

// Jika ID tidak ditemukan, kembalikan ke index
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi Admin #<?= $id; ?> - Kasir Fernando</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(189, 232, 245, 0.5);
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
            box-shadow: 0 15px 35px rgba(15, 40, 84, 0.08);
        }

        .table-glass-container {
            background: rgba(255, 255, 255, 0.5);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--accent-color);
        }

        .nota-header {
            border-bottom: 2px dashed var(--accent-color);
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }

        .btn-modern {
            border-radius: 12px;
            padding: 0.6rem 1.2rem;
            font-weight: 700;
            transition: all 0.3s;
            background: var(--dark-color);
            color: white;
            border: none;
        }

        .btn-modern:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(28, 77, 141, 0.3);
        }

        .total-row {
            background: rgba(189, 232, 245, 0.15);
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .bg-primary-light {
            background-color: rgba(28, 77, 141, 0.1) !important;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            body::before { display: none; }
            .glass-card { border: 1px solid #eee; box-shadow: none; background: white; border-radius: 0; }
            .container-fluid { padding: 0 !important; }
            .col-md-8 { width: 100% !important; }
            .nota-header { border-bottom: 2px dashed #000; }
        }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="no-print">
        <?php include '../../template/sidebar.php'; ?>
    </div>

    <div class="container-fluid p-4">
        <div class="row mb-4 no-print">
            <div class="col-md-8 mx-auto d-flex justify-content-between align-items-center">
                <a href="index.php" class="text-decoration-none text-muted fw-bold small">
                    <i class="fas fa-arrow-left me-2"></i>KEMBALI KE RIWAYAT
                </a>
                <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: rgba(28, 77, 141, 0.1); color: var(--primary-color);">
                    ID TRANSAKSI: #<?= $id; ?>
                </span>
            </div>
        </div>

        <div class="glass-card col-md-8 mx-auto overflow-hidden">
            <div style="height: 6px; background: linear-gradient(to right, var(--primary-color), var(--secondary-color));"></div>
            
            <div class="card-body p-5">
                <div class="nota-header text-center">
                    <h3 class="fw-extrabold mb-1" style="letter-spacing: -1px; color: var(--dark-color);">KASIR FERNANDO</h3>
                    <p class="text-muted small mb-0 fw-bold">LAPORAN RINCIAN TRANSAKSI RESMI</p>
                    <div class="mt-3 d-flex justify-content-center gap-4">
                        <div class="small text-start">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">DITERBITKAN UNTUK:</span>
                            <span class="fw-bold text-uppercase" style="color: var(--dark-color);"><?= $data['NamaPelanggan']; ?></span>
                        </div>
                        <div class="small text-start border-start ps-4">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">TANGGAL & WAKTU:</span>
                            <span class="fw-bold" style="color: var(--dark-color);"><?= date('d M Y, H:i', strtotime($data['TanggalPenjualan'])); ?> WIB</span>
                        </div>
                    </div>
                </div>

                <div class="table-glass-container mb-4">
                    <table class="table table-borderless align-middle mb-0">
                        <thead style="background-color: rgba(73, 136, 196, 0.1);">
                            <tr class="text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <th class="ps-4 py-3">NAMA PRODUK</th>
                                <th class="text-center py-3">HARGA</th>
                                <th class="text-center py-3">QTY</th>
                                <th class="text-end pe-4 py-3">SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $detail = mysqli_query($conn, "SELECT * FROM detailpenjualan 
                                      JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                      WHERE PenjualanID = '$id'");
                            while($d = mysqli_fetch_array($detail)){
                            ?>
                            <tr class="border-bottom border-light">
                                <td class="ps-4 py-3">
                                    <span class="fw-bold" style="color: var(--dark-color);"><?= $d['NamaProduk']; ?></span>
                                    <small class="d-block text-muted" style="font-size: 0.65rem;">ID: #<?= $d['ProdukID']; ?></small>
                                </td>
                                <td class="text-center text-muted">Rp <?= number_format($d['Harga']); ?></td>
                                <td class="text-center fw-bold"><?= $d['JumlahProduk']; ?></td>
                                <td class="text-end pe-4 fw-bold" style="color: var(--dark-color);">Rp <?= number_format($d['Subtotal']); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3" class="text-end py-4 fw-bold text-muted">TOTAL KESELURUHAN</td>
                                <td class="text-end pe-4 py-4 fw-extrabold text-primary-custom h4 mb-0">
                                    Rp <?= number_format($data['TotalHarga']); ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="p-3 rounded-4 bg-light border-start border-4" style="border-color: var(--primary-color) !important;">
                            <p class="small text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i> 
                                Simpan nota ini sebagai bukti transaksi yang sah. Data ini tercatat otomatis dalam sistem inventaris.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-5 text-end no-print">
                        <button onclick="window.print()" class="btn btn-modern">
                            <i class="fas fa-print me-2"></i>CETAK NOTA
                        </button>
                    </div>
                </div>

                <div class="d-none d-print-block mt-5 pt-4 border-top text-center text-muted small">
                    Nota dihasilkan secara digital oleh Sistem Kasir Fernando pada <?= date('d/m/Y H:i:s'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>