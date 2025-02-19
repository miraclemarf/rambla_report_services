<div class="content-wrapper">
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0 font-weight-bold">Sales Today Report</h3>
            <p>Data Per :
                <?= date("l, d F Y - H:i") ?>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <?php
                var_dump($result);
            ?>
        </div>
    </div>
    

</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/vendor/chartjs/js/loader.js"></script>
<script src="<?= base_url(); ?>assets/vendor/chartjs/js/chart.js"></script>
<script type="text/JavaScript">
    $(function() {
    });

</script>