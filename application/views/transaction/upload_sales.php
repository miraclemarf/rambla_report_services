<style>
    @media (max-width: 575.98px) {
    .card {
        height: 100%;
        /* background: gold; */
    }

    .embed-responsive {
        height: 100%;
    }
    .member-only {
        padding: 15px;
    }
}


</style>
<div class="content-wrapper">
<?php $this->load->view('modal/filter-uploadsales', true); ?>
<?php $this->load->view('modal/detail-moresales', true); ?>

    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0 font-weight-bold">List Upload Transaction</h3>
            <!-- <p>Sales 7 Hari Terkahir -->
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
                                $storename = 'Rambla Kelapa Gading (R001)';
                            if ($storeid == 'R002')
                                $storename = 'Rambla Bandung (R002)';
                            if ($storeid == 'V001')
                                $storename = 'Happy Harvest Bandung (V001)';
                            if ($storeid == 'S002')
                                $storename = 'Star SMS (S002)';
                            if ($storeid == 'S003')
                                $storename = 'Star SMB (S003)';
                            if ($storeid == 'V002')
                                $storename = 'Happy Harvest Bogor (V002)';
                            if ($storeid == 'V003')
                                $storename = 'Happy Harvest Bekasi (V003)';
                            ?>
                            <i class="typcn typcn-location mr-2"></i>
                            <?= $storename ?>
                        </button>
                        <div class="dropdown-menu opt-store" aria-labelledby="dropdownMenuSizeButton3"
                            data-x-placement="top-start" x-placement="bottom-start"
                            style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                            <h6 class="dropdown-header"></h6>
                            <?php foreach ($site as $row) : ?>
                                <a class="dropdown-item" style="cursor:pointer" data-store= "<?= $row->branch_id; ?>" data="<?= $row->branch_id; ?>"><?= $row->branch_name; ?> (<?= $row->branch_id; ?>)</a>
                            <?php endforeach; ?>
                            <!-- <a class="dropdown-item" style="cursor:pointer" data="R001">Rambla Kelapa Gading</a>
                            <a class="dropdown-item" style="cursor:pointer" data="R002">Rambla Bandung</a>
                            <a class="dropdown-item" style="cursor:pointer" data="V001">Happy Harvest Bandung</a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">

        <div class="col-xl-12 grid-margin stretch-card">
            
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                            <div>
                                <!-- <h4 class="card-title mb-0">Laporan Penjualan By Artikel</h4>
                                <p class="text-muted mb-2">Terapkan filter untuk menampilkan data.</p> -->
                            </div> 
                            <div class="align-self-end">
                                <button type="button" class="btn btn-info btn-sm btn-icon-text btn-filter-uploadsales" style="float:right">
                                    <i class="typcn typcn-filter"></i>
                                    Filter
                                </button>
                                <button type="button" class="btn btn-success btn-sm btn-icon-text btn-upload-sales mr-2" style="float:right">
                                    <i class="typcn typcn-upload menu-icon"></i>
                                    Upload
                                </button>
                            </div>
                        </div>

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#home">Draft</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab1">Review</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab2">Setuju</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab3">Batal</a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div id="home" class="tab-pane active m-0"><br>
                                <table class="table table-striped table-custom d-none" id="tb_sales_upload1">
                                    <thead class="table-rambla">
                                        <tr>
                                            <th>#</th>
                                            <th>
                                                <nobr>Barcode</nobr>
                                            </th>
                                            <th>
                                                <nobr>Article Name</nobr>
                                            </th>
                                            <th>
                                                <nobr>Quantity</nobr>
                                            </th>
                                            <th>
                                                <nobr>Price Item</nobr>
                                            </th>
                                            <th>
                                                <nobr>Disc Percentage</nobr>
                                            </th>
                                            <th>
                                                <nobr>More Disc Percentage</nobr>
                                            </th>
                                            <th>
                                                <nobr>Net Price</nobr>
                                            </th>
                                            <th>
                                                <nobr>Market Place</nobr>
                                            </th>
                                            <th>
                                                <nobr>Payment Type</nobr>
                                            </th>
                                            <th>
                                                <nobr>No Ref</nobr>
                                            </th>
                                            <th>
                                                <nobr>Upload Time</nobr>
                                            </th>
                                            <th>
                                                <nobr>Upload By</nobr>
                                            </th>
                                            <th>
                                                <nobr>Action</nobr>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="tab1" class="tab-pane m-0 fade"><br>
                                <table class="table table-striped table-custom d-none" id="tb_sales_upload2">
                                    <thead class="table-rambla">
                                        <tr>
                                            <th>#</th>
                                            <th>
                                                <nobr>Barcode</nobr>
                                            </th>
                                            <th>
                                                <nobr>Article Name</nobr>
                                            </th>
                                            <th>
                                                <nobr>Quantity</nobr>
                                            </th>
                                            <th>
                                                <nobr>Price Item</nobr>
                                            </th>
                                            <th>
                                                <nobr>Disc Percentage</nobr>
                                            </th>
                                            <th>
                                                <nobr>More Disc Percentage</nobr>
                                            </th>
                                            <th>
                                                <nobr>Net Price</nobr>
                                            </th>
                                            <th>
                                                <nobr>Market Place</nobr>
                                            </th>
                                            <th>
                                                <nobr>Payment Type</nobr>
                                            </th>
                                            <th>
                                                <nobr>No Ref</nobr>
                                            </th>
                                            <th>
                                                <nobr>Upload Time</nobr>
                                            </th>
                                            <th>
                                                <nobr>Upload By</nobr>
                                            </th>
                                            <th>
                                                <nobr>Approve By</nobr>
                                            </th>
                                            <th>
                                                <nobr>Approve Date</nobr>
                                            </th>
                                            <th>
                                                <nobr>Cancel By</nobr>
                                            </th>
                                            <th>
                                                <nobr>Cancel Date</nobr>
                                            </th>
                                            <th>
                                                <nobr>Action</nobr>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="tab2" class="tab-pane m-0 fade"><br>
                                <table class="table table-striped table-custom d-none" id="tb_sales_upload3">
                                    <thead class="table-rambla">
                                        <tr>
                                            <th>#</th>
                                            <th>
                                                <nobr>Barcode</nobr>
                                            </th>
                                            <th>
                                                <nobr>Article Name</nobr>
                                            </th>
                                            <th>
                                                <nobr>Quantity</nobr>
                                            </th>
                                            <th>
                                                <nobr>Price Item</nobr>
                                            </th>
                                            <th>
                                                <nobr>Disc Percentage</nobr>
                                            </th>
                                            <th>
                                                <nobr>More Disc Percentage</nobr>
                                            </th>
                                            <th>
                                                <nobr>Net Price</nobr>
                                            </th>
                                            <th>
                                                <nobr>Market Place</nobr>
                                            </th>
                                            <th>
                                                <nobr>Payment Type</nobr>
                                            </th>
                                            <th>
                                                <nobr>No Ref</nobr>
                                            </th>
                                            <th>
                                                <nobr>Upload Time</nobr>
                                            </th>
                                            <th>
                                                <nobr>Upload By</nobr>
                                            </th>
                                            <th>
                                                <nobr>Approve By</nobr>
                                            </th>
                                            <th>
                                                <nobr>Approve Date</nobr>
                                            </th>
                                            <th>
                                                <nobr>Cancel By</nobr>
                                            </th>
                                            <th>
                                                <nobr>Cancel Date</nobr>
                                            </th>
                                            <th>
                                                <nobr>Action</nobr>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="tab3" class="tab-pane m-0 fade"><br>
                                <table class="table table-striped table-custom d-none" id="tb_sales_upload4">
                                    <thead class="table-rambla">
                                        <tr>
                                            <th>#</th>
                                            <th>
                                                <nobr>Barcode</nobr>
                                            </th>
                                            <th>
                                                <nobr>Article Name</nobr>
                                            </th>
                                            <th>
                                                <nobr>Quantity</nobr>
                                            </th>
                                            <th>
                                                <nobr>Price Item</nobr>
                                            </th>
                                            <th>
                                                <nobr>Disc Percentage</nobr>
                                            </th>
                                            <th>
                                                <nobr>More Disc Percentage</nobr>
                                            </th>
                                            <th>
                                                <nobr>Net Price</nobr>
                                            </th>
                                            <th>
                                                <nobr>Market Place</nobr>
                                            </th>
                                            <th>
                                                <nobr>Payment Type</nobr>
                                            </th>
                                            <th>
                                                <nobr>No Ref</nobr>
                                            </th>
                                            <th>
                                                <nobr>Upload Time</nobr>
                                            </th>
                                            <th>
                                                <nobr>Upload By</nobr>
                                            </th>
                                            <th>
                                                <nobr>Approve By</nobr>
                                            </th>
                                            <th>
                                                <nobr>Approve Date</nobr>
                                            </th>
                                            <th>
                                                <nobr>Cancel By</nobr>
                                            </th>
                                            <th>
                                                <nobr>Cancel Date</nobr>
                                            </th>
                                            <th>
                                                <nobr>Upload By</nobr>
                                            </th>
                                            <th>
                                                <nobr>Action</nobr>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- <h4 class="card-title">Target Today</h4> -->
                        
                    </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/vendor/chartjs/js/loader.js"></script>
