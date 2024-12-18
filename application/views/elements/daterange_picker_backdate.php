<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/chosen.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/daterangepicker.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap-datetimepicker.min.css" />


<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="typcn typcn-calendar"></i></span>
    </div>
    <input type="text" class="form-control form-control-sm" type="text" name="daterange2" id="filter-last-periode" value="<?= date("m/01/Y", strtotime("-1 month", strtotime(date("m/01/Y")))); ?> - <?= date("m/t/Y", strtotime("-1 month", strtotime(date("m/01/Y")))); ?>" placeholder="Pilih Tanggal" />
</div>



<script>
    var last_periode = $('#filter-last-periode').val();
    $(function() {
        $('input[name="daterange2"]').daterangepicker({
            opens: 'left',
            'applyClass': 'btn-sm btn-rambla',
            'cancelClass': 'btn-sm btn-default',
            locale: {
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
            }
        }, function(start, end, label) {
            last_periode = start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY');
        });
    });
</script>

<script src="<?php echo base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/daterangepicker.min.js"></script>