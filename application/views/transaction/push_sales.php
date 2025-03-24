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
<?php $this->load->view('modal/filter-pushsales', true); ?>
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0 font-weight-bold">List Transaction DB Server History</h3>
            <p>Sales 3 Hari Terkahir
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
                            <div id="filter-store" class="d-none">
                                <form action="<?= base_url(); ?>Transaction/push_sales" method="post">
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
                            <button type="button" class="btn btn-info btn-sm btn-icon-text btn-filter-pushsales" style="float:right">
                                <i class="typcn typcn-filter"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                    <!-- <h4 class="card-title">Target Today</h4> -->
                    <div class="table-responsive">
                    <table class="table table-striped table-custom d-none" id="tb_saleshistory_list">
                            <thead class="table-rambla">
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <nobr>Trans No</nobr>
                                    </th>
                                    <th>
                                        <nobr>Periode</nobr>
                                    </th>
                                    <th>
                                        <nobr>Trans Status</nobr>
                                    </th>
                                    <th>
                                        <nobr>Trans Time</nobr>
                                    </th>
                                    <th>
                                        <nobr>Cashier ID</nobr>
                                    </th>
                                    <th>
                                        <nobr>Kode Register</nobr>
                                    </th>
                                    <th>
                                        <nobr>Tot Qty</nobr>
                                    </th>
                                    <th>
                                        <nobr>Tot Berat</nobr>
                                    </th>
                                    <th>
                                        <nobr>Net Price</nobr>
                                    </th>
                                    <th>
                                        <nobr>Status</nobr>
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

<script type="text/javascript" src="<?= base_url(); ?>assets/vendor/chartjs/js/loader.js"></script>
<script src="<?= base_url(); ?>assets/vendor/chartjs/js/chart.js"></script>
<script type="text/JavaScript">
    var store = $('.opt-store .dropdown-item').data('store');
    // console.log(store);
    var tabel = null;
    function push_sales(trans_no){
        $.ajax({
            type: "POST",
            url: "<?= base_url('Transaction'); ?>/insert_sales",
            dataType: "json",
            data: {
                "trans_no": trans_no
            },
            success: function(data) {
                // console.log(data);
                if(data["status"] == "1"){
                    $('.prefix-' + trans_no).html('<label class="badge badge-success badge-sm">Synchronized</label>');
                    $('.prefix-' + trans_no).removeClass('d-none');
                    swal("Success", "Transaksi Berhasil di Push" , "success");
                }else{
                    $('.prefix-' + trans_no).html('<label class="badge badge-danger badge-sm" style="cursor: pointer" onclick="push_sales('+trans_no+')"><i class="typcn typcn-upload menu-icon"></i> Need Push</label>');
                    $('.prefix-' + trans_no).removeClass('d-none');
                    swal("Oops !", 'Harap Hubungi IT!' , "error");
                }
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
        get_register();
        
        var params1 = null; 
        var params2 = null;
        var params3 = periode;
        var params4 = null;
        var kode_reg = null;
        var trans_status = null;
        store = $('.opt-store .dropdown-item').data('store');

        load_data_pushsales(params1, params2, params3,store, params4);

        $(function() {
            $('.opt-store .dropdown-item').on('click', function(){
                //console.log($(this).attr('data'));
                $('#choose-store').html('<i class="typcn typcn-location mr-2"></i>'+$(this).text());
                $('#filter-store input').val($(this).attr('data'));
                $('#filter-store form').trigger('submit');

                store = $('.opt-store .dropdown-item').data('store');
                // console.log(store);
            })
        });

        // START REGISTER
         function get_register() {
            $.ajax({
                type: "POST",
                url: "<?= base_url('Masterdata'); ?>/get_list_register",
                dataType: "html",
                data: {
                    "store" : store
                },
                success: function(data) {
                    // console.log(data);
                    $(".loading").hide();
                    $("#filter-pushsales").show();
                    $('.list_register').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-pushsales").hide();
                }
            });
        }

        $('.btn-filter-pushsales').on("click", function() {
            $('#modal-filter-pushsales').modal('show');
        });

        // $('#modal-filter-pushsales').modal('show');

        $('.list_register').on('change', function(e) {
            kode_reg = this.value;
        });

        $('.list_transstatus').on('change', function(e) {
            trans_status = this.value;
        });

        $('.btn-submit-filter').on("click", function() {
            params1 = $('input[name="trans_no"]').val();
            params2 = kode_reg;
            params3 = periode;
            params4 = trans_status;
            if (params1 === "") {
                params1 = null;
            }
            if (params2 === "") {
                params2 = null;
            }
            if (params3 === "") {
                params3 = null;
            }
            if (params4 === "") {
                params4 = null;
            }
            // console.log(params1, params2, params3, params4);
            load_data_pushsales(params1, params2, params3,store, params4);
        });

        function load_data_pushsales(params1, params2, params3, store, params4) {
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
                    "url": "<?= base_url('Transaction/list_sales_history'); ?>", // URL file untuk proses select datanya
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
                            return '<nobr class= "prefix-'+row.trans_no+' d-none">' + cek_central(row.trans_no) + '</nobr>';
                        },
                    },
                ],
            });
        }

        function cek_central(trans_no){
            var store_code = trans_no.substring(6, 8);
            // console.log(store_code);
            $.ajax({
                type: "POST",
                url: "<?= base_url('Transaction'); ?>/cek_central",
                dataType: "json",
                data: {
                    "trans_no": trans_no,
                    "store_code": store_code
                },
                success: function(data) {
                    // console.log(data["hasil"]);
                    if(data["hasil"]){
                        $('.prefix-' + trans_no).html('<label class="badge badge-success badge-sm">Synchronized</label>');
                        $('.prefix-' + trans_no).removeClass('d-none');
                    } else {
                        $('.prefix-' + trans_no).html('<label class="badge badge-danger badge-sm" style="cursor: pointer" onclick="push_sales('+trans_no+')"><i class="typcn typcn-upload menu-icon"></i> Need Push</label>');
                        $('.prefix-' + trans_no).removeClass('d-none');
                    }
                },
                beforeSend: function(xhr) {}
            });
        }
    });

</script>

   