<script src="<?= base_url(); ?>assets/vendor/chartjs/js/chart.js"></script>
<script type="text/JavaScript">
    // console.log(store);
    var base_url = '<?= base_url(); ?>';
    var table1 = null;
    var table2 = null;
    var table3 = null;
    var store = $('.opt-store .dropdown-item').data('store');


    function verif(status, no_ref, store){
        update_status(status, no_ref, store);
    }

    function batal(status, no_ref, store){
        update_status(status, no_ref, store);
    }

    function update_status(status, no_ref, store){
        $.ajax({
            type: "POST",
            url: "<?= base_url('Transaction'); ?>/update_transaksi",
            dataType: "json",
            data: {
                "no_ref": no_ref,
                "store" : store,
                "status": status
            },
            success: function(data) {
                if(data.status == "1"){
                    swal("Success", data["message"] , "success");
                }else{
                    swal("Oops !", data["message"] , "error");
                }
                setTimeout(
                function() {
                    window.location = base_url+"Transaction/upload_sales";
                }, 2000);
            },
            beforeSend: function(){
                swal("Loading", "Harap Tunggu..." , "warning");
            },
            error: function (jqXHR, exception) {
                console.log(exception);
                swal("Oops !", "Harap Hubungi IT" , "error");
            },
        });
    }

    function hapus(no_ref, store){
        $.ajax({
            type: "POST",
            url: "<?= base_url('Transaction'); ?>/hapus_transaksi",
            dataType: "json",
            data: {
                "no_ref": no_ref,
                "store" : store
            },
            success: function(data) {
                if(data.status == "success"){
                    swal("Success", data.msg , "success");
                }else{
                    swal("Oops !", data.msg , "error");
                }
                setTimeout(
                function() {
                    window.location = base_url+"Transaction/upload_sales";
                }, 2000);
            },
            beforeSend: function(){
                swal("Loading", "Harap Tunggu..." , "warning");
            },
            error: function (jqXHR, exception) {
                console.log(exception);
                swal("Oops !", "Harap Hubungi IT" , "error");
            },
        });
    }
                                
    $(document).ready(function() {
        
        var params1 = null; 
        var params2 = null;
        var params3 = periode;
        var params4 = null;
        var marketplace = null;
        
        load_data_upload_sales_excel('D',store, params2, params3);
        load_data_upload_sales_excel('R',store, params2, params3);
        load_data_upload_sales_excel('B',store, params2, params3);
        // load_data_upload_sales_excel('S',store, params2, params3);

        $(function() {
            $('.opt-store .dropdown-item').on('click', function(){
                //console.log($(this).attr('data'));
                $('#choose-store').html('<i class="typcn typcn-location mr-2"></i>'+$(this).text());
                $('#filter-store input').val($(this).attr('data'));
                // $('#filter-store form').trigger('submit');

                store = $(this).data('store');
                load_data_upload_sales_excel('D',store, params2, params3);
                load_data_upload_sales_excel('R',store, params2, params3);
                load_data_upload_sales_excel('B',store, params2, params3);
            })
        });

        // START REGISTER
        // function get_register() {
        //     $.ajax({
        //         type: "POST",
        //         url: "<?= base_url('Masterdata'); ?>/get_list_register",
        //         dataType: "html",
        //         data: {
        //             "store" : store
        //         },
        //         success: function(data) {
        //             // console.log(data);
        //             $(".loading").hide();
        //             $("#filter-pushsales").show();
        //             $('.list_register').html(data);
        //         },
        //         beforeSend: function(xhr) {
        //             // console.log(xhr);
        //             $(".loading").show();
        //             $("#filter-pushsales").hide();
        //         }
        //     });
        // }

        $('.btn-filter-uploadsales').on("click", function() {
            $('#modal-filter-uploadsales').modal('show');
        });

        $('.btn-upload-sales').on("click", function() {
            location.href = base_url+"Transaction/import_page/"+store;
        });
        
        $('.list_marketplace').on('change', function(e) {
            marketplace = this.value;
        });

        $('.btn-submit-filter').on("click", function() {
            params1 = null;
            params2 = marketplace;
            params3 = periode;
            if (params1 === "") {
                params1 = null;
            }
            if (params2 === "") {
                params2 = null;
            }
            if (params3 === "") {
                params3 = null;
            }
            
            load_data_upload_sales_excel('D',store, params2, params3);
            load_data_upload_sales_excel('R',store, params2, params3);
            load_data_upload_sales_excel('B',store, params2, params3);
        });

        function load_data_upload_sales_excel(status, store, params2, params3) {
            var id_table = "";
            if(status == "D"){
                id_table = "tb_sales_upload1";
            }else if(status == "R"){
                id_table = "tb_sales_upload2";
            }else if(status == "S"){
                id_table = "tb_sales_upload3";
            }else if(status == "B"){
                id_table = "tb_sales_upload4";
            }
            var transaksiSeen = new Set();
            $('#'+id_table+'').removeClass('d-none');
            table1 = $('#'+id_table+'').DataTable({
                "processing": true,
                "responsive": true,
                "serverSide": true,
                "serverMethod": "post",
                "bDestroy": true,
                "ordering": true, // Set true agar bisa di sorting
                "order": [
                    [0, "asc"]
                ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
                "ajax": {
                    "url": "<?= base_url('Transaction/list_sales_upload'); ?>", // URL file untuk proses select datanya
                    "type": "POST",
                    "data": {
                        "status": status,
                        "store" : store,
                        "params2": params2,
                        "params3":params3
                    },
                },
                "scrollX": true,
                "deferRender": true,
                "aLengthMenu": [
                    [10, 25, 50],
                    [10, 25, 50]
                ], // Combobox Limit
                "columns": [{
                        "data": 'barcode',
                        "sortable": false,
                        // "render": function ( data, type, row, meta ) {
                        //     var i = meta.row + meta.settings._iDisplayStart + 1;
                        //     return '<div class="form-check"><label class="form-check-label text-muted"><input type="checkbox" class="form-check-input" name="checkbox_'+i+'"><i class="input-helper"></i></label></div>';
                        // },
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        "data": "barcode",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "article_name",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "quantity",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "price_item",
                        "render": function(data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "disc_pct",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "more_disc_pct",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "net_price",
                        "render": function(data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "payment_type",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "marketplace",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "no_ref",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "upload_date",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "upload_by",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "",
                        "render": function(data, type, row) {
                            if(!transaksiSeen.has(row.no_ref)){
                                transaksiSeen.add(row.no_ref);
                                if(status == "D"){
                                    return `<nobr ><button type="button" onclick="hapus('`+row.no_ref+`','`+store+`')" class="btn btn-danger btn-sm">Hapus</button> | <button type="button" onclick="verif('R','`+row.no_ref+`','`+store+`')" class="btn btn-info btn-sm btn-verif">Verifikasi</button></nobr>`;
                                }else if(status == "R"){
                                    return `<nobr ><button type="button" onclick="batal('B','`+row.no_ref+`','`+store+`')" class="btn btn-danger btn-sm">Batal</button> | <button type="button" onclick="approve('`+row.no_ref+`')" class="btn btn-success btn-sm btn-approve">Approve</button></nobr>`;
                                } else {
                                    return ``;
                                }
                                
                            } else {
                                return ``;
                            }
                        },
                    },
                ],
            });
        }

        function load_data_upload_sales(params1, params2, params3, store, params4) {
            $('#tb_saleshistory_list').removeClass('d-none');
            tabel = $('#tb_saleshistory_list').DataTable({
                "processing": true,
                "responsive": true,
                "serverSide": true,
                "serverMethod": "post",
                "bDestroy": true,
                "ordering": true, // Set true agar bisa di sorting
                "order": [
                    [0, "asc"]
                ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
                "ajax": {
                    "url": "<?= base_url('Transaction/list_sales_today'); ?>", // URL file untuk proses select datanya
                    "type": "POST",
                    "data": {
                        "store": store,
                        "params1": params1,
                        "params2": params2,
                        "params3": params3,
                        "params4": params4
                    },
                },
                "scrollX": true,
                "deferRender": true,
                "aLengthMenu": [
                    [10, 25, 50],
                    [10, 25, 50]
                ], // Combobox Limit
                "columns": [{
                        "data": 'periode',
                        "sortable": false,
                        // "render": function ( data, type, row, meta ) {
                        //     var i = meta.row + meta.settings._iDisplayStart + 1;
                        //     return '<div class="form-check"><label class="form-check-label text-muted"><input type="checkbox" class="form-check-input" name="checkbox_'+i+'"><i class="input-helper"></i></label></div>';
                        // },
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        "data": "trans_no",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "periode",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "trans_status",
                        "render": function(data, type, row) {
                            return '<nobr>' + row.status_desc + '</nobr>';
                        },
                    },
                    {
                        "data": "trans_time",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "cashier_id",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "kode_register",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "jml_record",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "tot_qty",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "tot_berat",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "net_price",
                        "render": function(data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "",
                        "render": function(data, type, row) {
                            return `<nobr ><button type="button" onclick="more('`+row.trans_no+`')" class="btn btn-light btn-sm btn-more"><i class="typcn typcn-eye btn-icon-prepend"></i> More</button></nobr>`;
                        },
                    },
                ],
            });
        }
    });

    function more(trans_no){
        $('#modal-detail-moresales').modal('show');
        load_data_paid_sales(trans_no);
        load_data_detail_sales(trans_no);
        // $('#btnTrigger').click();
    }

    function load_data_detail_sales(trans_no) {
        $('#tb_sales_dtl').removeClass('d-none');
        tabel3 = $('#tb_sales_dtl').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "serverMethod": "post",
            "bDestroy": true,
            "ordering": true, // Set true agar bisa di sorting
            "order": [
                [0, "asc"]
            ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
            "ajax": {
                "url": "<?= base_url('Transaction/list_sales_detail_today'); ?>", // URL file untuk proses select datanya
                "type": "POST",
                "data": {
                    "store": store,
                    "trans_no": trans_no
                },
            },
            "scrollX": true,
            "deferRender": true,
            "aLengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ], // Combobox Limit
            "columns": [{
                    "data": 'no_urut',
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    "data": "trans_no",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
                {
                    "data": "barcode",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
                {
                    "data": "article_code",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
                {
                    "data": "supplier_pcode",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
                {
                    "data": "supplier_pname",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
                {
                    "data": "qty",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
                {
                    "data": "berat",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
                {
                    "data": "price",
                    "render": function(data, type, row) {
                        return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                    },
                },
                {
                    "data": "disc_pct",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
                {
                    "data": "disc_amt",
                    "render": function(data, type, row) {
                        return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                    },
                },
                {
                    "data": "moredisc_pct",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
                {
                    "data": "moredisc_amt",
                    "render": function(data, type, row) {
                        return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                    },
                },
                {
                    "data": "net_price",
                    "render": function(data, type, row) {
                        return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                    },
                },
                {
                    "data": "no_ref",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
            ],
        });
    }

    function load_data_paid_sales(trans_no) {
        $('#tb_paid').removeClass('d-none');
        tabel2 = $('#tb_paid').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "serverMethod": "post",
            "bDestroy": true,
            "ordering": true, // Set true agar bisa di sorting
            "order": [
                [0, "asc"]
            ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
            "ajax": {
                "url": "<?= base_url('Transaction/list_paid_today'); ?>", // URL file untuk proses select datanya
                "type": "POST",
                "data": {
                    "store": store,
                    "trans_no": trans_no
                },
            },
            "scrollX": true,
            "deferRender": true,
            "aLengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ], // Combobox Limit
            "columns": [{
                    "data": 'no_urut',
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    "data": "trans_no",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
                {
                    "data": "card_number",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
                {
                    "data": "card_name",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
                {
                    "data": "paid_amount",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
                {
                    "data": "description",
                    "render": function(data, type, row) {
                        return '<nobr>' + data + '</nobr>';
                    },
                },
            ],
        });
    }
    


</script>


   