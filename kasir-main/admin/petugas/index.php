<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Kasir Fernando</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(15, 40, 84, 0.06);
        }

        .table-container {
            background: rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            overflow: hidden;
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

        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        .opacity-100-hover:hover {
            opacity: 1 !important;
            transform: scale(1.1);
        }

        .text-dark-custom {
            color: var(--dark-color) !important;
        }

        .bg-primary-light {
            background-color: rgba(28, 77, 141, 0.1) !important;
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>

        <div class="container-fluid p-4">
            <header class="row mb-4 pt-2">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-0 text-dark-custom">Registrasi & User</h3>
                        <p class="text-muted small">Kelola hak akses petugas dan administrator</p>
                    </div>
                    <a href="tambah_petugas.php" class="btn btn-primary btn-modern shadow-sm" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border: none;">
                        <i class="fas fa-plus me-2"></i>Tambah User
                    </a>
                </div>
            </header>

            <div class="glass-card shadow-sm p-4">
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-white">
                                <tr class="text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">
                                    <th class="ps-4 py-3">NO</th>
                                    <th class="py-3">USERNAME</th>
                                    <th class="py-3">HAK AKSES</th>
                                    <th class="text-center py-3">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $query = mysqli_query($conn, "SELECT * FROM user");
                                while($d = mysqli_fetch_array($query)){
                                ?>
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 fw-bold text-muted small"><?= str_pad($no++, 2, "0", STR_PAD_LEFT); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary-light d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                                <i class="fas fa-user text-primary-custom small"></i>
                                            </div>
                                            <span class="fw-bold text-dark-custom"><?= $d['Username']; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($d['Role'] == 'admin'): ?>
                                            <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2 border border-danger border-opacity-25">
                                                <i class="fas fa-unlock-alt me-1"></i> ADMIN
                                            </span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill px-3 py-2 border" style="background-color: rgba(73, 136, 196, 0.1); color: var(--secondary-color); border-color: var(--secondary-color) !important;">
                                                <i class="fas fa-user-tag me-1"></i> PETUGAS
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="edit_petugas.php?id=<?= $d['UserID']; ?>" 
                                               class="btn btn-sm btn-light border-0 shadow-sm opacity-75 opacity-100-hover transition-all rounded-3 px-3" style="color: var(--primary-color);">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <button onclick="confirmDelete(<?= $d['UserID']; ?>)" 
                                               class="btn btn-sm btn-light border-0 shadow-sm link-danger opacity-75 opacity-100-hover transition-all rounded-3 px-3">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Akses?',
            text: "User ini akan kehilangan akses ke sistem sepenuhnya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            borderRadius: '1.5rem'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "hapus.php?id=" + id;
            }
        })
    }
    </script>

    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'sukses'): ?>
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data user telah diperbarui.',
            icon: 'success',
            confirmButtonColor: '<?= $primary_color ?>',
            borderRadius: '1.5rem'
        });
    </script>
    <?php endif; ?>
</body>
</html>