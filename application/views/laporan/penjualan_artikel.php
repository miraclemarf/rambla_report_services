<div class="content-wrapper">
<?php $this->load->view('modal/export-penjualanartikel', true); ?>
<?php $this->load->view('modal/filter-penjualanartikel', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <h4 class="card-title mb-3">Laporan Penjualan By Artikel</h4>
                        <div class="align-self-end">
                            <button type="button" class="btn btn-success btn-sm btn-icon-text btn-export-penjualanartikel ml-2" style="float:right">
                            <i class="typcn typcn-download btn-icon-prepend"></i>                                                    
                            Export File
                            </button>
                            <button type="button" class="btn btn-info btn-sm btn-icon-text btn-filter-pejualanartikel" style="float:right">
                            <i class="typcn typcn-filter"></i>                                                
                                Filter
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-striped table-custom" id="tb_penjualanartikel_list">
                        <thead class="table-rambla">
                            <tr>
                            <th>#</th>
                            <th><nobr>Store</nobr></th>
                            <th><nobr>Periode</nobr></th>
                            <th><nobr>Bulan</nobr></th>
                            <th><nobr>DIVISION</nobr></th>
                            <th><nobr>SUB DIVISION</nobr></th>
                            <th><nobr>DEPT</nobr></th>
                            <th><nobr>SUB DEPT</nobr></th>
                            <th><nobr>Article Code</nobr></th>
                            <th><nobr>Barcode</nobr></th>
                            <th><nobr>Kode Brand</nobr></th>
                            <th><nobr>Nama Brand</nobr></th>
                            <th><nobr>Nama Produk</nobr></th>
                            <th><nobr>Varian Option1</nobr></th>
                            <th><nobr>Varian Option2</nobr></th>
                            <th><nobr>Harga</nobr></th>
                            <th><nobr>Kode Vendor</nobr></th>
                            <th><nobr>Nama Vendor</nobr></th>
                            <th><nobr>Total Qty(Pcs)</nobr></th>
                            <th><nobr>Total Berat(Kg)</nobr></th>
                            <th><nobr>Disc(%)</nobr></th>
                            <th><nobr>Total Disc</nobr></th>
                            <th><nobr>Disc. Tambahan(Rp)</nobr></th>
                            <th><nobr>Disc. Tambahan(%)</nobr></th>
                            <th><nobr>Margin</nobr></th>
                            <th><nobr>Gross After Margin</nobr></th>
                            <th><nobr>Gross(Rp)</nobr></th>
                            <th><nobr>Net(Rp)</nobr></th>
                            <th><nobr>Source Data</nobr></th>
                            <th><nobr>Trans No</nobr></th> 
                            <th><nobr>No Ref</nobr></th> 
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
        
        // get_list_barcode();
        var brand_code      = null;
        var source          = null;
        var division        = null;
        var sub_division    = null;
        var dept            = null;
        var sub_dept        = null;
        var store           = null;
        var params1         = null;
        var params2         = null;
        var params3         = periode;
        var params4         = null;
        var params5         = null;
        var params6         = null;
        var params7         = null;
        var params8         = null;
        var format          = null;

        load_data_penjualanartikel(params1,params2,params3,params4,params5,params6,params7,params8);

        $('.btn-export-penjualanartikel').on("click", function(){
            $('#modal-export-penjualanartikel').modal('show');
        });

        $('.btn-filter-pejualanartikel').on("click", function(){
            $('#modal-filter-penjualanartikel').modal('show');
        });

        $('.list_user_brand').on('change', function (e) {
            brand_code = this.value;
        });

        $('.list_source').on('change', function (e) {
            source = this.value;
        });

        $('.format-file-export').on('change', function (e) {
            format = this.value;
        });

        
        $('.btn-submit-filter').on("click", function(){
            params1 = brand_code;
            params2 = source;
            params3 = periode;
            params4 = division;
            params5 = sub_division;
            params6 = dept;
            params7 = sub_dept;
            params8 = store;

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

            load_data_penjualanartikel(params1,params2,params3,params4,params5,params6,params7,params8);
        });

        $('.btn-export').on("click", function(){
            export_penjualanartikel(params1,params2,params3,params4,params5,params6,params7,params8);
        });

        function export_penjualanartikel(params1,params2,params3,params4,params5,params6,params7,params8) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('Laporan/generate_date');?>",
                dataType: "JSON",
                data: {"periode": params3},
                success: function(data) {
                if(format == "csv"){
                    window.location.href = "<?= base_url('Laporan/export_csv_penjualanartikel/'); ?>"+data.fromdate+'/'+data.todate+'/'+params2+'/'+params1+'/'+params4+'/'+params5+'/'+params6+'/'+params7+'/'+params8;
                }else if(format == "xls"){
                    window.location.href = "<?= base_url('Laporan/export_excel_penjualanartikel/'); ?>"+data.fromdate+'/'+data.todate+'/'+params2+'/'+params1+'/'+params4+'/'+params5+'/'+params6+'/'+params7+'/'+params8;
                }
                
                }
            });
        }

        // function load_data_penjualanartikeltest(params1,params2,params3) {
        //   $.ajax({
        //     type: "POST",
        //     url: "<?= base_url('Laporan/penjualan_artikel_where_test');?>",
        //     dataType: "JSON",
        //     data: { "params1": params1,"params2": params2,"params3": params3 },
        //     success: function(data) {
        //       console.log(data);
        //     }
        //   });
        // }

        function load_data_penjualanartikel(params1, params2, params3, params4, params5, params6,params7,params8){
            tabel = $('#tb_penjualanartikel_list').DataTable({
                "processing": true,
                "responsive":true,
                "serverSide": true,
                "bDestroy": true,
                "ordering": true, // Set true agar bisa di sorting
                "order": [[ 0, 'asc' ]], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
                "ajax":
                {
                    "url": "<?= base_url('Laporan/penjualan_artikel_where');?>", // URL file untuk proses select datanya
                    "type": "POST",
                    "data":  { "params1": params1,"params2": params2,"params3": params3,"params4": params4,"params5": params5,"params6": params6,"params7": params7, "params8": params8}, 
                },
                "deferRender": true,
                "aLengthMenu": [[10, 25, 50],[ 10, 25, 50]], // Combobox Limit
                "columns": [
                    {"data": 'periode',"sortable": false, 
                        // "render": function ( data, type, row, meta ) {
                        //     var i = meta.row + meta.settings._iDisplayStart + 1;
                        //     return '<div class="form-check"><label class="form-check-label text-muted"><input type="checkbox" class="form-check-input" name="checkbox_'+i+'"><i class="input-helper"></i></label></div>';
                        // },
                        "render": function ( data, type, row, meta ) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    { "data": "branch_id",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, 
                    { "data": "periode",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data.substring(0, 10)+'</nobr>';
                        },
                    }, // Tampilkan judul
                    { "data": "periode",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data.substring(5, 7)+'</nobr>';
                        },
                    }, // Tampilkan judul
                    { "data": "DIVISION",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, // Tampilkan judul
                    { "data": "SUB_DIVISION",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, // Tampilkan judul
                    { "data": "DEPT",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, // Tampilkan judul
                    { "data": "SUB_DEPT",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, // Tampilkan judul
                    { "data": "article_code",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, 
                    { "data": "barcode",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, 
                    { "data": "brand_code",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, 
                    { "data": "brand_name",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },  
                    { "data": "article_name",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, 
                    { "data": "varian_option1",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "varian_option2",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "price",
                        "render": function ( data, type, row ) {
                                return '<nobr>Rp '+rupiahjs(data)+'</nobr>';
                        },
                    },
                    { "data": "vendor_code",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "vendor_name",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "tot_qty",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "tot_berat",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "disc_pct",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "total_disc_amt",
                        "render": function ( data, type, row ) {
                                return '<nobr>Rp '+rupiahjs(data)+'</nobr>';
                        },
                    },
                    { "data": "total_moredisc_amt",
                        "render": function ( data, type, row ) {
                                return '<nobr>Rp '+rupiahjs(data)+'</nobr>';
                        },
                    },
                    { "data": "moredisc_pct",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "margin",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "gross_after_margin",
                        "render": function ( data, type, row ) {
                                return '<nobr>Rp '+rupiahjs(data)+'</nobr>';
                        },
                    },
                    { "data": "gross",
                        "render": function ( data, type, row ) {
                                return '<nobr>Rp '+rupiahjs(data)+'</nobr>';
                        },
                    },
                    { "data": "net",
                        "render": function ( data, type, row ) {
                                return '<nobr>Rp '+rupiahjs(data)+'</nobr>';
                        },
                    },
                    { "data": "source_data",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "trans_no",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "no_ref",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                ],
            });
        }

        function get_user_brand(){
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_user_brand",
                dataType: "html",
                success: function(data) {
                    $(".loading").hide();
                    $("#export-penjualanartikel").show();
                    $("#filter-penjualanartikel").show();
                    $('.list_user_brand').html(data);
                },
                beforeSend: function( xhr ) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        // START STORE
        function get_store(){
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
                beforeSend: function( xhr ) {
                    console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_store').on('change', function (e) {
            store = this.value;
        })
        // END STORE

        // START DIVISION
        function get_division(){
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
                beforeSend: function( xhr ) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_division').on('change', function (e) {
            division = this.value;
            if(division != ''){
                $.ajax({
                    url: "<?= base_url('Masterdata'); ?>/get_list_sub_division",
                    data: {
                        division : division
                    },
                    type: 'POST',
                    dataType: 'html',
                    success: function(data) {
                        $(".loading").hide();
                        $('.list_sub_division').html(data);
                    },
                    beforeSend: function( xhr ) {
                        // console.log(xhr);
                        $(".loading").show();
                    }
                });
            }
        })
        // END DIVISION

        // START SUB DIVISION
        function get_sub_division(){
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
                beforeSend: function( xhr ) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_sub_division').on('change', function (e) {
            sub_division = this.value;
            if(sub_division != ''){
                $.ajax({
                    url: "<?= base_url('Masterdata'); ?>/get_list_dept",
                    data: {
                        sub_division : sub_division
                    },
                    type: 'POST',
                    dataType: 'html',
                    success: function(data) {
                        //console.log(data);
                        $(".loading").hide();
                        $('.list_dept').html(data);
                    },
                    beforeSend: function( xhr ) {
                        // console.log(xhr);
                        $(".loading").show();
                    }
                });
            }
        })
        // END SUB DIVISION

        // START DEPT
        function get_dept(){
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
                beforeSend: function( xhr ) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_dept').on('change', function (e) {
            dept = this.value;
            if(dept != ''){
                $.ajax({
                    url: "<?= base_url('Masterdata'); ?>/get_list_sub_dept",
                    data: {
                        dept : dept
                    },
                    type: 'POST',
                    dataType: 'html',
                    success: function(data) {
                        //console.log(data);
                        $(".loading").hide();
                        $('.list_sub_dept').html(data);
                    },
                    beforeSend: function( xhr ) {
                        // console.log(xhr);
                        $(".loading").show();
                    }
                });
            }
        })
        // END DEPT

        // START SUB DEPT
        function get_list_dept(){
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
                beforeSend: function( xhr ) {
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

        
    });
</script>

