<div class="modal fade" id="modal-promo-detail" tabindex="-1" role="dialog" aria-labelledby="openModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="openModal">Detail Promo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <p id="loading" class="text-center">Loading</p>
                <div class="table-responsive">
                    <table class="table table-striped" id="tb_promo_dtl">
                        <thead class="table-rambla">
                            <tr>
                                <th><nobr>Promo Id</nobr></th>
                                <th><nobr>Article Name</nobr></th>
                                <th><nobr>Barcode</nobr></th>
                                <th><nobr>Article Code</nobr></th>
                                <th><nobr>Article Number</nobr></th>
                            </tr>
                        </thead>
                        <tbody id="promo_dtl">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>