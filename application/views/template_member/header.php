<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title; ?></title>
    <!-- base:css -->
    <link rel="stylesheet" href="<?= base_url('assets/template/celestialui'); ?>/vendors/typicons.font/font/typicons.css">
    <link rel="stylesheet" href="<?= base_url('assets/template/celestialui'); ?>/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="<?= base_url('assets/template/celestialui'); ?>/css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="<?= base_url('assets/ico/faviconrambla.png'); ?>" />
    <!-- Awal Sweet Alert -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/sweetalert/sweetalert.css">
    <script src="<?php echo base_url() ?>assets/sweetalert/sweetalert.min.js"></script>
    <!-- Akhir Sweet Alert -->
    <script src="<?= base_url('assets/js/jquery-3.4.1.min.js'); ?>"></script>

    <!-- Awal Select2 -->
    <link rel="stylesheet" href="<?= base_url('assets/template/celestialui'); ?>/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/template/celestialui'); ?>/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- Akhir Select2 -->

    <!-- base:js -->
    <script src="<?= base_url('assets/template/celestialui'); ?>/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->

    <link rel="stylesheet" href="<?php echo base_url() ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
    <!-- Awal Datatable -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Buttons Extension CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <!-- Awal Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/style.css">
    <!-- Akhir Custom CSS -->

</head>

<body>
    <script>
        function rupiahjs(bilangan) {
            if (bilangan == null) {
                bilangan = 0;
            } else {
                bilangan = bilangan;
            }
            var rupiah = bilangan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            return rupiah;
        }
        const rupiah = (number) => {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                maximumFractionDigits: 0,
                minimumFractionDigits: 0,
            }).format(number);
        }

        function hitungSelihHari(daterange) {
            const myArray = daterange.split(" - ");
            let start_date = myArray[0];
            let end_date = myArray[1];

            // Hitung selisih waktu dalam milidetik
            const selisihWaktu = new Date(end_date) - new Date(start_date);

            // Ubah selisih milidetik ke hari
            const selisihHari = selisihWaktu / (1000 * 3600 * 24);

            return Math.abs(selisihHari)
        }

        function hitungSelihBulan(daterange) {
            const myArray = daterange.split(" - ");

            // Mengonversi tanggal ke objek Date
            const startDate = new Date(myArray[0]);
            const endDate = new Date(myArray[1]);

            let selisihBulan = endDate.getMonth() - startDate.getMonth() + (12 * (endDate.getFullYear() - startDate.getFullYear()));

            return Math.abs(selisihBulan);
        }

        function validateNumber(input) {
            // Menghapus semua karakter non-numerik
            input.value = input.value.replace(/[^0-9]/g, '');
        }
    </script>
    <div class="row d-none" id="proBanner">
        <div class="col-12">
            <span class="d-flex align-items-center purchase-popup">
                <p>Get tons of UI components, Plugins, multiple layouts, 20+ sample pages, and more!</p>
                <a href="https://www.bootstrapdash.com/product/celestial-admin-template/?utm_source=organic&utm_medium=banner&utm_campaign=free-preview" target="_blank" class="btn download-button purchase-button ml-auto">Upgrade To Pro</a>
                <i class="typcn typcn-delete-outline" id="bannerClose"></i>
            </span>
        </div>
    </div>
    <div class="container-scroller">