<div class="content-wrapper">
    <?php $this->load->view('modal/export-operationalfee', true); ?>
    <?php $this->load->view('modal/filter-operationalfee', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <div>
                            <h4 class="card-title mb-0">Laporan Operational Fee</h4>
                            <p class="text-muted mb-2">Terapkan filter untuk menampilkan data.</p>
                        </div>
                        <div class="align-self-end">
                            <button type="button" class="btn btn-success btn-sm btn-icon-text btn-export-operationalfee ml-2" style="float:right">
                                <i class="typcn typcn-download btn-icon-prepend"></i>
                                Export File
                            </button>
                            <button type="button" class="btn btn-info btn-sm btn-icon-text btn-filter-operationalfee" style="float:right">
                                <i class="typcn typcn-filter"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                    <div class="">
                        <table class="table table-striped table-custom d-none" id="tb_operationalfee_list">
                            <thead class="table-rambla">
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <nobr>Vendor Code</nobr>
                                    </th>
                                    <th>
                                        <nobr>Vendor Name</nobr>
                                    </th>
                                    <th>
                                        <nobr>Brand Code</nobr>
                                    </th>
                                    <th>
                                        <nobr>Net Floor</nobr>
                                    </th>
                                    <th>
                                        <nobr>Net Bazaar</nobr>
                                    </th>
                                    <th>
                                        <nobr>Gross Floor</nobr>
                                    </th>
                                    <th>
                                        <nobr>Gross Bazaar</nobr>
                                    </th>
                                    <th>
                                        <nobr>Ops Fee</nobr>
                                    </th>
                                    <th>
                                        <nobr>Total Ops Fee</nobr>
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
        var format = null;

        //load_data_penjualanartikel(params1,params2,params3,params4,params5,params6,params7,params8,params9);

        $('#modal-filter-operationalfee').modal('show');

        $('.btn-export-operationalfee').on("click", function() {
            $('#modal-export-operationalfee').modal('show');
        });

        $('.btn-filter-operationalfee').on("click", function() {
            $('#modal-filter-operationalfee').modal('show');
        });

        $('.format-file-export').on('change', function(e) {
            format = this.value;
        });


        $('.btn-submit-filter').on("click", function() {
            params3 = periode;
            if (params3 === "") {
                params3 = null;
            }
            load_data_operationalfee(store, params3);
        });

        $('.btn-export').on("click", function() {
            export_operationalfee(store, params3);
        });

        function export_operationalfee(store, params3) {
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
                        window.location.href = "<?= base_url('Laporan/export_csv_operational_fee/'); ?>?fromdate=" + data.fromdate + '&todate=' + data.todate + '&store=' + store;
                    } else if (format == "xls") {
                        window.location.href = "<?= base_url('Laporan/export_excel_operational_fee/'); ?>?fromdate=" + data.fromdate + '&todate=' + data.todate + '&store=' + store;
                    }

                }
            });
        }

        function load_data_operationalfee(store, params3) {
            $('#tb_operationalfee_list').removeClass('d-none');
            tabel = $('#tb_operationalfee_list').DataTable({
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
                    "url": "<?= base_url('Laporan/pembayaran_operational_fee'); ?>", // URL file untuk proses select datanya
                    "type": "POST",
                    "data": {
                        "store": store,
                        "params3": params3
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
                        "data": "vendor_code",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "vendor_name",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    }, // Tampilkan judul
                    {
                        "data": "brand_code",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "net_floor",
                        "render": function(data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "nett_bazzar",
                        "render": function(data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "gross_floor",
                        "render": function(data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "gross_bazzar",
                        "render": function(data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "ops_fee",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '% </nobr>';
                        },
                    },
                    {
                        "data": "TotalOpsFee",
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
                    $("#export-operationalfee").show();
                    $("#filter-operationalfee").show();
                    $('.list_store').html(data);
                },
                beforeSend: function(xhr) {
                    $(".loading").show();
                    $("#filter-operationalfee").hide();
                    $("#export-operationalfee").hide();
                }
            });
        }
        $('.list_store').on('change', function(e) {
            store = this.value;
        })
    });
</script>