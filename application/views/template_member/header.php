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
    <script src="<?php echo base_url() ?>assets/js/jquery-3.4.1.min.js"></script>
    <!-- Awal Datatable -->
    <link href="<?= base_url('assets/data_tables/datatables.min.css'); ?>" rel="stylesheet">
    
    <!-- Datatable -->
    <script src="<?= base_url('assets/data_tables/datatables.min.js'); ?>"></script>
    <!-- Akhir Datatable -->

    <!-- Awal Select2 -->
    <link rel="stylesheet" href="<?= base_url('assets/template/celestialui'); ?>/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/template/celestialui'); ?>/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- Akhir Select2 -->

    <!-- base:js -->
    <script src="<?= base_url('assets/template/celestialui'); ?>/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->

    <link rel="stylesheet" href="<?php echo base_url() ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />

    <!-- Awal Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/style.css">
    <!-- Akhir Custom CSS -->
    
</head>

<body>
    <script>
           function rupiahjs(bilangan) {
                if(bilangan == null){
                    bilangan = 0;
                }else{
                    bilangan = bilangan;
                }
                var bilangan = bilangan;
                var number_string = bilangan.toString(),
                    sisa = number_string.length % 3,
                    rupiah = number_string.substr(0, sisa),
                    ribuan = number_string.substr(sisa).match(/\d{3}/g);
    
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return rupiah;
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