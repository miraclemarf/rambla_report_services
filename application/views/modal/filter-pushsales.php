<div class="modal fade" id="modal-filter-pushsales" tabindex="-1" role="dialog" aria-labelledby="openModal" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center" role="document" style="width: 350px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="openModal">Filter Data Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center loading">Loading</p>
                            <div id="filter-pushsales">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="mt-1">Pilih Periode <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <?php $this->load->view('elements/daterange_picker'); ?>
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="mt-1">Trans No</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="trans_no" class="form-control form-control-sm" oninput="validateNumber(this)">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="mt-1">Kode Register</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="js-example-basic-single list_register">
                                            <option value=''>Please Wait...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="mt-1">Trans Status</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="js-example-basic-single list_transstatus">
                                            <option value=''>-- Pilih Data --</option>
                                            <option value='1'>Success (1)</option>
                                            <option value='2'>Cancel (2)</option>
                                            <option value='3'>Trader (3)</option>
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