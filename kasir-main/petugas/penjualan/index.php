<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

// Variabel aktif untuk sidebar
$current_dir = 'transaksi'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Penjualan - Kasir Fernando</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(135deg, #BDE8F5 0%, #4988C4 100%);
            min-height: 100vh;
        }
        .produk-item { 
            cursor: pointer; 
            transition: all 0.25s ease; 
            border: 1px solid #BDE8F5; 
            border-radius: 16px; 
            overflow: hidden;
        }
        .produk-item:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 20px rgba(28, 77, 141, 0.2) !important;
            border-color: #1C4D8D; 
        }
        .produk-item .card-body { padding: 1.25rem; }
        .keranjang-box { 
            border-radius: 20px; 
            position: sticky; 
            top: 20px; 
            border: none;
        }
        .table-keranjang thead th {
            background: #BDE8F5;
            border-bottom: 2px solid #4988C4;
            color: #0F2854;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .dashed-line { 
            border-top: 2px dashed #4988C4; 
            margin: 1.5rem 0; 
        }
        .total-section {
            background: linear-gradient(135deg, #BDE8F5 0%, rgba(73, 136, 196, 0.3) 100%);
            border-radius: 12px;
            padding: 15px;
        }
        .form-control-custom {
            border-radius: 10px;
            border: 1.5px solid #4988C4;
            padding: 10px 15px;
        }
        .form-control-custom:focus {
            box-shadow: 0 0 0 3px rgba(28, 77, 141, 0.2);
            border-color: #1C4D8D;
        }
        .bg-soft-primary {
            background: rgba(28, 77, 141, 0.1) !important;
        }
        .text-primary {
            color: #1C4D8D !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, #1C4D8D 0%, #0F2854 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0F2854 0%, #1C4D8D 100%);
        }
        .bg-primary {
            background: linear-gradient(135deg, #1C4D8D 0%, #0F2854 100%) !important;
        }
        .shadow-primary {
            box-shadow: 0 5px 15px rgba(28, 77, 141, 0.3);
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #4988C4; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="container-fluid p-4">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold m-0" style="color: #0F2854;">Pilih Produk</h3>
                            <p class="text-muted small">Klik pada produk untuk menambah ke keranjang</p>
                        </div>
                        <div class="input-group w-50">
                            <span class="input-group-text bg-white border-0 shadow-sm" style="border-radius: 10px 0 0 10px;"><i class="fas fa-search" style="color: #4988C4;"></i></span>
                            <input type="text" id="searchInput" class="form-control border-0 shadow-sm" placeholder="Cari barang..." onkeyup="cariProduk()" style="border-radius: 0 10px 10px 0;">
                        </div>
                    </div>

                    <div class="row g-3 overflow-auto" style="max-height: 75vh; padding-bottom: 20px;">
                        <?php 
                        $sql = mysqli_query($conn, "SELECT * FROM produk WHERE Stok > 0 ORDER BY NamaProduk ASC");
                        while($p = mysqli_fetch_array($sql)){
                        ?>
                        <div class="col-md-4">
                            <div class="card produk-item shadow-sm h-100 bg-white" 
                                 onclick="tambahItem('<?= $p['ProdukID'] ?>', '<?= $p['NamaProduk'] ?>', '<?= $p['Harga'] ?>', '<?= $p['Stok'] ?>')">
                                <div class="card-body">
                                    <div class="badge bg-soft-primary text-primary mb-2">#<?= $p['ProdukID'] ?></div>
                                    <h6 class="fw-bold mb-1 text-truncate" style="color: #0F2854;"><?= $p['NamaProduk'] ?></h6>
                                    <h5 class="fw-bold text-primary mb-3">Rp <?= number_format($p['Harga'], 0, ',', '.') ?></h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small text-muted"><i class="fas fa-box me-1"></i> Stok: <?= $p['Stok'] ?></span>
                                        <button class="btn btn-sm btn-primary rounded-circle"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card shadow-lg keranjang-box bg-white">
                        <div class="card-header bg-white border-0 py-4 px-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white p-2 rounded-3 me-3">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <h5 class="fw-bold m-0" style="color: #0F2854;">Keranjang Belanja</h5>
                            </div>
                        </div>
                        <div class="card-body px-4 pt-0">
                            <form action="proses_simpan.php" method="POST" id="formTransaksi">
                                <div class="row g-2 mb-4">
                                    <div class="col-12">
                                        <input type="text" name="NamaPelanggan" class="form-control form-control-custom" placeholder="Nama Pelanggan" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="NomorTelepon" class="form-control form-control-custom" placeholder="No. Telp" required>
                                    </div>
                                    <div class="col-md-6">
                                        <textarea name="Alamat" class="form-control form-control-custom" rows="1" placeholder="Alamat" required></textarea>
                                    </div>
                                </div>

                                <div class="table-responsive" style="max-height: 250px;">
                                    <table class="table table-borderless align-middle table-keranjang" id="tabelPesanan">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th width="80" class="text-center">Qty</th>
                                                <th class="text-end">Subtotal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="dashed-line"></div>

                                <div class="total-section mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="fw-bold" style="color: #0F2854;">TOTAL BAYAR</span>
                                        <h2 class="fw-bold text-primary m-0" id="totalHarga">Rp 0</h2>
                                    </div>

                                    <div class="mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text border-0 bg-white">Rp</span>
                                            <input type="number" id="uangBayar" name="Bayar" class="form-control form-control-lg border-0 fw-bold text-success" placeholder="Uang Bayar..." oninput="hitungKembalian()">
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between px-2">
                                        <span class="fw-bold small" style="color: #0F2854;">KEMBALIAN</span>
                                        <span class="fw-bold text-danger" id="textKembalian">Rp 0</span>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-primary" id="btnBayar" disabled>
                                    <i class="fas fa-print me-2"></i> PROSES TRANSAKSI
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let items = [];

        function tambahItem(id, nama, harga, stokMax) {
            let index = items.findIndex(i => i.id === id);
            if(index !== -1) {
                if(items[index].qty < stokMax) {
                    items[index].qty++;
                } else {
                    Swal.fire({icon: 'error', title: 'Stok Terbatas', text: 'Maksimal stok tersedia adalah ' + stokMax});
                }
            } else {
                items.push({ id, nama, harga: parseInt(harga), qty: 1 });
            }
            renderTabel();
        }

        function hapusItem(index) {
            items.splice(index, 1);
            renderTabel();
        }

        function hitungKembalian() {
            let total = items.reduce((sum, item) => sum + (item.qty * item.harga), 0);
            let bayar = document.getElementById('uangBayar').value;
            let kembalian = bayar - total;
            
            document.getElementById('textKembalian').innerText = 'Rp ' + (kembalian >= 0 ? kembalian.toLocaleString('id-ID') : 0);
            document.getElementById('btnBayar').disabled = (items.length === 0 || kembalian < 0 || bayar === "");
        }

        function renderTabel() {
            let html = '';
            let grandTotal = 0;
            items.forEach((item, i) => {
                let subtotal = item.qty * item.harga;
                grandTotal += subtotal;
                html += `
                <tr>
                    <td>
                        <div class="fw-bold small" style="color: #0F2854;">${item.nama}</div>
                        <div class="text-muted" style="font-size: 10px;">@ Rp ${item.harga.toLocaleString('id-ID')}</div>
                        <input type="hidden" name="ProdukID[]" value="${item.id}">
                    </td>
                    <td>
                        <input type="number" name="Jumlah[]" class="form-control form-control-sm text-center border-0" style="background-color: #BDE8F5;" value="${item.qty}" readonly>
                    </td>
                    <td class="text-end fw-bold small" style="color: #1C4D8D;">Rp ${subtotal.toLocaleString('id-ID')}</td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm text-danger" onclick="hapusItem(${i})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
            });
            
            if(items.length === 0) {
                html = '<tr><td colspan="4" class="text-center py-4 text-muted small">Keranjang kosong</td></tr>';
            }

            document.querySelector('#tabelPesanan tbody').innerHTML = html;
            document.getElementById('totalHarga').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
            hitungKembalian();
        }

        function cariProduk() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let cards = document.getElementsByClassName('col-md-4');

            for (let i = 0; i < cards.length; i++) {
                let namaProduk = cards[i].querySelector('h6').innerText.toLowerCase();
                
                if (namaProduk.indexOf(input) > -1) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>