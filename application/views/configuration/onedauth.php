<!DOCTYPE html>
<html lang="en">
<head>
<script src="<?php echo base_url() ?>assets/js/jquery-3.4.1.min.js"></script>
</head>
<body>
<div>
    <input type="hidden" id="url-oned" value="<?= $url; ?>" />
</div>
<script>
    $(document).ready(function() {
        window.open($('#url-oned').val());
    });
</script>
</body>