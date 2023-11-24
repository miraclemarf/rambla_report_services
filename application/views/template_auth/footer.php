</div>
<!-- container-scroller -->
<!-- base:js -->
<script src="<?= base_url('assets/template/celestialui'); ?>/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- inject:js -->
<script src="<?= base_url('assets/template/celestialui'); ?>/js/off-canvas.js"></script>
<script src="<?= base_url('assets/template/celestialui'); ?>/js/hoverable-collapse.js"></script>
<script src="<?= base_url('assets/template/celestialui'); ?>/js/template.js"></script>
<script src="<?= base_url('assets/template/celestialui'); ?>/js/settings.js"></script>
<script src="<?= base_url('assets/template/celestialui'); ?>/js/todolist.js"></script>
<!-- endinject -->
<script>
    $(document).ready(function() {
        console.log(data);
        //swal Failed
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