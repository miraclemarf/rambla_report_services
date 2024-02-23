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
                            <h2 class="mb-2 mt-2  font-weight-bold">
                                <?= empty($result[0]->tot_trx) ? '-' : 'Rp'.$result[0]->net ?>
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
                            <h2 class="mb-2 mt-2 font-weight-bold">
                                <?= empty($result[0]->tot_trx) ? '-' : 'Rp'.$result[0]->gross ?>
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
                                <?= empty($result[0]->tot_trx) ? '-' :  $result[0]->tot_trx?>
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
                                <?= empty($result[0]->tot_trx) ? '-' : $result[0]->tot_qty ?>
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
                        <table class="table table-striped" >
                            <thead>
                                <tr>
                                    <th>
                                       
                                    </th>
                                    <th>
                                        <label class="badge badge-info"><h4 class="m-0">Total Trx</h4></label>
                                    </th>
                                    <th>
                                        <label class="badge badge-primary"><h4 class="m-0">Total Qty</h4></label>
                                    </th>
                                    <th>
                                        <label class="badge badge-success"><h4 class="m-0">Sales Gross</h4></label>
                                    </th>
                                    <th>
                                        <label class="badge badge-warning" style="color: #fff"><h4 class="m-0">Sales Nett</h4></label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($sales_allfl)): ?>
                                <tr>
                                    <td style="padding:20px;"><h4 class="m-0"><b><nobr>Sales Floor</nobr></b></h4></td>
                                    <td><h4 class="m-0"><?= $sales_allfl[0]->tot_trx ?> trx</h4></td>
                                    <td><h4 class="m-0"><?= $sales_allfl[0]->tot_qty ?> pcs</h4></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_allfl[0]->gross ?></h4></nobr></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_allfl[0]->net ?></h4></nobr></td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($sales_gf)): ?>
                                <tr>
                                    <td style="padding:20px;"><h4 class="m-0"><b><nobr>Sales Ground Floor</nobr></b></h4></td>
                                    <td><h4 class="m-0"><?= $sales_gf[0]->tot_trx ?> trx</h4></td>
                                    <td><h4 class="m-0"><?= $sales_gf[0]->tot_qty ?> pcs</h4></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_gf[0]->gross ?></h4></nobr></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_gf[0]->net ?></h4></nobr></td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($sales_fl1)): ?>
                                <tr>
                                    <td style="padding:20px;"><h4 class="m-0"><b><nobr>Sales Lantai 1</nobr></b></h4></td>
                                    <td><h4 class="m-0"><?= $sales_fl1[0]->tot_trx ?> trx</h4></td>
                                    <td><h4 class="m-0"><?= $sales_fl1[0]->tot_qty ?> pcs</h4></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_fl1[0]->gross ?></h4></nobr></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_fl1[0]->net ?></h4></nobr></td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($sales_fl2)): ?>
                                <tr>
                                    <td style="padding:20px;"><h4 class="m-0"><b><nobr>Sales Lantai 2</nobr></b></h4></td>
                                    <td><h4 class="m-0"><?= $sales_fl2[0]->tot_trx ?> trx</h4></td>
                                    <td><h4 class="m-0"><?= $sales_fl2[0]->tot_qty ?> pcs</h4></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_fl2[0]->gross ?></h4></nobr></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_fl2[0]->net ?></h4></nobr></td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($sales_rd)): ?>
                                <tr>
                                    <td style="padding:20px;"><h4 class="m-0"><b><nobr>Sales Deptstore</nobr></b></h4></td>
                                    <td><h4 class="m-0"><?= $sales_rd[0]->tot_trx ?> trx</h4></td>
                                    <td><h4 class="m-0"><?= $sales_rd[0]->tot_qty ?> pcs</h4></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_rd[0]->gross ?></h4></nobr></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_rd[0]->net ?></h4></nobr></td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($sales_rs)): ?>
                                <tr>
                                    <td style="padding:20px;"><h4 class="m-0"><b><nobr>Sales Supermarket</nobr></b></h4></td>
                                    <td><h4 class="m-0"><?= $sales_rs[0]->tot_trx ?> trx</h4></td>
                                    <td><h4 class="m-0"><?= $sales_rs[0]->tot_qty ?> pcs</h4></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_rs[0]->gross ?></h4></nobr></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_rs[0]->net ?></h4></nobr></td>
                                </tr>
                                <?php endif; ?>
                                <?php if(isset($sales_bazaar)): ?>
                                <tr>
                                    <td style="padding:20px;"><h4 class="m-0"><b><nobr>Sales Bazaar</nobr></b></h4></td>
                                    <td><h4 class="m-0"><?= $sales_bazaar[0]->tot_trx ?> trx</h4></td>
                                    <td><h4 class="m-0"><?= $sales_bazaar[0]->tot_qty ?> pcs</h4></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_bazaar[0]->gross ?></h4></nobr></td>
                                    <td><nobr><h4 class="m-0">Rp <?= $sales_bazaar[0]->net ?></h4></nobr></td>
                                </tr>
                                <?php endif; ?>
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