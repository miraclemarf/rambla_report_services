<footer class="footer">
    <div class="d-sm-flex justify-content-center">
        <span class="text-center text-sm-center">Copyright © <a href="<?= base_url(); ?>" target="_blank">Rambla.id</a> <?= date('Y'); ?></span>
    </div>
</footer>
<!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->

<!-- Plugin js for this page-->
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="<?= base_url('assets/template/celestialui'); ?>/js/off-canvas.js"></script>
<script src="<?= base_url('assets/template/celestialui'); ?>/js/hoverable-collapse.js"></script>
<script src="<?= base_url('assets/template/celestialui'); ?>/js/template.js"></script>
<script src="<?= base_url('assets/template/celestialui'); ?>/js/settings.js"></script>
<script src="<?= base_url('assets/template/celestialui'); ?>/js/todolist.js"></script>
<!-- endinject -->
<!-- plugin js for this page -->
<script src="<?= base_url('assets/template/celestialui'); ?>/vendors/progressbar.js/progressbar.min.js"></script>
<script src="<?= base_url('assets/template/celestialui'); ?>/vendors/chart.js/Chart.min.js"></script>
<!-- End plugin js for this page -->
<!-- Custom js for this page-->
<script src="<?= base_url('assets/template/celestialui'); ?>/js/dashboard.js"></script>
<!-- End custom js for this page-->
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Buttons Extension JS -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<!-- JSZip for Excel Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- HTML5 export buttons for CSV, Excel -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/2.1.8/sorting/any-number.js"></script>
<!-- Akhir Datatable -->

<!-- SELECT2 -->
<script src="<?= base_url('assets/template/celestialui'); ?>/vendors/select2/select2.min.js"></script>
<script src="<?= base_url('assets/template/celestialui'); ?>/js/select2.js"></script>
<!-- SELECT2-->


<script>
    
    $(document).ready(function() {
        //swal Failed
        console.log(data);
        
        var data = '<?php echo $this->session->flashdata('message-failed'); ?>';
        if (data != "") {
            swal('Failed !', data, 'error');
        }

        //swal Success
        var data = '<?php echo $this->session->flashdata('message-success'); ?>';
        if (data != "") {
            swal('Yeay !', data, 'success');
        }

        $(".js-example-basic-single").select2({ width: '100%' }); 

    });
</script>
</body>

</html>