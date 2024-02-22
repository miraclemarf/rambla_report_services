<div class="content-wrapper">
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0 font-weight-bold">Sales Today Report</h3>
            <p>Data Per :
                <?= date("l, d F Y - H:i") ?>
            </p>
        </div>
        <div class="col-sm-6">
            <div class="d-flex align-items-center justify-content-md-end">
                <div class="mb-3 mb-xl-0 pr-1">
                    <div class="dropdown">
                        <button class="btn bg-white btn-sm dropdown-toggle btn-icon-text border mr-2" type="button"
                            id="choose-store" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php
                            if ($storeid == 'R001')
                                $storename = 'Rambla Kelapa Gading';
                            if ($storeid == 'R002')
                                $storename = 'Rambla Bandung';
                            if ($storeid == 'V001')
                                $storename = 'Happy Harvest Bandung';
                            ?>
                            <i class="typcn typcn-location mr-2"></i>
                            <?= $storename ?>
                        </button>
                        <div class="dropdown-menu opt-store" aria-labelledby="dropdownMenuSizeButton3"
                            data-x-placement="top-start" x-placement="bottom-start"
                            style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                            <h6 class="dropdown-header"></h6>
                            <?php foreach($site as $row) :?>
                                <a class="dropdown-item" style="cursor:pointer" data="<?= $row->branch_id; ?>"><?= $row->branch_name; ?></a>
                            <?php endforeach; ?>
                            <!-- <a class="dropdown-item" style="cursor:pointer" data="R001">Rambla Kelapa Gading</a>
                            <a class="dropdown-item" style="cursor:pointer" data="R002">Rambla Bandung</a>
                            <a class="dropdown-item" style="cursor:pointer" data="V001">Happy Harvest Bandung</a> -->
                            <div id="filter-store" class="d-none">
                                <form action="<?= base_url(); ?>SalesToday" method="post">
                                    <input type="text" name="storeid" id="">
                                    <input type="submit" value="go">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <div class="card-description h4 mb-1 font-weight-normal">Net Sales</div>
                            <h2 class="mb-2 mt-2  font-weight-bold">Rp
                                <?= $result[0]->net ?>
                            </h2>
                        </div>
                        <div class="display-2 text-warning">
                            <i class="fa fa-rocket" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <div class="card-description h4 mb-1 font-weight-normal">Gross Sales</div>
                            <h2 class="mb-2 mt-2 font-weight-bold">Rp
                                <?= $result[0]->gross ?>
                            </h2>
                        </div>
                        <div class="display-2 text-success">
                            <i class="fa fa-money" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-column">
                            <div class="card-description h4 mb-1 font-weight-normal">Total Trx</div>
                            <h2 class="mb-2 mt-2 font-weight-bold">
                                <?= $result[0]->tot_trx ?>
                            </h2>

                        </div>
                        <div class="display-2 text-info">
                            <i class="fa fa-exchange" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex  justify-content-between">
                        <div class="d-flex flex-column">
                            <div class="card-description h4 mb-1 font-weight-normal">Total Qty</div>
                            <h2 class="mb-2 mt-2 font-weight-bold">
                                <?= $result[0]->tot_qty ?>
                            </h2>
                        </div>
                        <div class="display-2" style="color:#f2125e !important;">
                            <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Target Today</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                       
                                    </th>
                                    <th>
                                        Total Trx
                                    </th>
                                    <th>
                                        Total Qty
                                    </th>
                                    <th>
                                        Sales Gross
                                    </th>
                                    <th>
                                        Sales Nett
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b>Sales Floor</b></td>
                                </tr>
                                <tr>
                                    <td><b>Sales Lantai 1</b></td>
                                </tr>
                                <tr>
                                    <td><b>Sales Lantai 2</b></td>
                                </tr>
                                <tr>
                                    <td><b>Sales Deptstore</b></td>
                                </tr>
                                <tr>
                                    <td><b>Sales Supermarket</b></td>
                                </tr>
                                <tr>
                                    <td><b>Sales Bazaar</b></td>
                                </tr>
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/vendor/chartjs/js/loader.js"></script>
<script src="<?= base_url(); ?>assets/vendor/chartjs/js/chart.js"></script>
<script type="text/JavaScript">
    $(function() {
        $('.opt-store .dropdown-item').on('click', function(){
            //console.log($(this).attr('data'));
            $('#choose-store').html('<i class="typcn typcn-location mr-2"></i>'+$(this).text());
            $('#filter-store input').val($(this).attr('data'));
            $('#filter-store form').trigger('submit');
        })
    });

</script>