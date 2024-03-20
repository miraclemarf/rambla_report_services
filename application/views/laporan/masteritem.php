<div class="content-wrapper">
<?php $this->load->view('modal/export-masteritem', true); ?>
<?php $this->load->view('modal/filter-masteritem', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <div>
                            <h4 class="card-title mb-0">Laporan List Item Master</h4>
                            <p class="text-muted mb-2">Terapkan filter untuk menampilkan data.</p>
                        </div>
                        <div class="align-self-end">
                            <button type="button" class="btn btn-success btn-sm btn-icon-text btn-export-masteritem ml-2" style="float:right">
                            <i class="typcn typcn-download btn-icon-prepend"></i>                                                    
                            Export File
                            </button>
                            <button type="button" class="btn btn-info btn-sm btn-icon-text btn-filter-masteritem" style="float:right">
                            <i class="typcn typcn-filter"></i>                                                
                                Filter
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-striped table-custom" id="tb_masteritem_list">
                        <thead class="table-rambla">
                            <tr>
                            <th>#</th>
                            <th><nobr>Store</nobr></th>   
                            <th><nobr>Number Artikel</nobr></th>
                            <th><nobr>Kode Artikel</nobr></th>
                            <th><nobr>Barcode</nobr></th>
                            <th><nobr>Kode Produk</nobr></th>
                            <th><nobr>Kode Kategori</nobr></th>
                            <th><nobr>Nama Produk</nobr></th>
                            <th><nobr>Nama Produk Supplier</nobr></th>
                            <th><nobr>Kode Brand</nobr></th>
                            <th><nobr>Nama Brand</nobr></th>
                            <th><nobr>Option1</nobr></th>
                            <th><nobr>Varian Option1</nobr></th>
                            <th><nobr>Option1</nobr></th>
                            <th><nobr>Varian Option2</nobr></th>
                            <th><nobr>DIVISION</nobr></th>
                            <th><nobr>SUB DIVISION</nobr></th>
                            <th><nobr>DEPT</nobr></th>
                            <th><nobr>SUB DEPT</nobr></th>
                            <th><nobr>Normal Price</nobr></th>
                            <th><nobr>Current Price</nobr></th>
                            <th><nobr>Tag 5</nobr></th>
                            <th><nobr>Add Date</nobr></th>
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

        var brand_code      = null;
        var division        = null;
        var sub_division    = null;
        var dept            = null;
        var sub_dept        = null;
        var store           = null;
        var params1         = null;
        var params2         = null;
        var params3         = null;
        var params4         = null;
        var params5         = null;
        var params6         = null;
        var format          = null;

        // load_data_masteritem(params1,params2,params3,params4,params5,params6);
        $('#modal-filter-masteritem').modal('show');

        $('.btn-export-masteritem').on("click", function(){
            $('#modal-export-masteritem').modal('show');
        });

        $('.btn-filter-masteritem').on("click", function(){
            $('#modal-filter-masteritem').modal('show');
        });

        $('.list_user_brand').on('change', function (e) {
            brand_code = this.value;
        });

        $('.list_barcode').on('change', function (e) {
            barcode = this.value;
        });

        $('.format-file-export').on('change', function (e) {
            format = this.value;
        });


        $('.btn-export').on("click", function(){
            if(format == "csv"){
                window.location.href = "<?= base_url('Laporan/export_csv_masteritem/'); ?>"+params1+'/'+params2+'/'+params3+'/'+params4+'/'+params5+'/'+params6;
            }else if(format == "xls"){
                window.location.href = "<?= base_url('Laporan/export_excel_masteritem/'); ?>"+params1+'/'+params2+'/'+params3+'/'+params4+'/'+params5+'/'+params6;
            }
        });

        $('.btn-submit-filter').on("click", function(){
            if (store === ''|| store == null){
                alert('Harap Pilih Store Dahulu')
                return false;
            }
            params1 = brand_code;
            params2 = division;
            params3 = sub_division;
            params4 = dept;
            params5 = sub_dept;
            params6 = store;

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

            load_data_masteritem(params1,params2,params3,params4,params5,params6);
        });

        function load_data_masteritem(params1,params2,params3,params4,params5,params6){
            tabel = $('#tb_masteritem_list').DataTable({
                "processing": true,
                "responsive":true,
                "serverSide": true,
                "bDestroy": true,
                "ordering": true, // Set true agar bisa di sorting
                "order": [[ 0, 'asc' ]], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
                "ajax":
                {
                    "url": "<?= base_url('Laporan/masteritem_where');?>", // URL file untuk proses select datanya
                    "type": "POST",
                    "data":  { "params1": params1,"params2": params2,"params3": params3,"params4": params4,"params5": params5,"params6": params6 }, 
                },
                "deferRender": true,
                "aLengthMenu": [[10, 25, 50],[ 10, 25, 50]], // Combobox Limit
                "columns": [
                    {"data": 'article_code',"sortable": false, 
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
                   
                    { "data": "article_number",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "article_code",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, // Tampilkan judul
                    { "data": "barcode",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },  // Tampilkan kategori
                    { "data": "supplier_pcode",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, 
                    { "data": "category_code",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, 
                    { "data": "article_name",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },  // Tampilkan penulis
                    { "data": "supplier_pname",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },  // Tampilkan penulis
                    { "data": "brand",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },  // Tampilkan tgl posting
                    { "data": "brand_name",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },  // Tampilkan tgl posting
                    { "data": "option1",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },  // Tampilkan tgl posting
                    { "data": "varian_option1",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },  // Tampilkan tgl posting
                    { "data": "option2",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "varian_option2",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
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
                    { "data": "normal_price",
                        "render": function ( data, type, row ) {
                                return '<nobr>RP '+rupiahjs(data)+'</nobr>';
                        },
                    },
                    { "data": "current_price",
                        "render": function ( data, type, row ) {
                                return '<nobr>RP '+rupiahjs(data)+'</nobr>';
                        },
                    },
                    { "data": "tag_5",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "add_date",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data.substring(0, 10)+'</nobr>';
                        },
                    }
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
                    $("#export-masteritem").show();
                    $("#filter-masteritem").show();
                    $('.list_user_brand').html(data);
                },
                beforeSend: function( xhr ) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-masteritem").hide();
                    $("#export-masteritem").hide();
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
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
                }
            });
        }

        $('.list_store').on('change', function (e) {
            store = this.value;
            $.ajax({
                url: "<?= base_url('Masterdata'); ?>/get_list_division",
                data: {
                    store : store
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    //console.log(data);
                    $(".loading").hide();
                    $('.list_division').html(data);
                },
                beforeSend: function( xhr ) {
                    // console.log(xhr);
                    $(".loading").show();
                }
            });
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

