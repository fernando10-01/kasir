<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Stok Produk - Kasir Fernando</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            background: linear-gradient(135deg, #BDE8F5 0%, #4988C4 100%);
            min-height: 100vh;
        }
        .card { 
            border-radius: 20px; 
            overflow: hidden;
            border: none;
        }
        .table thead { 
            background: linear-gradient(135deg, #BDE8F5 0%, #4988C4 50%);
        }
        .table thead th { 
            text-transform: uppercase; 
            font-size: 0.8rem; 
            letter-spacing: 1px; 
            color: #0F2854;
            border-bottom: 2px solid #1C4D8D;
        }
        .stock-warning { 
            background-color: #fff3cd !important; 
            transition: 0.3s;
        }
        .badge { 
            padding: 8px 12px; 
            border-radius: 8px; 
            font-weight: 600; 
        }
        .search-box { 
            border-radius: 10px; 
            border: 1px solid #4988C4; 
            padding-left: 40px; 
        }
        .search-box:focus {
            border-color: #1C4D8D;
            box-shadow: 0 0 0 3px rgba(28, 77, 141, 0.1);
        }
        .search-icon { 
            position: absolute; 
            left: 15px; 
            top: 10px; 
            color: #4988C4; 
        }
        .text-primary {
            color: #1C4D8D !important;
        }
        h3.fw-bold {
            color: #0F2854;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="container-fluid p-4">
            <div class="row mb-4 align-items-center">
                <div class="col-md-6">
                    <h3 class="fw-bold m-0"><i class="fas fa-boxes me-2" style="color: #1C4D8D;"></i>Manajemen Stok</h3>
                    <p class="text-muted small">Pantau ketersediaan produk secara real-time.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="position-relative d-inline-block w-75">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="filterInput" class="form-control search-box shadow-sm" placeholder="Cari nama produk...">
                    </div>
                </div>
            </div>

            <div class="card shadow-lg">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="produkTable">
                            <thead>
                                <tr>
                                    <th class="ps-4">No</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok Tersisa</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $sql = mysqli_query($conn, "SELECT * FROM produk ORDER BY NamaProduk ASC");
                                while($d = mysqli_fetch_array($sql)){
                                    // Logika Status & Warna
                                    if($d['Stok'] <= 0) {
                                        $status = "<span class='badge bg-danger'><i class='fas fa-times-circle me-1'></i> Habis</span>";
                                        $row_class = "table-light text-muted";
                                    } elseif($d['Stok'] <= 5) {
                                        $status = "<span class='badge bg-warning text-dark'><i class='fas fa-exclamation-triangle me-1'></i> Hampir Habis</span>";
                                        $row_class = "stock-warning";
                                    } else {
                                        $status = "<span class='badge bg-success bg-opacity-10 text-success border border-success border-opacity-25'><i class='fas fa-check-circle me-1'></i> Tersedia</span>";
                                        $row_class = "";
                                    }
                                ?>
                                <tr class="<?= $row_class; ?>">
                                    <td class="ps-4 fw-bold text-muted"><?= $no++; ?></td>
                                    <td class="fw-bold" style="color: #0F2854;"><?= $d['NamaProduk']; ?></td>
                                    <td><span class="text-primary fw-bold">Rp <?= number_format($d['Harga'], 0, ',', '.'); ?></span></td>
                                    <td>
                                        <span class="fw-bold <?= ($d['Stok'] <= 5) ? 'text-danger' : ''; ?>">
                                            <?= $d['Stok']; ?>
                                        </span>
                                    </td>
                                    <td><?= $status; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <small class="text-muted">* Produk dengan baris kuning menandakan stok di bawah 5 unit.</small>
            </div>
        </div>
    </div>

    <script>
        // Fitur Filter Search Sederhana
        document.getElementById('filterInput').addEventListener('keyup', function() {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll('#produkTable tbody tr');
            
            rows.forEach(row => {
                let nama = row.cells[1].textContent.toLowerCase();
                row.style.display = nama.includes(value) ? '' : 'none';
            });
        });
    </script>
</body>
</html>