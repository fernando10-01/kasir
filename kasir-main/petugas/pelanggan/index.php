<?php 
session_start();
include '../../main/connect.php';

// Proteksi halaman
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

// Variabel untuk menandai menu aktif di sidebar
$current_dir = 'pelanggan'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan - Kasir Fernando</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #BDE8F5 0%, #4988C4 100%);
            min-height: 100vh;
        }
        .card { 
            border-radius: 20px;
            border: none;
        }
        .table thead { 
            background: linear-gradient(135deg, #BDE8F5 0%, #4988C4 50%);
            color: #0F2854;
        }
        .btn-action { 
            border-radius: 10px; 
            transition: 0.3s; 
        }
        .btn-action:hover { 
            transform: translateY(-2px); 
        }
        .btn-outline-primary {
            color: #1C4D8D;
            border-color: #1C4D8D;
        }
        .btn-outline-primary:hover {
            background-color: #1C4D8D;
            border-color: #1C4D8D;
        }
        .text-primary {
            color: #1C4D8D !important;
        }
        .fw-bold.text-dark {
            color: #0F2854 !important;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="container-fluid p-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h3 class="fw-bold" style="color: #0F2854;"><i class="fas fa-user-tag me-2" style="color: #1C4D8D;"></i>Data Pelanggan</h3>
                    <p class="text-muted">Kelola data pelanggan tetap dan pantau histori belanja mereka.</p>
                </div>
            </div>

            <div class="card shadow-lg">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4 py-3">NO</th>
                                    <th>NAMA PELANGGAN</th>
                                    <th>NOMOR TELEPON</th>
                                    <th>ALAMAT</th>
                                    <th class="text-center">HISTORI</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $query = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY NamaPelanggan ASC");
                                
                                if(mysqli_num_rows($query) > 0) {
                                    while($row = mysqli_fetch_array($query)){
                                ?>
                                <tr>
                                    <td class="ps-4"><?= $no++; ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= $row['NamaPelanggan']; ?></div>
                                        <small class="text-muted">ID: #PLG-<?= $row['PelangganID']; ?></small>
                                    </td>
                                    <td><i class="fas fa-phone-alt me-2 text-success small"></i><?= $row['NomorTelepon']; ?></td>
                                    <td><i class="fas fa-map-marker-alt me-2 text-danger small"></i><?= $row['Alamat']; ?></td>
                                    <td class="text-center">
                                        <a href="histori_belanja.php?id=<?= $row['PelangganID']; ?>" class="btn btn-sm btn-outline-primary btn-action px-3">
                                            <i class="fas fa-search-dollar me-1"></i> Cek Histori
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $row['PelangganID']; ?>" class="btn btn-sm btn-light text-warning me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <?php if($_SESSION['role'] == 'admin'): ?>
                                        <a href="javascript:void(0)" onclick="konfirmasiHapus(<?= $row['PelangganID']; ?>)" class="btn btn-sm btn-light text-danger">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                    echo "<tr><td colspan='6' class='text-center py-5 text-muted'>Belum ada data pelanggan.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Pelanggan?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#4988C4',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "hapus.php?id=" + id;
            }
        })
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>