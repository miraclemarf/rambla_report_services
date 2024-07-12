<div class="content-wrapper">
    <?php $this->load->view('modal/export-promotoday', true); ?>
    <?php $this->load->view('modal/filter-updateprice', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <div>
                            <h4 class="card-title mb-0">Daftar Artikel Perubahan Harga Hari Ini & Besok</h4>
                            <p class="text-muted mb-2">Tanggal : <?= date("l, d F Y") ?></p>
                        </div>
                        <div class="align-self-end">
                            <button type="button"
                                class="btn btn-success btn-sm btn-icon-text btn-export-promotoday ml-2"
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
                        <table class="table table-striped table-custom d-none" id="tb_penjualanartikel_list">
                            <thead class="table-rambla">
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <nobr>No Trx</nobr>
                                    </th>
                                    <th>
                                        <nobr>Store</nobr>
                                    </th>
                                    <th>
                                        <nobr>Article Code</nobr>
                                    </th>
                                    <th>
                                        <nobr>Article Number</nobr>
                                    </th>
                                    <th>
                                        <nobr>Barcode</nobr>
                                    </th>
                                    <th>
                                        <nobr>Category Code</nobr>
                                    </th>
                                    <th>
                                        <nobr>Division</nobr>
                                    </th>
                                    <th>
                                        <nobr>Sub-Division</nobr>
                                    </th>
                                    <th>
                                        <nobr>Dept</nobr>
                                    </th>
                                    <th>
                                        <nobr>Sub-Dept</nobr>
                                    </th>
                                    <th>
                                        <nobr>Brand Code</nobr>
                                    </th>
                                    <th>
                                        <nobr>Brand Name</nobr>
                                    </th>
                                    <th>
                                        <nobr>Article Name</nobr>
                                    </th>
                                    <th>
                                        <nobr>Varian Opt 1</nobr>
                                    </th>
                                    <th>
                                        <nobr>Varian Opt 2</nobr>
                                    </th>
                                    <th>
                                        <nobr>Harga Lama</nobr>
                                    </th>
                                    <th>
                                        <nobr>Harga Baru</nobr>
                                    </th>
                                    <th>
                                        <nobr>Remark</nobr>
                                    </th>
                                    <th>
                                        <nobr>Selisih</nobr>
                                    </th>
                                    <th>
                                        <nobr>Effective Date</nobr>
                                    </th>
                                    <th>
                                        <nobr>Created Date</nobr>
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
    $(document).ready(function () {
        get_store();
        get_user_brand();
        get_division();
        get_sub_division();
        get_dept();
        get_list_dept();
        get_list_prmotype();

        let store='', brand = '', division = '', sub_division = '', dept = '', sub_dept = '', price_status = '', searchValue = '', columnName = '', columnSortOrder = '';

        //load_data_pembayaranonline(brand, division, sub_division, dept, sub_dept, promotype, ismember, storeId);

        $('#modal-filter-penjualanartikel').modal('show');

        $('.btn-export-promotoday').on("click", function () {
            $('#modal-export-promotoday').modal('show');
        });

        $('.btn-filter-pejualanartikel').on("click", function () {
            $('#modal-filter-penjualanartikel').modal('show');
        });

        $('.format-file-export').on('change', function (e) {
            format = this.value;
        });


        $('.btn-submit-filter').on("click", function () {
            if (store === "" || store == null) {
                alert('Store Harus Dipilih')
                return false;
            }
            load_data_pembayaranonline(store, brand, division, sub_division, dept, sub_dept, price_status);
        });

        $('.btn-export').on("click", function () {
            let colIndex = tabel.order()[0][0];
            searchValue = tabel.search();
            columnName = tabel.column(colIndex).dataSrc();
            columnSortOrder = tabel.order()[0][1];

            export_pembayaranonline(store, brand, division, sub_division, dept, sub_dept, price_status, searchValue, columnName, columnSortOrder);
        });

        function export_pembayaranonline(store, brand, division, sub_division, dept, sub_dept, price_status, searchValue, columnName, columnSortOrder) {
            //console.log(params9)
            let qStr = '?isAll=1' +'&store='+store +'&brand='+brand +'&division='+division +'&sub_division='+sub_division +'&dept='+dept +'&sub_dept='+sub_dept +'&price_status='+ price_status + '&searchValue=' + searchValue + '&columnName=' + columnName + '&columnSortOrder=' + columnSortOrder;

            if (format == "csv") {                
                window.location.href = "<?= base_url('UpdatePrice/export_csv_update_price/'); ?>"+qStr
            } else if (format == "xls") {
                window.location.href = "<?= base_url('UpdatePrice/export_excel_update_price/'); ?>"+qStr
            }
        }

        function load_data_pembayaranonline(store, brand, division, sub_division, dept, sub_dept, price_status) {
            $('#tb_penjualanartikel_list').removeClass('d-none');
            tabel = $('#tb_penjualanartikel_list').DataTable({
                "processing": true,
                "responsive": true,
                "serverSide": true,
                "serverMethod": "post",
                "pageLength": 25,
                "bDestroy": true,
                "ordering": true, // Set true agar bisa di sorting
                "ajax":
                {
                    "url": "<?= base_url('UpdatePrice/update_price_list'); ?>", // URL file untuk proses select datanya
                    "type": "POST",
                    "data": { "store":store, "brand": brand, "division": division, "sub_division": sub_division, "dept": dept, "sub_dept": sub_dept, "price_status": price_status, "isAll": 0 },
                },
                "scrollX": true,
                "deferRender": true,
                "aLengthMenu": [[10, 25, 50], [10, 25, 50]], // Combobox Limit
                "columns": [
                    {
                        "data": "create_time", "sortable": false,
                        "render": function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        "data": "trans_no",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "branch_id",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "article_code",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "article_number",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "barcode",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "category_code",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "DIVISION",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "SUB_DIVISION",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "DEPT",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "SUB_DEPT",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "brand",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "brand_name",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "article_name",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "varian_option1",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "varian_option2",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "old_price",
                        "render": function (data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "new_price",
                        "render": function (data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "status_price",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "diff",
                        "render": function (data, type, row) {
                            return '<nobr>Rp ' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "effective_date",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "create_time",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                ],
            });
        }
        function get_store() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_store",
                dataType: "html",
                success: function (data) {
                    $(".loading").hide();
                    $("#export-penjualanartikel").show();
                    $("#filter-penjualanartikel").show();
                    $('.list_store').html(data);
                },
                beforeSend: function (xhr) {
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }
        // START DIVISION
        function get_division() {
            $.ajax({
                type: "POST",
                url: "<?= base_url('Masterdata'); ?>/get_list_division",
                dataType: "html",
                data: {
                    store: 'V001'
                },
                success: function (data) {
                    //console.log(data);
                    $(".loading").hide();
                    $("#export-penjualanartikel").show();
                    $("#filter-penjualanartikel").show();
                    $('.list_division').html(data);
                },
                beforeSend: function (xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_division').on('change', function (e) {
            division = this.value;
            if (division != '') {
                $.ajax({
                    url: "<?= base_url('Masterdata'); ?>/get_list_sub_division",
                    data: {
                        division: division
                    },
                    type: 'POST',
                    dataType: 'html',
                    success: function (data) {
                        $(".loading").hide();
                        $('.list_sub_division').html(data);
                    },
                    beforeSend: function (xhr) {
                        // console.log(xhr);
                        $(".loading").show();
                    }
                });
            }
        })
        // END DIVISION

        // START SUB DIVISION
        function get_sub_division() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_list_sub_division",
                dataType: "html",
                success: function (data) {
                    //console.log(data);
                    $(".loading").hide();
                    $("#export-penjualanartikel").show();
                    $("#filter-penjualanartikel").show();
                    $('.list_sub_division').html(data);
                },
                beforeSend: function (xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_sub_division').on('change', function (e) {
            sub_division = this.value;
            if (sub_division != '') {
                $.ajax({
                    url: "<?= base_url('Masterdata'); ?>/get_list_dept",
                    data: {
                        sub_division: sub_division
                    },
                    type: 'POST',
                    dataType: 'html',
                    success: function (data) {
                        //console.log(data);
                        $(".loading").hide();
                        $('.list_dept').html(data);
                    },
                    beforeSend: function (xhr) {
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
                success: function (data) {
                    $(".loading").hide();
                    $("#export-penjualanartikel").show();
                    $("#filter-penjualanartikel").show();
                    $('.list_dept').html(data);
                },
                beforeSend: function (xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_dept').on('change', function (e) {
            dept = this.value;
            if (dept != '') {
                $.ajax({
                    url: "<?= base_url('Masterdata'); ?>/get_list_sub_dept",
                    data: {
                        dept: dept
                    },
                    type: 'POST',
                    dataType: 'html',
                    success: function (data) {
                        //console.log(data);
                        $(".loading").hide();
                        $('.list_sub_dept').html(data);
                    },
                    beforeSend: function (xhr) {
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
                success: function (data) {
                    $(".loading").hide();
                    $("#export-penjualanartikel").show();
                    $("#filter-penjualanartikel").show();
                    $('.list_sub_dept').html(data);
                },
                beforeSend: function (xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_sub_dept').on('change', function (e) {
            sub_dept = this.value;
        });
        // END SUB DEPT      

        function get_user_brand() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_user_brand",
                dataType: "html",
                success: function (data) {
                    $(".loading").hide();
                    $("#export-penjualanartikel").show();
                    $("#filter-penjualanartikel").show();
                    $('.list_user_brand').html(data);
                },
                beforeSend: function (xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }
        $('.list_user_brand').on('change', function (e) {
            brand = this.value;
        });

        function get_list_prmotype() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_list_prmotype",
                dataType: "html",
                success: function (data) {
                    // console.log(data);
                    $(".loading").hide();
                    $('.list_tipe_promo').html(data);
                },
                beforeSend: function (xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                }
            });
        }
        $('.list_store').on('change', function (e) {
            store = this.value;
        });
        
        $('.list_price_status').on('change', function (e) {
            price_status = this.value;
        });
    });
</script>