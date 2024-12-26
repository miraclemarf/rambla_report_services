<div class="content-wrapper">
    <?php $this->load->view('modal/export-stock', true); ?>
    <?php $this->load->view('modal/filter-stock', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <div>
                            <h4 class="card-title mb-0">Laporan Stock</h4>
                            <p class="text-muted mb-2">Data Per : <?=
                                                                    date("l, d F Y", strtotime("-1 days")) ?></p>
                        </div>
                        <div class="align-self-end">
                            <button type="button" class="btn btn-success btn-sm btn-icon-text btn-export-stock ml-2" style="float:right">
                                <i class="typcn typcn-download btn-icon-prepend"></i>
                                Export File
                            </button>
                            <button type="button" class="btn btn-info btn-sm btn-icon-text btn-filter-stock" style="float:right">
                                <i class="typcn typcn-filter"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-custom" id="tb_stock_list">
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
                                        <nobr>Barcode</nobr>
                                    </th>
                                    <th>
                                        <nobr>Article Code</nobr>
                                    </th>
                                    <th>
                                        <nobr>Article Name</nobr>
                                    </th>
                                    <th>
                                        <nobr>Varian Option1</nobr>
                                    </th>
                                    <th>
                                        <nobr>Varian Option2</nobr>
                                    </th>
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
                                        <nobr>Brand Name</nobr>
                                    </th>
                                    <th>
                                        <nobr>Category Code</nobr>
                                    </th>
                                    <th>
                                        <nobr>DIVISION</nobr>
                                    </th>
                                    <th>
                                        <nobr>SUB DIVISION</nobr>
                                    </th>
                                    <th>
                                        <nobr>DEPT</nobr>
                                    </th>
                                    <th>
                                        <nobr>SUB DEPT</nobr>
                                    </th>
                                    <th>
                                        <nobr>Last Stock</nobr>
                                    </th>
                                    <th>
                                        <nobr>Current Price</nobr>
                                    </th>
                                    <th>
                                        <nobr>Purchase Price</nobr>
                                    </th>
                                    <th>
                                        <nobr>Retail Value</nobr>
                                    </th>
                                    <th>
                                        <nobr>Article Status</nobr>
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
        get_user_brand();
        get_division();
        get_sub_division();
        get_dept();
        get_list_dept();
        get_store();

        var brand_code = null;
        var division = null;
        var sub_division = null;
        var dept = null;
        var sub_dept = null;
        var store = null;
        var art_type = null;
        var article_status = null;
        var params1 = null;
        var params2 = null;
        var params3 = null;
        var params4 = null;
        var params5 = null;
        var params6 = null;
        var params7 = null;
        var format = null;

        //load_data_stock(params1,params2,params3,params4,params5,params6, params7);

        $('#modal-filter-stock').modal('show');

        $('.btn-export-stock').on("click", function() {
            $('#modal-export-stock').modal('show');
        });

        $('.btn-filter-stock').on("click", function() {
            $('#modal-filter-stock').modal('show');
        });

        $('.list_user_brand').on('change', function(e) {
            brand_code = this.value;
        });

        $('.list_barcode').on('change', function(e) {
            barcode = this.value;
        });

        $('.format-file-export').on('change', function(e) {
            format = this.value;
        });

        $('.btn-submit-filter').on("click", function() {
            if (store === '' || store == null) {
                alert('Harap Pilih Store Dahulu')
                return false;
            }

            params1 = brand_code;
            params2 = division;
            params3 = sub_division;
            params4 = dept;
            params5 = sub_dept;
            params6 = store;
            params7 = art_type;
            params8 = article_status;

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
            if (params5 === "") {
                params5 = null;
            }
            if (params6 === "") {
                params6 = null;
            }
            if (params7 === "") {
                params7 = null;
            }
            if (params8 === "") {
                params8 = null;
            }

            load_data_stock(params1, params2, params3, params4, params5, params6, params7, params8);
        });

        $('.btn-export').on("click", function() {
            if (format == "csv") {
                window.location.href = "<?= base_url('Laporan/export_csv_stockv2/'); ?>" + params1 + '/' + params2 + '/' + params3 + '/' + params4 + '/' + params5 + '/' + params6 + '/' + params7 + '/' + params8;
            } else if (format == "xls") {
                window.location.href = "<?= base_url('Laporan/export_excel_stockv2/'); ?>" + params1 + '/' + params2 + '/' + params3 + '/' + params4 + '/' + params5 + '/' + params6 + '/' + params7 + '/' + params8;
            }
        });

        function load_data_stock(params1, params2, params3, params4, params5, params6, params7, params8) {
            tabel = $('#tb_stock_list').DataTable({
                "processing": true,
                "responsive": true,
                "serverSide": true,
                "bDestroy": true,
                "ordering": true, // Set true agar bisa di sorting
                "order": [
                    [0, 'asc']
                ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
                "ajax": {
                    "url": "<?= base_url('Laporan/stockv2_where'); ?>", // URL file untuk proses select datanya
                    "type": "POST",
                    "data": {
                        "params1": params1,
                        "params2": params2,
                        "params3": params3,
                        "params4": params4,
                        "params5": params5,
                        "params6": params6,
                        "params7": params7,
                        "params8": params8
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
                        "data": "branch_id",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "periode",
                        "render": function(data, type, row) {
                            return '<nobr>' + data.substring(0, 7) + '</nobr>';
                        },
                    },
                    {
                        "data": "barcode",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    }, // Tampilkan kategori
                    {
                        "data": "article_code",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    }, // Tampilkan kategori
                    {
                        "data": "article_name",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "varian_option1",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    }, // Tampilkan penulis
                    {
                        "data": "varian_option2",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    }, // Tampilkan tgl posting
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
                        "data": "brand_name",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    }, // Tampilkan judul
                    {
                        "data": "category_code",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    }, // Tampilkan tgl posting
                    // Tampilkan tgl posting
                    {
                        "data": "DIVISION",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    }, // Tampilkan tgl posting
                    {
                        "data": "SUB_DIVISION",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "DEPT",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "SUB_DEPT",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "last_stock",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "current_price",
                        "render": function(data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(Math.round(data)) + '</nobr>';
                        },
                    },
                    {
                        "data": "purchase_price",
                        "render": function(data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(Math.round(data)) + '</nobr>';
                        },
                    },
                    {
                        "data": "retail_value",
                        "render": function(data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(Math.round(data)) + '</nobr>';
                        },
                    },
                    {
                        "data": "",
                        "render": function(data, type, row) {
                            var color = '';
                            var id = row.article_number + row.branch_id;
                            if (row.status_article == 'ACTIVE') {
                                color = 'badge-success';
                            } else if (row.status_article == 'PURGE') {
                                color = 'badge-danger'
                            } else {
                                color = 'badge-warning'
                            }
                            return `<nobr><label class="badge ` + color + `">` + row.status_article + `</label></nobr>`;
                        },
                    },
                ],
            });
        }

        function get_user_brand() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_user_brand",
                dataType: "html",
                success: function(data) {
                    $(".loading").hide();
                    $("#export-stock").show();
                    $("#filter-stock").show();
                    $('.list_user_brand').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-stock").hide();
                    $("#export-stock").hide();
                }
            });
        }

        // START STORE
        function get_store() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_store",
                dataType: "html",
                success: function(data) {
                    console.log(data);
                    $(".loading").hide();
                    $("#export-penjualanartikel").show();
                    $("#filter-penjualanartikel").show();
                    $('.list_store').html(data);
                },
                beforeSend: function(xhr) {
                    console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_store').on('change', function(e) {
            store = this.value;
            $.ajax({
                url: "<?= base_url('Masterdata'); ?>/get_list_division",
                data: {
                    store: store
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    //console.log(data);
                    $(".loading").hide();
                    $('.list_division').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                }
            });
        })
        // END STORE

        // START DIVISION
        function get_division() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_list_division",
                dataType: "html",
                success: function(data) {
                    //console.log(data);
                    $(".loading").hide();
                    $("#export-penjualanartikel").show();
                    $("#filter-penjualanartikel").show();
                    $('.list_division').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_division').on('change', function(e) {
            division = this.value;
            if (division != '') {
                $.ajax({
                    url: "<?= base_url('Masterdata'); ?>/get_list_sub_division",
                    data: {
                        division: division
                    },
                    type: 'POST',
                    dataType: 'html',
                    success: function(data) {
                        $(".loading").hide();
                        $('.list_sub_division').html(data);
                    },
                    beforeSend: function(xhr) {
                        // console.log(xhr);
                        $(".loading").show();
                    }
                });
            }
        })
        // END DIVISION

        // START STATUS
        $('.list_article_status').on('change', function(e) {
            article_status = this.value;
        })
        // END STATUS

        // START SUB DIVISION
        function get_sub_division() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_list_sub_division",
                dataType: "html",
                success: function(data) {
                    //console.log(data);
                    $(".loading").hide();
                    $("#export-penjualanartikel").show();
                    $("#filter-penjualanartikel").show();
                    $('.list_sub_division').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_sub_division').on('change', function(e) {
            sub_division = this.value;
            if (sub_division != '') {
                $.ajax({
                    url: "<?= base_url('Masterdata'); ?>/get_list_dept",
                    data: {
                        sub_division: sub_division
                    },
                    type: 'POST',
                    dataType: 'html',
                    success: function(data) {
                        //console.log(data);
                        $(".loading").hide();
                        $('.list_dept').html(data);
                    },
                    beforeSend: function(xhr) {
                        // console.log(xhr);
                        $(".loading").show();
                    }
                });
            }
        })
        // END SUB DIVISION

        // START DEPT
        function get_dept() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_list_dept",
                dataType: "html",
                success: function(data) {
                    $(".loading").hide();
                    $("#export-penjualanartikel").show();
                    $("#filter-penjualanartikel").show();
                    $('.list_dept').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_dept').on('change', function(e) {
            dept = this.value;
            if (dept != '') {
                $.ajax({
                    url: "<?= base_url('Masterdata'); ?>/get_list_sub_dept",
                    data: {
                        dept: dept
                    },
                    type: 'POST',
                    dataType: 'html',
                    success: function(data) {
                        //console.log(data);
                        $(".loading").hide();
                        $('.list_sub_dept').html(data);
                    },
                    beforeSend: function(xhr) {
                        // console.log(xhr);
                        $(".loading").show();
                    }
                });
            }
        })
        // END DEPT

        // START SUB DEPT
        function get_list_dept() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_list_sub_dept",
                dataType: "html",
                success: function(data) {
                    $(".loading").hide();
                    $("#export-penjualanartikel").show();
                    $("#filter-penjualanartikel").show();
                    $('.list_sub_dept').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_sub_dept').on('change', function(e) {
            sub_dept = this.value;
        });
        // END SUB DEPT

        // START ARTICLE TYPE
        $('.list_article_type').on('change', function(e) {
            art_type = this.value;
            console.log(art_type);
        })
        // END ARTICLE TYPE
    });
</script>