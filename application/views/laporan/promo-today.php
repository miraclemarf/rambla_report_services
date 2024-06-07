<div class="content-wrapper">
    <?php $this->load->view('modal/export-promotoday', true); ?>
    <?php $this->load->view('modal/filter-promotoday', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <div>
                            <?php                         
                                $storeName = '';
                                if($store == 'V001'){
                                    $storeName = 'Happy Harvest';
                                }
                                else{
                                    $storeName = $store == 'R001' ? 'Rambla SMKG' : 'Rambla SMBDG';
                                }
                            ?>
                            <h4 class="card-title mb-0">Artikel Promo Hari Ini : <?= $storeName; ?> - <?= $store; ?></h4>
                            <input id="storeID" type="hidden" name="storeId" value="<?= $store; ?>" />
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
                                        <nobr>Barcode</nobr>
                                    </th>
                                    <th>
                                        <nobr>Article Name</nobr>
                                    </th>
                                    <th>
                                        <nobr>Brand</nobr>
                                    </th>
                                    <th>
                                        <nobr>Division</nobr>
                                    </th>
                                    <th>
                                        <nobr>Category</nobr>
                                    </th>
                                    <th>
                                        <nobr>Sub Category</nobr>
                                    </th>
                                    <th>
                                        <nobr>Promo ID</nobr>
                                    </th>
                                    <th>
                                        <nobr>Promo Description</nobr>
                                    </th>
                                    <th>
                                        <nobr>Promo Type</nobr>
                                    </th>
                                    <th>
                                        <nobr>Start Date</nobr>
                                    </th>
                                    <th>
                                        <nobr>Start Time</nobr>
                                    </th>
                                    <th>
                                        <nobr>End Date</nobr>
                                    </th>
                                    <th>
                                        <nobr>End Time</nobr>
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
        get_user_brand();
        get_division();
        get_sub_division();
        get_dept();
        get_list_dept();
        get_list_prmotype();

        let brand = '', division = '', sub_division = '', dept = '', sub_dept = '', promotype = '', ismember = '', searchValue = '', columnName = '', columnSortOrder = '', storeId = $('#storeID').val();

        load_data_pembayaranonline(brand, division, sub_division, dept, sub_dept, promotype, ismember, storeId);

        //$('#modal-filter-penjualanartikel').modal('show');

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
            load_data_pembayaranonline(brand, division, sub_division, dept, sub_dept, promotype, ismember, storeId);
        });

        $('.btn-export').on("click", function () {
            let colIndex = tabel.order()[0][0];
            searchValue = tabel.search();
            columnName = tabel.column(colIndex).dataSrc();
            columnSortOrder = tabel.order()[0][1];            
            
            export_pembayaranonline(brand, division, sub_division, dept, sub_dept, promotype, ismember, searchValue, columnName, columnSortOrder);
        });

        function export_pembayaranonline(brand, division, sub_division, dept, sub_dept, promotype, ismember, searchValue, columnName, columnSortOrder, storeId) {
            //console.log(params9)
            let qStr = '?brand='+brand+'&division='+division+'&sub_division='+sub_division+'&dept='+dept+'&sub_dept='+sub_dept+'&promotype='+promotype+'&ismember='+ismember+'&searchValue='+searchValue+'&columnName='+columnName+'&columnSortOrder='+columnSortOrder+'&storeId='+storeId;
            if (format == "csv") {

                window.location.href = "<?= base_url('PromoToday/export_csv_promotoday/'); ?>"+qStr
            } else if (format == "xls") {
                window.location.href = "<?= base_url('PromoToday/export_excel_promotoday/'); ?>"+qStr
            }
        }

        function load_data_pembayaranonline(brand, division, sub_division, dept, sub_dept, promotype, ismember, storeId) {
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
                    "url": "<?= base_url('PromoToday/promo_today_list'); ?>", // URL file untuk proses select datanya
                    "type": "POST",
                    "data": { "brand": brand, "division": division, "sub_division": sub_division, "dept": dept, "sub_dept":sub_dept, "promotype": promotype, "ismember": ismember, "storeId" :storeId },
                },
                "scrollX": true,
                "deferRender": true,
                "aLengthMenu": [[10, 25, 50], [10, 25, 50]], // Combobox Limit
                "columns": [
                    {
                        "data": "barcode", "sortable": false,
                        "render": function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        "data": "barcode",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "article_name",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    }, // Tampilkan judul
                    {
                        "data": "brand_name",
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
                        "data": "promo_id",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "promo_desc",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "promo_name",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "start_date",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "start_time",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "end_date",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "end_time",
                        "render": function (data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                ],
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
        $('.list_tipe_promo').on('change', function (e) {
            promotype = this.value;
        });
        $('.promo_member').on('change', function (e) {
            ismember = this.value;
        });
    });
</script>