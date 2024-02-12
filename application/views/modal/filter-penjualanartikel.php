<div class="modal fade" id="modal-filter-penjualanartikel" tabindex="-1" role="dialog" aria-labelledby="openModal" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center" role="document" style="width: 350px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="openModal">Filter Data Penjualan By Artikel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center loading">Loading</p>
                            <div id="filter-penjualanartikel">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="mt-1">Pilih Periode</label>
                                    </div>
                                    <div class="col-md-8">
                                        <?php $this->load->view('elements/daterange_picker'); ?>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="mt-1">Store</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="js-example-basic-single list_store">
                                            <option value=''>Please Wait...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="mt-1">Division</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="js-example-basic-single list_division">
                                            <option value=''>Please Wait...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="mt-1">Sub Division</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="js-example-basic-single list_sub_division">
                                            <option value=''>Please Wait...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="mt-1">Dept</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="js-example-basic-single list_dept">
                                            <option value=''>Please Wait...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="mt-1">Sub Dept</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="js-example-basic-single list_sub_dept">
                                        <option value=''>Please Wait...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="mt-1">Pilih Brand</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="js-example-basic-single list_user_brand">
                                            <option value=''>Please Wait...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="mt-1">Area Transaksi</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="js-example-basic-single list_areatrx">
                                            <option value=''>-- Pilih Data --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="mt-1">Pilih Source</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="js-example-basic-single list_source">
                                        <option value=''>-- Pilih Data --</option>
                                        <option value="OFFLINE">OFFLINE</option>
                                        <option value="ONLINE">ONLINE</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rambla btn-submit-filter" data-dismiss="modal">Submit</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>
</div>