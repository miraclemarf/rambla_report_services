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

<!-- Datatable -->
<script src="<?= base_url('assets/data_tables/datatables.min.js'); ?>"></script>
<!-- Datatable -->

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

    });
</script>
</body>

</html>