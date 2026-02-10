<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM penjualan 
           JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
           WHERE PenjualanID = '$id'");
$data = mysqli_fetch_array($query);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= $id; ?> - Kasir Fernando</title>
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
            background: linear-gradient(135deg, var(--color-light-blue) 0%, #E8F6FB 100%);
            min-height: 100vh;
        }
        
        .card-invoice { 
            border-radius: 20px; 
            border: none; 
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(28, 77, 141, 0.15);
        }

        .invoice-header {
            background: linear-gradient(135deg, var(--color-dark-blue), var(--color-navy));
            color: white;
            padding: 30px;
            position: relative;
            overflow: hidden;
        }

        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(189, 232, 245, 0.1);
            border-radius: 50%;
        }

        .brand-invoice {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .line-dashed { 
            border-top: 2px dashed rgba(73, 136, 196, 0.3); 
            margin: 20px 0; 
        }

        .table thead th {
            background: linear-gradient(135deg, rgba(189, 232, 245, 0.3), rgba(73, 136, 196, 0.2));
            color: var(--color-navy);
            font-weight: 600;
            border: none;
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(189, 232, 245, 0.3);
        }

        .btn-action-invoice {
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-print-invoice {
            background: white;
            color: var(--color-dark-blue);
            border: 2px solid var(--color-light-blue);
        }

        .btn-print-invoice:hover {
            background: var(--color-medium-blue);
            color: white;
            border-color: var(--color-medium-blue);
            transform: translateY(-2px);
        }

        .btn-back-invoice {
            background: var(--color-navy);
            color: white;
            border: 2px solid var(--color-navy);
        }

        .btn-back-invoice:hover {
            background: var(--color-dark-blue);
            border-color: var(--color-dark-blue);
            transform: translateY(-2px);
        }

        .total-section {
            background: linear-gradient(135deg, rgba(189, 232, 245, 0.2), rgba(73, 136, 196, 0.1));
            padding: 20px;
            border-radius: 15px;
            border: 2px solid var(--color-light-blue);
        }
        
        @media print {
            .no-print, .sidebar { display: none !important; }
            body { background-color: white; }
            .container-fluid { padding: 0 !important; }
            .card { box-shadow: none !important; border: 1px solid #ddd !important; }
            .mx-auto { width: 100% !important; max-width: 100% !important; }
            .invoice-header { background: var(--color-dark-blue) !important; }
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <div class="no-print">
            <?php include '../../template/sidebar.php'; ?>
        </div>
        
        <div class="container-fluid p-4">
            <div class="card shadow-sm card-invoice col-md-8 mx-auto">
                <div class="invoice-header position-relative">
                    <div class="row align-items-center position-relative" style="z-index: 1;">
                        <div class="col-md-7">
                            <div class="brand-invoice">
                                <i class="fas fa-store me-2"></i>KASIR FERNANDO
                            </div>
                            <small class="opacity-75">Toko Terpercaya & Berkualitas</small>
                        </div>
                        <div class="col-md-5 text-md-end mt-3 mt-md-0">
                            <div class="d-inline-block text-start bg-white bg-opacity-10 rounded-3 px-3 py-2">
                                <div class="small opacity-75">Invoice ID:</div>
                                <div class="h5 fw-bold m-0">#<?= str_pad($id, 6, '0', STR_PAD_LEFT); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 bg-white">
                    <div class="d-flex justify-content-end gap-2 mb-4 no-print">
                        <button onclick="window.print()" class="btn btn-action-invoice btn-print-invoice">
                            <i class="fas fa-print me-2"></i>Cetak Invoice
                        </button>
                        <a href="index.php" class="btn btn-action-invoice btn-back-invoice">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <h6 class="small fw-bold text-uppercase mb-2" style="color: var(--color-medium-blue);">
                                <i class="fas fa-user me-2"></i>Pelanggan:
                            </h6>
                            <h5 class="fw-bold m-0" style="color: var(--color-navy);"><?= $data['NamaPelanggan']; ?></h5>
                            <p class="text-muted small mb-0">Customer ID: #PLG-<?= $data['PelangganID']; ?></p>
                        </div>
                        <div class="col-6 text-end">
                            <h6 class="small fw-bold text-uppercase mb-2" style="color: var(--color-medium-blue);">
                                <i class="fas fa-calendar me-2"></i>Tanggal:
                            </h6>
                            <h5 class="fw-bold m-0" style="color: var(--color-navy);"><?= date('d/m/Y', strtotime($data['TanggalPenjualan'])); ?></h5>
                            <small class="text-muted"><?= date('H:i', strtotime($data['TanggalPenjualan'])); ?> WIB</small>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead>
                                <tr class="small">
                                    <th class="ps-3">PRODUK</th>
                                    <th class="text-center">HARGA SATUAN</th>
                                    <th class="text-center">QTY</th>
                                    <th class="text-end pe-3">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $detail = mysqli_query($conn, "SELECT * FROM detailpenjualan 
                                          JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                          WHERE PenjualanID = '$id'");
                                while($d = mysqli_fetch_array($detail)){
                                ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold" style="color: var(--color-navy);"><?= $d['NamaProduk']; ?></div>
                                    </td>
                                    <td class="text-center text-muted small">Rp <?= number_format($d['Harga'], 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <span class="badge px-3 py-2" style="background: var(--color-light-blue); color: var(--color-navy); font-weight: 600;">
                                            <?= $d['JumlahProduk']; ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-3 fw-bold" style="color: var(--color-medium-blue);">
                                        Rp <?= number_format($d['Subtotal'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="line-dashed"></div>

                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <div class="total-section">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Total Belanja:</span>
                                    <span class="fw-bold" style="color: var(--color-navy);">
                                        Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?>
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3" style="border-top: 2px solid var(--color-medium-blue);">
                                    <span class="h6 fw-bold m-0" style="color: var(--color-dark-blue);">TOTAL AKHIR</span>
                                    <span class="h3 fw-bold m-0" style="color: var(--color-dark-blue);">
                                        Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-center d-none d-print-block">
                        <p class="small text-muted fst-italic mb-4">"Terima kasih telah berbelanja di Kasir Fernando!"</p>
                        <div class="row mt-5 pt-4">
                            <div class="col-6">
                                <small class="text-muted">Pelanggan</small><br><br><br>
                                <strong style="color: var(--color-navy);">( <?= $data['NamaPelanggan']; ?> )</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Kasir</small><br><br><br>
                                <strong style="color: var(--color-navy);">( <?= $_SESSION['username'] ?? 'Admin'; ?> )</strong>
                            </div>
                        </div>
                        <div class="mt-4 pt-4" style="border-top: 1px solid #ddd;">
                            <small class="text-muted">
                                <i class="fas fa-phone me-2"></i>Hubungi Kami: (021) 123-4567 | 
                                <i class="fas fa-envelope ms-2 me-2"></i>info@kasirfernando.com
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>