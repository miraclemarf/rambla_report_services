<div class="content-wrapper">
    <?php $this->load->view('modal/export-pembayaranonline', true); ?>
    <?php $this->load->view('modal/filter-pembayaranonline', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <div>
                            <h4 class="card-title mb-0">Laporan Pembayaran Trx Online</h4>
                            <p class="text-muted mb-2">Terapkan filter untuk menampilkan data.</p>
                        </div>
                        <div class="align-self-end">
                            <button type="button"
                                class="btn btn-success btn-sm btn-icon-text btn-export-penjualanartikel ml-2"
                                style="float:right">
                                <i class="typcn typcn-download btn-icon-prepend"></i>
                                Export File
                            </button>
                            <button type="button" class="btn btn-info btn-sm btn-icon-text btn-filter-pejualanartikel"
                                style="float:right">
                                <i class="typcn typcn-filter"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                    <div class="">
                        <table class="table table-striped table-custom d-none table-responsive" id="tb_penjualanartikel_list">
                            <thead class="table-rambla">
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <nobr>Store</nobr>
                                    </th>
                                    <th>
                                        <nobr>Periode</nobr>
                                    </th>
                                    <th>
                                        <nobr>Bulan</nobr>
                                    </th>
                                    <th>
                                        <nobr>Trans No</nobr>
                                    </th>
                                    <th>
                                        <nobr>No Ref</nobr>
                                    </th>
                                    <th>
                                        <nobr>Delivery Type</nobr>
                                    </th>
                                    <th>
                                        <nobr>Delivery Provider</nobr>
                                    </th>
                                    <th>
                                        <nobr>Tipe Pembayaran</nobr>
                                    </th>
                                    <th>
                                        <nobr>Nama Pembayaran </nobr>
                                    </th>
                                    <th>
                                        <nobr>Jumlah Pembayaran</nobr>
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

<script>
    var tabel = null;
    $(document).ready(function() {
        get_store();

        // get_list_barcode();
        var store = '';
        var params3 = periode;
        var params8 = '';
        var deltype = '';
        var paytype = '';

        //load_data_penjualanartikel(params1,params2,params3,params4,params5,params6,params7,params8,params9);

        $('#modal-filter-penjualanartikel').modal('show');

        $('.btn-export-penjualanartikel').on("click", function() {
            $('#modal-export-penjualanartikel').modal('show');
        });

        $('.btn-filter-pejualanartikel').on("click", function() {
            $('#modal-filter-penjualanartikel').modal('show');
        });

        $('.format-file-export').on('change', function(e) {
            format = this.value;
        });


        $('.btn-submit-filter').on("click", function() {
            params3 = periode;
            if (params3 === "") {
                params3 = null;
            }
            load_data_pembayaranonline(store, params3, params8);
        });

        $('.btn-export').on("click", function() {
            export_pembayaranonline(store, params3, params8, paytype, deltype);
        });

        function export_pembayaranonline(store, params3, params8, paytype, deltype) {
            //console.log(params9)
            $.ajax({
                type: "POST",
                url: "<?= base_url('Laporan/generate_date'); ?>",
                dataType: "JSON",
                data: {
                    "periode": params3
                },
                success: function(data) {
                    if (format == "csv") {
                        window.location.href = "<?= base_url('Laporan/export_csv_pembayaran_online/'); ?>?fromdate=" + data.fromdate + '&todate=' + data.todate + '&store=' + store + '&deltype=' + deltype + '&paytype=' + paytype;
                    } else if (format == "xls") {
                        window.location.href = "<?= base_url('Laporan/export_excel_pembayaran_online/'); ?>?fromdate=" + data.fromdate + '&todate=' + data.todate + '&store=' + store + '&deltype=' + deltype + '&paytype=' + paytype;
                    }

                }
            });
        }

        function load_data_pembayaranonline(store, params3, params8) {
            $('#tb_penjualanartikel_list').removeClass('d-none');
            tabel = $('#tb_penjualanartikel_list').DataTable({
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
                    "url": "<?= base_url('Laporan/pembayaran_online_list'); ?>", // URL file untuk proses select datanya
                    "type": "POST",
                    "data": {
                        "store": store,
                        "params3": params3,
                        "params8": params8,
                        "deltype": deltype,
                        "paytype": paytype
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
                        "data": "branch_id",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "trans_date",
                        "render": function(data, type, row) {
                            return '<nobr>' + data.substring(0, 10) + '</nobr>';
                        },
                    }, // Tampilkan judul
                    {
                        "data": "trans_date",
                        "render": function(data, type, row) {
                            return '<nobr>' + data.substring(5, 7) + '</nobr>';
                        },
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
                        "data": "delivery_type",
                        "render": function(data, type, row) {
                            var result = '';
                            if (data == 'P')
                                result = 'Pickup'
                            if (data == 'I')
                                result = 'Instan'
                            if (data == 'R')
                                result = 'Reguler'
                            return '<nobr>' + result + '</nobr>';
                        },
                    },
                    {
                        "data": "delivery_number",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "mop_name",
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
                            return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                        },
                    },
                ],
            });
        }


        // START STORE
        function get_store() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_store",
                dataType: "html",
                success: function(data) {
                    $(".loading").hide();
                    $("#export-penjualanartikel").show();
                    $("#filter-penjualanartikel").show();
                    $('.list_store').html(data);
                },
                beforeSend: function(xhr) {
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }
        $('.list_store').on('change', function(e) {
            store = this.value;
        })

        $('.list_deltype').on('change', function(e) {
            deltype = this.value;
        })

        $('.list_paytype').on('change', function(e) {
            paytype = this.value;
        })
        // END STORE        
    });
</script>