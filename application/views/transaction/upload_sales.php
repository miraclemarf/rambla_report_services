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
    <?php $this->load->view('modal/detail-uploadsales', true); ?>

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
                                <a class="dropdown-item" style="cursor:pointer" data-store="<?= $row->branch_id; ?>" data="<?= $row->branch_id; ?>"><?= $row->branch_name; ?> (<?= $row->branch_id; ?>)</a>
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

                    <ul class="nav nav-tabs multi-tab">
                        <li class="nav-item">
                            <a class="nav-link active draft" data-toggle="tab" href="#home">Draft</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link review" data-toggle="tab" href="#tab1">Review</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link setuju" data-toggle="tab" href="#tab2">Setuju</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link batal" data-toggle="tab" href="#tab3">Batal</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="home" class="tab-pane active m-0">
                            <button type="button" class="btn btn-danger btn-icon-text btn-hapus" disabled>
                                <i class="typcn typcn-trash btn-icon-prepend"></i>
                                Hapus
                            </button>
                            <button type="button" class="btn btn-info btn-icon-text btn-verif ml-1" disabled>
                                <i class="typcn typcn-folder btn-icon-prepend"></i>
                                Submit
                            </button>
                            <br><br>
                            <table class="table table-striped table-custom d-none" id="tb_sales_upload1">
                                <thead class="table-rambla">
                                    <tr>
                                        <th><input type="checkbox" class="select-all"></th>
                                        <th>
                                            <nobr>Trans No</nobr>
                                        </th>
                                        <th>
                                            <nobr>No Ref</nobr>
                                        </th>
                                        <th>
                                            <nobr>Marketplace</nobr>
                                        </th>
                                        <th>
                                            <nobr>Quantity</nobr>
                                        </th>
                                        <th>
                                            <nobr>Price Item</nobr>
                                        </th>
                                        <th>
                                            <nobr>Net Price</nobr>
                                        </th>
                                        <th>
                                            <nobr>Upload By</nobr>
                                        </th>
                                        <th>
                                            <nobr>Upload Time</nobr>
                                        </th>
                                        <th>
                                            <nobr>Approve By</nobr>
                                        </th>
                                        <th>
                                            <nobr>Approve Time</nobr>
                                        </th>
                                        <th>
                                            <nobr>Cancel By</nobr>
                                        </th>
                                        <th>
                                            <nobr>Cancel Time</nobr>
                                        </th>
                                        <th>
                                            <nobr>Action</nobr>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div id="tab1" class="tab-pane m-0">
                            <button type="button" class="btn btn-warning btn-icon-text btn-batal text-white" disabled>
                                <i class="typcn typcn-cancel btn-icon-prepend"></i>
                                Batal
                            </button>
                            <button type="button" class="btn btn-success btn-icon-text btn-approve ml-1" disabled>
                                <i class="typcn typcn-input-checked btn-icon-prepend"></i>
                                Approve
                            </button>
                            <br><br>
                            <table class="table table-striped table-custom d-none" id="tb_sales_upload2">
                                <thead class="table-rambla">
                                    <tr>
                                        <th><input type="checkbox" class="select-all"></th>
                                        <th>
                                            <nobr>Trans No</nobr>
                                        </th>
                                        <th>
                                            <nobr>No Ref</nobr>
                                        </th>
                                        <th>
                                            <nobr>Marketplace</nobr>
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
                                            <nobr>Upload By</nobr>
                                        </th>
                                        <th>
                                            <nobr>Upload Time</nobr>
                                        </th>
                                        <th>
                                            <nobr>Approve By</nobr>
                                        </th>
                                        <th>
                                            <nobr>Approve Time</nobr>
                                        </th>
                                        <th>
                                            <nobr>Cancel By</nobr>
                                        </th>
                                        <th>
                                            <nobr>Cancel Time</nobr>
                                        </th>
                                        <th>
                                            <nobr>Action</nobr>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div id="tab2" class="tab-pane m-0"><br>
                            <table class="table table-striped table-custom d-none" id="tb_sales_upload3">
                                <thead class="table-rambla">
                                    <tr>
                                        <th><input type="checkbox" class="select-all"></th>
                                        <th>
                                            <nobr>Trans No</nobr>
                                        </th>
                                        <th>
                                            <nobr>No Ref</nobr>
                                        </th>
                                        <th>
                                            <nobr>Marketplace</nobr>
                                        </th>
                                        <th>
                                            <nobr>Quantity</nobr>
                                        </th>
                                        <th>
                                            <nobr>Price Item</nobr>
                                        </th>
                                        <th>
                                            <nobr>Net Price</nobr>
                                        </th>
                                        <th>
                                            <nobr>Upload By</nobr>
                                        </th>
                                        <th>
                                            <nobr>Upload Time</nobr>
                                        </th>
                                        <th>
                                            <nobr>Approve By</nobr>
                                        </th>
                                        <th>
                                            <nobr>Approve Time</nobr>
                                        </th>
                                        <th>
                                            <nobr>Cancel By</nobr>
                                        </th>
                                        <th>
                                            <nobr>Cancel Time</nobr>
                                        </th>
                                        <th>
                                            <nobr>Action</nobr>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div id="tab3" class="tab-pane m-0"><br>
                            <table class="table table-striped table-custom d-none" id="tb_sales_upload4">
                                <thead class="table-rambla">
                                    <tr>
                                        <th><input type="checkbox" class="select-all"></th>
                                        <th>
                                            <nobr>Trans No</nobr>
                                        </th>
                                        <th>
                                            <nobr>No Ref</nobr>
                                        </th>
                                        <th>
                                            <nobr>Marketplace</nobr>
                                        </th>
                                        <th>
                                            <nobr>Quantity</nobr>
                                        </th>
                                        <th>
                                            <nobr>Price Item</nobr>
                                        </th>
                                        <th>
                                            <nobr>Net Price</nobr>
                                        </th>
                                        <th>
                                            <nobr>Upload By</nobr>
                                        </th>
                                        <th>
                                            <nobr>Upload Time</nobr>
                                        </th>
                                        <th>
                                            <nobr>Approve By</nobr>
                                        </th>
                                        <th>
                                            <nobr>Approve Time</nobr>
                                        </th>
                                        <th>
                                            <nobr>Cancel By</nobr>
                                        </th>
                                        <th>
                                            <nobr>Cancel Time</nobr>
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
    var role = '<?= $this->input->cookie('cookie_invent_tipe') ?>';
    var table1 = null;
    var table2 = null;
    var table3 = null;
    var store = $('.opt-store .dropdown-item').data('store');

                                
    $(document).ready(function() {
        
        var params1 = null; 
        var params2 = null;
        var params3 = periode;
        var params4 = null;
        var marketplace = null;

        load_data_upload_sales_excel('D',store, params2, params3);
        $('.draft').click(function(){
            load_data_upload_sales_excel('D',store, params2, params3);
        })
        $('.review').click(function(){
            load_data_upload_sales_excel('R',store, params2, params3);
        }) 
        $('.setuju').click(function(){
            load_data_upload_sales_excel('S',store, params2, params3);
        }) 
        $('.batal').click(function(){
            load_data_upload_sales_excel('B',store, params2, params3);
        }) 

        if(role == "3"){
            $('.btn-hapus').show();
            $('.btn-verif').show();
            $('.btn-batal').hide();
            $('.btn-approve').hide();
        }
        if(role == "4"){
            $('.btn-hapus').hide();
            $('.btn-verif').hide();
            $('.btn-batal').show();
            $('.btn-approve').show();
        }

        $(function() {
            $('.opt-store .dropdown-item').on('click', function(){
                //console.log($(this).attr('data'));
                $('#choose-store').html('<i class="typcn typcn-location mr-2"></i>'+$(this).text());
                $('#filter-store input').val($(this).attr('data'));
                // $('#filter-store form').trigger('submit');

                store = $(this).data('store');
                load_data_upload_sales_excel('D',store, params2, params3);
            })
        });

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
            var activeTabText = $('.multi-tab .nav-item .nav-link.active').text();
            if(activeTabText == "Draf"){
                load_data_upload_sales_excel('D',store, params2, params3);
            }else if(activeTabText == "Review"){
                load_data_upload_sales_excel('R',store, params2, params3);
            }else if(activeTabText == "Setuju"){
                load_data_upload_sales_excel('S',store, params2, params3);
            }else if(activeTabText == "Batal"){
                load_data_upload_sales_excel('B',store, params2, params3);
            }
            
           
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
            // var transaksiSeen = new Set();
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
                    "url": "<?= base_url('Transaction/list_header_sales_upload'); ?>", // URL file untuk proses select datanya
                    "type": "POST",
                    "data": {
                        "status": status,
                        "store" : store,
                        "params2": params2,
                        "params3":params3
                    },
                    "error": function(xhr, error, thrown) {
                        let response = xhr.responseJSON;
                        let message = response && response.error ? response.error : 'Data gagal dimuat. silakan di refresh kembali!';
                        swal("Oops !", message , "error");
                    }
                },
                "scrollX": true,
                "deferRender": true,
                "aLengthMenu": [
                    [10, 25, 50],
                    [10, 25, 50]
                ], // Combobox Limit
                "columns": [{
                        "data": 'no_ref',
                        "sortable": false,
                        "render": function ( data, type, row, meta ) {
                            var i = meta.row + meta.settings._iDisplayStart + 1;
                            return '<div class="form-check"><label class="form-check-label text-muted"><input type="checkbox" class="form-check-input row-checkbox" name="checkbox_'+i+'"><i class="input-helper"></i></label></div>';
                        },
                        // "render": function(data, type, row, meta) {
                        //     return meta.row + meta.settings._iDisplayStart + 1;
                        // },
                    },
                    {
                        "data": "trans_no",
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
                        "data": "marketplace",
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
                        "data": "net_price",
                        "render": function(data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "upload_by",
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
                        "data": "approve_by",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "approve_date",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "cancel_by",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "cancel_date",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "",
                        "render": function(data, type, row) {
                            // console.log(transaksiSeen);
                            return `<nobr><button type="button" onclick="show('`+row.no_ref+`','`+store+`')" class="btn btn-secondary btn-sm">Show</button></nobr>`;
                        },
                    },
                ],
            });

            // Handle Select All Checkbox
            $('.select-all').on('click', function(){
                var isChecked = $(this).prop('checked');
                // console.log(isChecked);
                $('.row-checkbox').prop('checked', isChecked);
                var checkedCount = $('.row-checkbox:checked').length;
                if (checkedCount > 0) {
                    $('.btn-verif').prop("disabled",false);
                    $('.btn-hapus').prop("disabled",false);
                    $('.btn-batal').prop("disabled",false);
                    $('.btn-approve').prop("disabled",false);
                } else {
                    $('.btn-verif').prop("disabled",true);
                    $('.btn-hapus').prop("disabled",true);
                    $('.btn-batal').prop("disabled",true);
                    $('.btn-approve').prop("disabled",true);
                }
            });

            // Handle individual row checkbox change
            $('#'+id_table+' tbody').on('change', '.row-checkbox', function() {
                // console.log($('.row-checkbox:checked').length);
                var isChecked = $('.row-checkbox:checked').length == $('.row-checkbox').length;
                $('.select-all').prop('checked', isChecked);
                // console.log(isChecked);
                var checkedCount = $('.row-checkbox:checked').length;
                if (checkedCount > 0) {
                    $('.btn-verif').prop("disabled",false);
                    $('.btn-hapus').prop("disabled",false);
                    $('.btn-batal').prop("disabled",false);
                    $('.btn-approve').prop("disabled",false);
                } else {
                    $('.btn-verif').prop("disabled",true);
                    $('.btn-hapus').prop("disabled",true);
                    $('.btn-batal').prop("disabled",true);
                    $('.btn-approve').prop("disabled",true);
                }
            });

            // Handle Delete Selected Button
            $('.btn-verif').off('click').on('click', function() {
                var selectedRows = [];
                $('.row-checkbox:checked').each(function() {
                    var rowData = table1.row($(this).closest('tr')).data();
                    selectedRows.push(rowData);
                });

                if (selectedRows.length > 0) {
                    // console.log("Selected rows to delete:", selectedRows);
                    update_status('R', selectedRows, store);
                } else {
                    alert("Please select at least one row.");
                }
            });

            // Handle Delete Selected Button
            $('.btn-batal').off('click').on('click', function() {
                var selectedRows = [];
                $('.row-checkbox:checked').each(function() {
                    var rowData = table1.row($(this).closest('tr')).data();
                    selectedRows.push(rowData);
                });

                if (selectedRows.length > 0) {
                    // console.log("Selected rows to delete:", selectedRows);
                    update_status('B', selectedRows, store);
                } else {
                    alert("Please select at least one row.");
                }
            });

            $('.btn-approve').off('click').on('click', function() {
                var selectedRows = [];
                $('.row-checkbox:checked').each(function() {
                    var rowData = table1.row($(this).closest('tr')).data();
                    selectedRows.push(rowData);
                });

                if (selectedRows.length > 0) {
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url('Transaction'); ?>/submit_transaksi",
                        dataType: "json",
                        data: {
                            "selectedRows": selectedRows,
                            "store"       : store
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
                            swal("Oops !", "Harap Hubungi IT" , "error");
                        },
                    });
                } else {
                    alert("Please select at least one row.");
                }
            });

            $('.btn-hapus').off('click').on('click', function() {
                if (confirm("Anda yakin mau hapus data ini?")) {
                    var selectedRows = [];
                    $('.row-checkbox:checked').each(function() {
                        var rowData = table1.row($(this).closest('tr')).data();
                        selectedRows.push(rowData);
                    });

                    if (selectedRows.length > 0) {
                        $.ajax({
                            type: "POST",
                            url: "<?= base_url('Transaction'); ?>/hapus_transaksi",
                            dataType: "json",
                            data: {
                                "selectedRows": selectedRows,
                                "store"       : store
                            },
                            success: function(data) {
                                // console.log(data);
                                if(data.status == "1"){
                                    swal("Success", data["message"] , "success");
                                    table1.row($(this).closest('tr')).remove().draw();
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
                                swal("Oops !", "Harap Hubungi IT" , "error");
                            },
                        });
                    } else {
                        alert("Please select at least one row.");
                    }
                } 
            });
        }
    });

    function update_status(status, selectedRows, store){
        $.ajax({
            type: "POST",
            url: "<?= base_url('Transaction'); ?>/update_transaksi",
            dataType: "json",
            data: {
                "selectedRows": selectedRows,
                "status"      : status,
                "store"       : store
            },
            success: function(data) {
                // console.log(data);
                //table1.row($(this).closest('tr')).remove().draw();
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
                swal("Oops !", "Harap Hubungi IT" , "error");
            },
        });
    }

    function show(no_ref, store){
        $('#modal-detail-uploadsales').modal('show');
        load_data_detail_sales(no_ref, store);
    }

    function load_data_detail_sales(no_ref, store) {
        $('#tb_sales_detail_upload').removeClass('d-none');
        tabel2 = $('#tb_sales_detail_upload').DataTable({
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
                "url": "<?= base_url('Transaction/list_detail_sales_upload'); ?>", // URL file untuk proses select datanya
                "type": "POST",
                "data": {
                    "store": store,
                    "no_ref": no_ref
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
                    "data": 'barcode',
                    "sortable": false,
                    "render": function(data, type, row, meta) {
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
            ],
        });
    }

    function batal(status, no_ref, store){
        update_status(status, no_ref, store);
    }

    // function update_status(status, no_ref, store){
    //     $.ajax({
    //         type: "POST",
    //         url: "<?= base_url('Transaction'); ?>/update_transaksi",
    //         dataType: "json",
    //         data: {
    //             "no_ref": no_ref,
    //             "store" : store,
    //             "status": status
    //         },
    //         success: function(data) {
    //             if(data.status == "1"){
    //                 swal("Success", data["message"] , "success");
    //             }else{
    //                 swal("Oops !", data["message"] , "error");
    //             }
    //             setTimeout(
    //             function() {
    //                 window.location = base_url+"Transaction/upload_sales";
    //             }, 2000);
    //         },
    //         beforeSend: function(){
    //             swal("Loading", "Harap Tunggu..." , "warning");
    //         },
    //         error: function (jqXHR, exception) {
    //             console.log(exception);
    //             swal("Oops !", "Harap Hubungi IT" , "error");
    //         },
    //     });
    // }

    // function load_data_paid_sales(trans_no) {
    //     $('#tb_paid').removeClass('d-none');
    //     tabel2 = $('#tb_paid').DataTable({
    //         "processing": true,
    //         "responsive": true,
    //         "serverSide": true,
    //         "serverMethod": "post",
    //         "bDestroy": true,
    //         "ordering": true, // Set true agar bisa di sorting
    //         "order": [
    //             [0, "asc"]
    //         ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
    //         "ajax": {
    //             "url": "<?= base_url('Transaction/list_paid_today'); ?>", // URL file untuk proses select datanya
    //             "type": "POST",
    //             "data": {
    //                 "store": store,
    //                 "trans_no": trans_no
    //             },
    //         },
    //         "scrollX": true,
    //         "deferRender": true,
    //         "aLengthMenu": [
    //             [10, 25, 50],
    //             [10, 25, 50]
    //         ], // Combobox Limit
    //         "columns": [{
    //                 "data": 'no_urut',
    //                 "sortable": false,
    //                 "render": function(data, type, row, meta) {
    //                     return meta.row + meta.settings._iDisplayStart + 1;
    //                 },
    //             },
    //             {
    //                 "data": "trans_no",
    //                 "render": function(data, type, row) {
    //                     return '<nobr>' + data + '</nobr>';
    //                 },
    //             },
    //             {
    //                 "data": "card_number",
    //                 "render": function(data, type, row) {
    //                     return '<nobr>' + data + '</nobr>';
    //                 },
    //             },
    //             {
    //                 "data": "card_name",
    //                 "render": function(data, type, row) {
    //                     return '<nobr>' + data + '</nobr>';
    //                 },
    //             },
    //             {
    //                 "data": "paid_amount",
    //                 "render": function(data, type, row) {
    //                     return '<nobr>' + data + '</nobr>';
    //                 },
    //             },
    //             {
    //                 "data": "description",
    //                 "render": function(data, type, row) {
    //                     return '<nobr>' + data + '</nobr>';
    //                 },
    //             },
    //         ],
    //     });
    // }
    


</script>