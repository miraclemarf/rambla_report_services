<div class="modal fade" id="modal-export-promo" tabindex="-1" role="dialog" aria-labelledby="openModal" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: 350px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="openModal">Export Data Promo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <p class="text-center loading">Loading</p>
                <div id="export-promo">
                
                    <div class="row">
                        <div class="col-md-4">
                            <label class="mt-1">Pilih Format</label>
                        </div>
                        <div class="col-md-8">
                            <select class="js-example-basic-single format-file-export">
                                <option value=''>-- Pilih Data --</option>
                                <option value="xls">Excel</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-export" data-dismiss="modal">Export</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>