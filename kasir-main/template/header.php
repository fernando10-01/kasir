<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Efek Halus untuk Semua Tombol */
    .btn {
        transition: all 0.3s ease; /* Membuat perubahan warna/ukuran jadi mulus */
        border-radius: 8px;
        position: relative;
        overflow: hidden;
    }

    /* Efek saat Mouse Menempel (Hover) */
    .btn:hover {
        transform: translateY(-3px); /* Tombol sedikit terangkat ke atas */
        box-shadow: 0 5px 15px rgba(28, 77, 141, 0.3); /* Bayangan dengan warna tema */
        filter: brightness(1.1); /* Membuat warna sedikit lebih terang */
    }

    /* Efek saat Tombol Diklik (Active) */
    .btn:active {
        transform: translateY(1px); /* Tombol seolah tertekan ke bawah */
        box-shadow: 0 2px 5px rgba(15, 40, 84, 0.2);
    }

    /* Efek Khusus untuk Sidebar Menu */
    .nav-link {
        transition: all 0.3s;
        border-radius: 5px;
        margin-bottom: 5px;
    }

    .nav-link:hover {
        background-color: rgba(189, 232, 245, 0.15);
        padding-left: 20px; /* Efek bergeser ke kanan sedikit */
        color: #BDE8F5 !important;
    }

    /* Efek Hover Menu Sidebar */
    .nav-link {
        padding: 12px 15px;
        transition: all 0.3s ease;
        border-radius: 10px !important;
        margin: 4px 0;
    }

    .nav-link:hover:not(.active) {
        background-color: rgba(73, 136, 196, 0.2) !important;
        transform: translateX(8px); /* Efek menu bergeser ke kanan saat hover */
        color: #BDE8F5 !important;
        box-shadow: 0 2px 8px rgba(189, 232, 245, 0.1);
    }

    /* Efek saat Menu Aktif */
    .nav-link.active {
        font-weight: bold;
        transform: scale(1.05); /* Sedikit membesar saat aktif */
        background: linear-gradient(135deg, #4988C4 0%, #BDE8F5 100%) !important;
        color: #0F2854 !important;
        box-shadow: 0 4px 15px rgba(73, 136, 196, 0.4);
    }

    /* Animasi klik pada tombol keluar */
    .btn-danger:active {
        transform: scale(0.95);
    }

    /* Customisasi Tombol Primary dengan Tema Fernando */
    .btn-primary {
        background: linear-gradient(135deg, #1C4D8D 0%, #0F2854 100%);
        border: none;
        color: #fff;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #4988C4 0%, #1C4D8D 100%);
        box-shadow: 0 5px 15px rgba(28, 77, 141, 0.4);
    }

    /* Customisasi Tombol Secondary dengan Tema Fernando */
    .btn-secondary {
        background-color: #4988C4;
        border: none;
        color: #fff;
    }

    .btn-secondary:hover {
        background-color: #1C4D8D;
        box-shadow: 0 5px 15px rgba(73, 136, 196, 0.4);
    }

    /* Customisasi Tombol Light dengan Tema Fernando */
    .btn-light {
        background-color: #BDE8F5;
        border: 1px solid #4988C4;
        color: #0F2854;
    }

    .btn-light:hover {
        background-color: #4988C4;
        border-color: #1C4D8D;
        color: #fff;
    }

    /* Customisasi SweetAlert dengan Tema Fernando */
    .swal2-popup {
        border-radius: 15px;
    }

    .swal2-confirm {
        background: linear-gradient(135deg, #1C4D8D 0%, #0F2854 100%) !important;
        border-radius: 8px;
    }

    .swal2-confirm:hover {
        background: linear-gradient(135deg, #4988C4 0%, #1C4D8D 100%) !important;
        box-shadow: 0 5px 15px rgba(28, 77, 141, 0.4) !important;
    }

    .swal2-cancel {
        background-color: #4988C4 !important;
        border-radius: 8px;
    }

    .swal2-cancel:hover {
        background-color: #1C4D8D !important;
    }

    /* Efek untuk Input Form dengan Tema Fernando */
    .form-control:focus {
        border-color: #4988C4;
        box-shadow: 0 0 0 0.2rem rgba(73, 136, 196, 0.25);
    }

    /* Efek untuk Card dengan Tema Fernando */
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 25px rgba(28, 77, 141, 0.15);
        transform: translateY(-2px);
    }

    /* Badge dengan Tema Fernando */
    .badge-primary {
        background: linear-gradient(135deg, #1C4D8D 0%, #0F2854 100%);
    }

    .badge-info {
        background-color: #BDE8F5;
        color: #0F2854;
    }

    /* Link dengan Tema Fernando */
    a {
        color: #1C4D8D;
        transition: color 0.3s ease;
    }

    a:hover {
        color: #4988C4;
    }
</style>