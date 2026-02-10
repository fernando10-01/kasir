<?php 
session_start();
include '../../main/connect.php';

if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$data = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pelanggan WHERE PelangganID='$id'"));

$update_success = false; // Variabel penanda untuk memicu popup

if(isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['NamaPelanggan']);
    $alamat = mysqli_real_escape_string($conn, $_POST['Alamat']);
    $telp = mysqli_real_escape_string($conn, $_POST['NomorTelepon']);

    $query = mysqli_query($conn, "UPDATE pelanggan SET NamaPelanggan='$nama', Alamat='$alamat', NomorTelepon='$telp' WHERE PelangganID='$id'");
    
    if($query) {
        $update_success = true; // Tandai sukses
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggan - Kasir Fernando</title>
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
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #1C4D8D 0%, #0F2854 100%);
            color: white;
            border: none;
        }
        .form-control:focus {
            border-color: #4988C4;
            box-shadow: 0 0 0 0.2rem rgba(73, 136, 196, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #1C4D8D 0%, #0F2854 100%);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0F2854 0%, #1C4D8D 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(15, 40, 84, 0.3);
        }
        .btn-light {
            border: 2px solid #4988C4;
            color: #1C4D8D;
            transition: all 0.3s ease;
        }
        .btn-light:hover {
            background-color: #BDE8F5;
            border-color: #1C4D8D;
            color: #0F2854;
        }
        .text-muted {
            color: #1C4D8D !important;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-lg">
                    <div class="card-header fw-bold py-3">
                        <i class="fas fa-user-edit me-2"></i>Edit Data Pelanggan
                    </div>
                    <div class="card-body p-4 bg-white">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">NAMA PELANGGAN</label>
                                <input type="text" name="NamaPelanggan" class="form-control" value="<?= $data['NamaPelanggan']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">NOMOR TELEPON</label>
                                <input type="text" name="NomorTelepon" class="form-control" value="<?= $data['NomorTelepon']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">ALAMAT</label>
                                <textarea name="Alamat" class="form-control" rows="3" required><?= $data['Alamat']; ?></textarea>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="index.php" class="btn btn-light px-4">Batal</a>
                                <button type="submit" name="update" class="btn btn-primary px-4">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($update_success): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data pelanggan telah diperbarui.',
            confirmButtonColor: '#1C4D8D',
            showConfirmButton: false,
            timer: 2000
        }).then(function() {
            window.location.href = 'index.php';
        });
    </script>
    <?php endif; ?>
</body>
</html>