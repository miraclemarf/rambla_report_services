<div class="modal fade" id="modal-filter-users" tabindex="-1" role="dialog" aria-labelledby="openModal" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center" role="document" style="width: 350px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="openModal">Filter List Users</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center loading">Loading</p>
                            <div id="filter-users">

                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="mt-1">Account Status</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="js-example-basic-single list_aktif">
                                            <option value=''>-- Pilih Data --</option>
                                            <option value="non">Non Aktif</option>
                                            <option value="act">Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label class="mt-1">Role Name</label>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="js-example-basic-single list_role">
                                            <option value=''>Please Wait...</option>
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