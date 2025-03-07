<div class="content-wrapper">
    <?php $this->load->view('modal/filter-penjualanartikel', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="embed-responsive embed-responsive-4by3 frame-meta">
                        <iframe id="iFrameSalesMetaByArticle" class="embed-responsive-item" src="<?= $iframe ?>" allowfullscreen allowtransparency></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        
    });
</script>