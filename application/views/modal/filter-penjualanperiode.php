<div class="modal fade" id="modal-filter-penjualankategori" tabindex="-1" role="dialog" aria-labelledby="openModal" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center" role="document" style="width: 350px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="openModal">Filter Data Penjualan By Periode</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center loading">Loading</p>
                            <div id="filter-penjualankategori">
                                <fieldset>
                                    <legend>
                                        <h4>General:</h4>
                                    </legend>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="mt-1">Periode <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php $this->load->view('elements/daterange_picker'); ?>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="mt-1">Time</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6 pr-1">
                                                    <input type="time" id="start_time" class="form-control form-control-sm" maxlength="5">
                                                </div>
                                                <div class="col-md-6 pl-1">
                                                    <input type="time" id="end_time" class="form-control form-control-sm" maxlength="5">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="mt-1">Unit <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="js-example-basic-single list_unit">
                                                <option value=''>Please Wait...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="mt-1">Store <span class="text-danger">*</span></label>
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
                                    <!-- <div class="row mt-2">
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
                                    </div> -->
                                    <!-- <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="mt-1">Pilih Brand</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="js-example-basic-single list_user_brand">
                                                <option value=''>Please Wait...</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="mt-1">Pilih Source</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="js-example-basic-single list_areatrx">
                                                <option value="">-- Pilih Data --</option>
                                                <option value="FLOOR">FLOOR</option>
                                                <option value="BAZAAR">BAZAAR</option>
                                                <option value="ONLINE">ONLINE</option>
                                            </select>
                                        </div>
                                    </div>

                                    <hr>
                                    <legend>
                                        <h4>Member Area</h4>
                                    </legend>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <div class="form-check form-check-flat form-check-primary">
                                                <label class=" form-check-label" style="padding-top: 6px;">
                                                    <input type="radio" class="form-check-input" name="member_area" id="member_area" value="1" checked>
                                                    All
                                                    <i class="input-helper"></i></label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 member-only">
                                            <div class="form-check form-check-flat form-check-primary">
                                                <label class=" form-check-label" style="padding-top: 6px;">
                                                    <input type="radio" class="form-check-input" name="member_area" id="member_area" value="2">
                                                    Member Only
                                                    <i class="input-helper"></i></label>
                                            </div>

                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-check form-check-flat form-check-primary">
                                                <label class=" form-check-label" style="padding-top: 6px;">
                                                    <input type="radio" class="form-check-input" name="member_area" id="member_area" value="3">
                                                    Non Member
                                                    <i class="input-helper"></i></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="mt-1">Min Purchase</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" name="min_purchase" class="form-control form-control-sm" placeholder="ex.100000" oninput="validateNumber(this)">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="mt-1">Max Purchase</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" name="max_purchase" class="form-control form-control-sm" placeholder="ex.200000" oninput="validateNumber(this)">
                                        </div>
                                    </div>
                                    <hr>
                                    <legend>
                                        <h4>Sales Option</h4>
                                    </legend>
                                    <div class="row mt-2">
                                        <div class="col" style="padding-left: 10px;">
                                            <div class="form-check form-check-flat form-check-primary" id="exc_vch">
                                                <label class="form-check-label" style="padding-top: 6px;">
                                                    <input type="checkbox" class="form-check-input" name="excludevch" value="1">
                                                    Exclude Voucher dan Starry
                                                    <i class="input-helper"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label class="mt-1">Payment Type</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="js-example-basic-single list_payment">
                                                <option value=''>-- Pilih Data --</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
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