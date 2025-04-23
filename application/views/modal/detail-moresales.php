<div class="modal fade" id="modal-detail-moresales" tabindex="-1" role="dialog" aria-labelledby="openModal" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center" role="document" style="width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="openModal">Detail Sales</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="btnTrigger" href="#detail" role="tab" data-toggle="tab">Detail</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#paid" role="tab" data-toggle="tab">Paid</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="detail">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-custom d-none" id="tb_sales_dtl">
                                            <thead class="table-rambla">
                                                <tr>
                                                    <th>#</th>
                                                    <th>
                                                        <nobr>Trans No</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Barcode</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Article Code</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Supplier Pcode</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Supplier Pname</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Qty (pcs)</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Berat (Kg)</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Price</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Disc Pct</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Disc Amt</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>More Disc Pct</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>More Disc Amt</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Net Price</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>No Ref</nobr>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="paid">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-custom d-none" id="tb_paid">
                                            <thead class="table-rambla">
                                                <tr>
                                                    <th>#</th>
                                                    <th>
                                                        <nobr>Trans No</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Card Number</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Card Name</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Paid Amount</nobr>
                                                    </th>
                                                    <th>
                                                        <nobr>Descrption</nobr>
                                                    </th>
                                    
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>
</div>