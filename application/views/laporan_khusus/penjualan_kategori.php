<style>
    table,
    th,
    td {
        border: 1px solid white;
    }
</style>
<div class="content-wrapper">
    <?php $this->load->view('modal/export-penjualankategori', true); ?>
    <?php $this->load->view('modal/filter-penjualankategori', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <div>
                            <h4 class="card-title mb-0">Laporan Penjualan By Kategori</h4>
                            <p class="text-muted mb-2">Terapkan filter untuk menampilkan data.</p>
                        </div>
                        <div class="align-self-end">
                            <button type="button" class="btn btn-success btn-sm btn-icon-text btn-export-penjualankategori ml-2" style="float:right">
                                <i class="typcn typcn-download btn-icon-prepend"></i>
                                Export File
                            </button>
                            <button type="button" class="btn btn-info btn-sm btn-icon-text btn-filter-pejualanbrand" style="float:right">
                                <i class="typcn typcn-filter"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                    <div class="">
                        <table class="table table-striped table-custom d-none" id="tb_penjualankategori_list">
                            <thead class="table-rambla">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle; text-align:center;">#</th>
                                    <th rowspan="2" style="vertical-align: middle; text-align:center;">
                                        <nobr>Store</nobr>
                                    </th>
                                    <th rowspan="2" style="vertical-align: middle; text-align:center;">
                                        <nobr>SBU</nobr>
                                    </th>
                                    <th colspan="9" style="vertical-align: middle; text-align:center; background-color:#ff5252">FLOOR</td>
                                    <th colspan="9" style="vertical-align: middle; text-align:center; background-color: #ff5252">ATRIUM</td>
                                    <th colspan="9" style="vertical-align: middle; text-align:center; background-color: #ff5252">ONLINE</td>
                                    <th colspan="9" style="vertical-align: middle; text-align:center; background-color: #ff5252">TOTAL</td>
                                </tr>
                                <tr>
                                    <th>
                                        <nobr>LP Sales</nobr>
                                    </th>
                                    <th>
                                        <nobr>TP Target</nobr>
                                    </th>
                                    <th>
                                        <nobr>TP Sales</nobr>
                                    </th>
                                    <th>
                                        <nobr>%Achieve</nobr>
                                    </th>
                                    <th>
                                        <nobr>%Growth</nobr>
                                    </th>
                                    <th>
                                        <nobr>%LP Margin</nobr>
                                    </th>
                                    <th>
                                        <nobr>%TP Margin</nobr>
                                    </th>
                                    <th>
                                        <nobr>LP Margin Value</nobr>
                                    </th>
                                    <th>
                                        <nobr>TP Margin Value</nobr>
                                    </th>
                                    <th>
                                        <nobr>LP Sales</nobr>
                                    </th>
                                    <th>
                                        <nobr>TP Target</nobr>
                                    </th>
                                    <th>
                                        <nobr>TP Sales</nobr>
                                    </th>
                                    <th>
                                        <nobr>%Achieve</nobr>
                                    </th>
                                    <th>
                                        <nobr>%Growth</nobr>
                                    </th>
                                    <th>
                                        <nobr>%LP Margin</nobr>
                                    </th>
                                    <th>
                                        <nobr>%TP Margin</nobr>
                                    </th>
                                    <th>
                                        <nobr>LP Margin Value</nobr>
                                    </th>
                                    <th>
                                        <nobr>TP Margin Value</nobr>
                                    </th>
                                    <th>
                                        <nobr>LP Sales</nobr>
                                    </th>
                                    <th>
                                        <nobr>TP Target</nobr>
                                    </th>
                                    <th>
                                        <nobr>TP Sales</nobr>
                                    </th>
                                    <th>
                                        <nobr>%Achieve</nobr>
                                    </th>
                                    <th>
                                        <nobr>%Growth</nobr>
                                    </th>
                                    <th>
                                        <nobr>%LP Margin</nobr>
                                    </th>
                                    <th>
                                        <nobr>%TP Margin</nobr>
                                    </th>
                                    <th>
                                        <nobr>LP Margin Value</nobr>
                                    </th>
                                    <th>
                                        <nobr>TP Margin Value</nobr>
                                    </th>
                                    <th>
                                        <nobr>LP Sales</nobr>
                                    </th>
                                    <th>
                                        <nobr>TP Target</nobr>
                                    </th>
                                    <th>
                                        <nobr>TP Sales</nobr>
                                    </th>
                                    <th>
                                        <nobr>%Achieve</nobr>
                                    </th>
                                    <th>
                                        <nobr>%Growth</nobr>
                                    </th>
                                    <th>
                                        <nobr>%LP Margin</nobr>
                                    </th>
                                    <th>
                                        <nobr>%TP Margin</nobr>
                                    </th>
                                    <th>
                                        <nobr>LP Margin Value</nobr>
                                    </th>
                                    <th>
                                        <nobr>TP Margin Value</nobr>
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
        get_user_brand();
        get_division();
        get_sub_division();
        get_dept();
        get_list_dept();

        // get_list_barcode();
        var brand_code = null;
        var source = null;
        var division = null;
        var sub_division = null;
        var dept = null;
        var sub_dept = null;
        var store = null;
        var params1 = null;
        var params2 = null;
        var params3 = last_periode;
        var params4 = null;
        var params5 = null;
        var params6 = null;
        var params7 = null;
        var params8 = null;
        var params9 = periode;
        var format = null;
        var areatrx = null;

        //load_data_penjualanartikel(params1,params2,params3,params4,params5,params6,params7,params8,params9);

        $('#modal-filter-penjualankategori').modal('show');

        $('.btn-export-penjualankategori').on("click", function() {
            $('#modal-export-penjualankategori').modal('show');
        });

        $('.btn-filter-pejualanbrand').on("click", function() {
            $('#modal-filter-penjualankategori').modal('show');
        });

        $('.list_user_brand').on('change', function(e) {
            brand_code = this.value;
        });

        $('.list_source').on('change', function(e) {
            source = this.value;
        });

        $('.format-file-export').on('change', function(e) {
            format = this.value;
        });


        $('.btn-submit-filter').on("click", function() {
            params1 = brand_code;
            params2 = source;
            params3 = last_periode;
            params4 = division;
            params5 = sub_division;
            params6 = dept;
            params7 = sub_dept;
            params8 = store;
            params9 = periode;
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
            if (params8 === "" || params8 == null) {
                params8 = null;
                alert('Store Harus Dipilih')
                return false;
            }
            if (params9 === "") {
                params9 = null;
            }
            // console.log(params1, params2, params3, params4, params5, params6, params7, params8, params9);
            load_data_penjualankategori(params1, params2, params3, params4, params5, params6, params7, params8, params9);
        });

        $('.btn-export').on("click", function() {
            export_penjualankategori(params1, params2, params3, params4, params5, params6, params7, params8, params9);
        });

        function export_penjualankategori(params1, params2, params3, params4, params5, params6, params7, params8, params9) {
            //console.log(params9)
            $.ajax({
                type: "POST",
                url: "<?= base_url('LaporanKhusus/generate_date'); ?>",
                dataType: "JSON",
                data: {
                    "last_periode": params3,
                    "this_periode": params9
                },
                success: function(data) {
                    window.location.href = "<?= base_url('LaporanKhusus/export_excel_penjualankategori/'); ?>" + data.fromdate1 + '/' + data.todate1 + '/' + data.fromdate2 + '/' + data.todate2 + '/' + params4 + '/' + params5 + '/' + params8;
                }
            });
        }

        // params4 = division;
        //     params5 = sub_division;
        //     params6 = dept;
        //     params7 = sub_dept;
        //     params8 = store;

        // function load_data_penjualanartikeltest(params1, params2, params3, params4, params5, params6, params7, params8, params9) {
        //     $.ajax({
        //         type: "POST",
        //         url: "<?= base_url('LaporanKhusus/penjualan_brand_where'); ?>",
        //         dataType: "JSON",
        //         data: {
        //             "params1": params1,
        //             "params2": params2,
        //             "params3": params3,
        //             "params4": params4,
        //             "params5": params5,
        //             "params6": params6,
        //             "params7": params7,
        //             "params8": params8,
        //             "params9": params9
        //         },
        //         success: function(data) {
        //             console.log(data);
        //         }
        //     });
        // }


        function load_data_penjualankategori(params1, params2, params3, params4, params5, params6, params7, params8, params9) {
            $('#tb_penjualankategori_list').removeClass('d-none');
            tabel = $('#tb_penjualankategori_list').DataTable({
                "processing": true,
                "responsive": true,
                "serverSide": true,
                "bDestroy": true,
                "ordering": true, // Set true agar bisa di sorting
                "order": [
                    [0, 'asc']
                ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
                "ajax": {
                    "url": "<?= base_url('LaporanKhusus/penjualan_kategori_where'); ?>", // URL file untuk proses select datanya
                    "type": "POST",
                    "data": {
                        "params1": params1,
                        "params2": params2,
                        "params3": params3,
                        "params4": params4,
                        "params5": params5,
                        "params6": params6,
                        "params7": params7,
                        "params8": params8,
                        "params9": params9
                    },
                },
                "scrollX": true,
                "stateSave": true,
                "deferRender": true,
                "aLengthMenu": [
                    [10, 25, 50],
                    [10, 25, 50]
                ], // Combobox Limit
                "columns": [{
                        "data": 'STORE',
                        "sortable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        "data": "STORE",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "SBU",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },

                    {
                        "data": "LP_Sales1",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Target1",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Sales1",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "Achieve1",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "Growth1",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '%</nobr>';
                        },
                    },
                    {
                        "data": "LP_Margin_Percent1",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Margin_Percent1",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "LP_Margin_Value1",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Margin_Value1",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "LP_Sales2",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Target2",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Sales2",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "Achieve2",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "Growth2",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '%</nobr>';
                        },
                    },
                    {
                        "data": "LP_Margin_Percent2",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Margin_Percent2",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "LP_Margin_Value2",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Margin_Value2",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "LP_Sales3",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Target3",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Sales3",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "Achieve3",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "Growth3",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '%</nobr>';
                        },
                    },
                    {
                        "data": "LP_Margin_Percent3",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Margin_Percent3",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "LP_Margin_Value3",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Margin_Value3",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "LP_Sales4",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Target4",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Sales4",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "Achieve4",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "Growth4",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '%</nobr>';
                        },
                    },
                    {
                        "data": "LP_Margin_Percent4",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Margin_Percent4",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "LP_Margin_Value4",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
                        },
                    },
                    {
                        "data": "TP_Margin_Value4",
                        "render": function(data, type, row) {
                            return '<nobr>' + rupiahjs(data) + '</nobr>';
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
                    $("#export-penjualankategori").show();
                    $("#filter-penjualankategori").show();
                    $('.list_user_brand').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualankategori").hide();
                    $("#export-penjualankategori").hide();
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
                    $(".loading").hide();
                    $("#export-penjualankategori").show();
                    $("#filter-penjualankategori").show();
                    $('.list_store').html(data);
                },
                beforeSend: function(xhr) {
                    $(".loading").show();
                    $("#filter-penjualankategori").hide();
                    $("#export-penjualankategori").hide();
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

        $('.list_areatrx').on('change', function(e) {
            areatrx = this.value;
        })
        $('.list_areatrx').parent().on('click', function(e) {
            if (store === '' || store == null) {
                alert('Harap Pilih Store Dahulu')
                return false;
            }
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
                    $("#export-penjualanbrand").show();
                    $("#filter-penjualanbrand").show();
                    $('.list_division').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanbrand").hide();
                    $("#export-penjualanbrand").hide();
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

        // START SUB DIVISION
        function get_sub_division() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_list_sub_division",
                dataType: "html",
                success: function(data) {
                    //console.log(data);
                    $(".loading").hide();
                    $("#export-penjualanbrand").show();
                    $("#filter-penjualanbrand").show();
                    $('.list_sub_division').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanbrand").hide();
                    $("#export-penjualanbrand").hide();
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
                    $("#export-penjualanbrand").show();
                    $("#filter-penjualanbrand").show();
                    $('.list_dept').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanbrand").hide();
                    $("#export-penjualanbrand").hide();
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
                    $("#export-penjualanbrand").show();
                    $("#filter-penjualanbrand").show();
                    $('.list_sub_dept').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanbrand").hide();
                    $("#export-penjualanbrand").hide();
                }
            });
        }

        $('.list_sub_dept').on('change', function(e) {
            sub_dept = this.value;
        });
        // END SUB DEPT


    });
</script>