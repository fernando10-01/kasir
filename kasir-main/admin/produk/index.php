<?php 
session_start();
include '../../main/connect.php';
// Cek login
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Kasir Fernando</title>
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
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(15, 40, 84, 0.06);
        }

        /* Search Bar Styling */
        .search-box {
            position: relative;
            max-width: 350px;
        }

        .search-box input {
            border-radius: 15px;
            padding: 10px 20px 10px 45px;
            border: 1.5px solid var(--accent-color);
            background: rgba(255,255,255,0.8);
            transition: all 0.3s;
        }

        .search-box input:focus {
            box-shadow: 0 0 0 4px rgba(73, 136, 196, 0.15);
            border-color: var(--secondary-color);
            background: white;
        }

        .search-box i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
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

        .badge-stok {
            width: 45px;
            padding: 8px 0;
            font-weight: 800;
            font-size: 0.85rem;
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .text-dark-custom {
            color: var(--dark-color) !important;
        }

        /* Animation for search results */
        tr { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include '../../template/sidebar.php'; ?>

    <div class="container-fluid p-4">
        
        <header class="row mb-4 pt-3">
            <div class="col-md-6">
                <h3 class="fw-bold mb-0 text-dark-custom">Manajemen Produk</h3>
                <p class="text-muted small mb-0">Atur katalog barang dan pantau ketersediaan stok</p>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end align-items-center gap-3 mt-3 mt-md-0">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari nama atau SKU...">
                </div>
                <a href="tambah.php" class="btn btn-primary btn-modern shadow-sm" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border: none;">
                    <i class="fas fa-plus me-2"></i>Tambah
                </a>
            </div>
        </header>

        <div class="glass-card shadow-sm overflow-hidden">
            <div class="p-4">
                <div class="table-glass-container">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="productTable">
                            <thead>
                                <tr>
                                    <th class="ps-4" width="8%">No</th>
                                    <th>Informasi Produk</th>
                                    <th>Harga Jual</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $total_aset = 0;
                                $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY NamaProduk ASC");
                                
                                if(mysqli_num_rows($query) == 0){
                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted'>
                                            <i class='fas fa-box-open d-block mb-3 fa-3x opacity-25'></i>
                                            <p class='fw-bold mb-0'>Belum ada produk terdaftar</p>
                                          </td></tr>";
                                }

                                while($d = mysqli_fetch_array($query)){
                                    $subtotal_produk = $d['Harga'] * $d['Stok'];
                                    $total_aset += $subtotal_produk;
                                    
                                    $stok_class = $d['Stok'] < 10 ? 'bg-danger text-white' : 'bg-success bg-opacity-10 text-success';
                                    if($d['Stok'] == 0) $stok_class = 'bg-dark text-white';
                                ?>
                                <tr class="product-row">
                                    <td class="ps-4">
                                        <span class="text-muted fw-bold">#<?= str_pad($no++, 2, "0", STR_PAD_LEFT); ?></span>
                                    </td>
                                    <td>
                                        <div class="product-name fw-bold text-dark-custom text-uppercase" style="font-size: 0.9rem; letter-spacing: 0.5px;"><?= $d['NamaProduk']; ?></div>
                                        <div class="product-sku text-muted" style="font-size: 0.7rem;">SKU: PRD-<?= $d['ProdukID']; ?></div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary-custom">Rp <?= number_format($d['Harga'], 0, ',', '.'); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-3 badge-stok <?= $stok_class; ?>">
                                            <?= $d['Stok']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="edit.php?id=<?= $d['ProdukID']; ?>" class="btn btn-sm btn-light border rounded-3 shadow-sm px-3" title="Edit Data">
                                                <i class="fas fa-edit" style="color: var(--secondary-color);"></i>
                                            </a>
                                            <a href="hapus.php?id=<?= $d['ProdukID']; ?>" class="btn btn-sm btn-light border rounded-3 shadow-sm px-3" 
                                               title="Hapus" onclick="return confirm('Menghapus produk akan berpengaruh pada data transaksi terkait. Yakin?')">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr id="noResults" style="display: none;">
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-search d-block mb-3 fa-3x opacity-25"></i>
                                        <p class="fw-bold mb-0">Produk tidak ditemukan</p>
                                    </td>
                                </tr>
                            </tbody>
                            <?php if(mysqli_num_rows($query) > 0): ?>
                            <tfoot id="totalFooter" style="background: rgba(189, 232, 245, 0.2);">
                                <tr>
                                    <td colspan="2" class="ps-4 py-4 text-muted fw-bold small uppercase">Total Nilai Inventaris (Aset):</td>
                                    <td colspan="3" class="text-end pe-5 py-4">
                                        <span class="h5 fw-extrabold text-dark-custom">Rp <?= number_format($total_aset, 0, ',', '.'); ?></span>
                                    </td>
                                </tr>
                            </tfoot>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-3">
            <div class="small text-muted"><i class="fas fa-circle text-danger me-1"></i> Stok Kritis (< 10)</div>
            <div class="small text-muted"><i class="fas fa-circle text-success me-1"></i> Stok Aman</div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        var visibleCount = 0;

        $("#productTable tbody .product-row").filter(function() {
            // Cari berdasarkan nama produk atau SKU
            var match = $(this).text().toLowerCase().indexOf(value) > -1;
            $(this).toggle(match);
            if(match) visibleCount++;
        });

        // Tampilkan pesan jika tidak ada hasil
        if(visibleCount === 0 && value !== "") {
            $("#noResults").show();
            $("#totalFooter").hide();
        } else {
            $("#noResults").hide();
            $("#totalFooter").show();
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>