<div class="content-wrapper">
<?php $this->load->view('modal/export-promo', true); ?>
<?php $this->load->view('modal/filter-promo', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <div>
                            <h4 class="card-title mb-0">Laporan Promo</h4>
                            <p class="text-muted mb-2">Terapkan filter untuk menampilkan data.</p>
                        </div>
                        <div class="align-self-end">
                            <button type="button" class="btn btn-success btn-sm btn-icon-text btn-export-promo ml-2" style="float:right">
                            <i class="typcn typcn-download btn-icon-prepend"></i>                                                    
                            Export File
                            </button>
                            <button type="button" class="btn btn-info btn-sm btn-icon-text btn-filter-promo" style="float:right">
                            <i class="typcn typcn-filter"></i>                                                
                                Filter
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-striped table-custom" id="tb_promo">
                        <thead class="table-rambla">
                            <tr>
                            <th>#</th>
                            <th>Store</th>
                            <th><nobr>Start Date</nobr></th>
                            <th><nobr>End Date</nobr></th>
                            <th><nobr>Promo Id</nobr></th>
                            <th><nobr>Promo Type</nobr></th>
                            <th><nobr>Category Code</nobr></th>
                            <th><nobr>Vendor Name</nobr></th>
                            <th><nobr>Brand</nobr></th>
                            <th><nobr>Barcode</nobr></th>
                            <th><nobr>Article Name</nobr></th>
                            <th><nobr>Varian Option1</nobr></th>
                            <th><nobr>Varian Option2</nobr></th>
                            <th><nobr>Promo desc</nobr></th>
                            <th><nobr>Current Price</nobr></th>
                            <th><nobr>Min Qty</nobr></th>
                            <th><nobr>Min Purchase</nobr></th>
                            <th><nobr>Disc %</nobr></th>
                            <th><nobr>Disc Amount</nobr></th>
                            <th><nobr>Add Disc %</nobr></th>
                            <th><nobr>Free Qty</nobr></th>
                            <th><nobr>Special Price</nobr></th>
                            <th><nobr>Aktif</nobr></th>
                            <th><nobr>Monday</nobr></th>
                            <th><nobr>Tuesday</nobr></th>
                            <th><nobr>Wednesday</nobr></th>
                            <th><nobr>Thusday</nobr></th>
                            <th><nobr>Friday</nobr></th>
                            <th><nobr>Saturday</nobr></th>
                            <th><nobr>Sunday</nobr></th>
                            <th><nobr>Division</nobr></th>
                            <th><nobr>Sub Division</nobr></th>
                            <th><nobr>Dept</nobr></th>
                            <th><nobr>Sub Dept</nobr></th>
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
        get_list_prmotype();
        get_division();
        get_sub_division();
        get_dept();
        get_list_dept();
        get_store();
        var brand_code      = null;
        var promo           = null;
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
        var params7         = null;
        var params8         = null;
        var format          = null;
     
        // console.log(params3);
        $('#modal-filter-promo').modal('show');
        // load_data_promo(params1,params2,params3,params4,params5,params6,params7);

        $('.btn-export-promo').on("click", function(){
            $('#modal-export-promo').modal('show');
        });

        $('.btn-filter-promo').on("click", function(){
            $('#modal-filter-promo').modal('show');
        });

        $('.list_user_brand').on('change', function (e) {
            brand_code = this.value;
        });

        $('.list_barcode').on('change', function (e) {
            barcode = this.value;
        });

        $('.list_tipe_promo').on('change', function (e) {
            promo = this.value;
        });

        $('.format-file-export').on('change', function (e) {
            format = this.value;
        });

        $('.btn-submit-filter').on("click", function(){
            if (store === ''|| store == null){
                alert('Harap Pilih Store Dahulu')
                return false;
            }
            params1 = brand_code;
            params2 = promo;
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
            load_data_promo(params1,params2,params3,params4,params5,params6,params7, params8);
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
                window.location.href = "<?= base_url('Laporan/export_csv_promo/'); ?>"+params1+'/'+params2+'/'+data.fromdate+'/'+data.todate+'/'+params4+'/'+params5+'/'+params6+'/'+params7+'/'+params8;
              }else if(format == "xls"){
                window.location.href = "<?= base_url('Laporan/export_excel_promo/'); ?>"+params1+'/'+params2+'/'+data.fromdate+'/'+data.todate+'/'+params4+'/'+params5+'/'+params6+'/'+params7+'/'+params8;
              }
            }
          });
        }

        // function load_data_promotest(params1,params2,params3,params4,params5,params6,params7, params8) {
        //   $.ajax({
        //     type: "POST",
        //     url: "<?= base_url('Laporan/promo_where');?>",
        //     dataType: "JSON",
        //     data: { "params1": params1,"params2": params2,"params3": params3,"params4": params4,"params5": params5,"params6": params6,"params7": params7,"params8": params8 },
        //         success: function(data) {
        //         console.log(data);
        //         }
        //   });
        // }

        function load_data_promo(params1,params2,params3,params4,params5,params6,params7, params8){
            tabel = $('#tb_promo').DataTable({
                "processing": true,
                "responsive":true,
                "serverSide": true,
                "bDestroy": true,
                "ordering": true, // Set true agar bisa di sorting
                "order": [[ 0, 'asc' ]], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
                "ajax":
                {
                    "url": "<?= base_url('Laporan/promo_where');?>", // URL file untuk proses select datanya
                    "type": "POST",
                    "data":  { "params1": params1,"params2": params2,"params3": params3,"params4": params4,"params5": params5,"params6": params6,"params7": params7, "params8": params8}, 
                },
                "deferRender": true,
                "aLengthMenu": [[10, 25, 50],[ 10, 25, 50]], // Combobox Limit
                "columns": [
                    {"data": 'id',"sortable": false, 
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
                    { "data": "start_date",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "end_date",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, // Tampilkan judul
                    { "data": "promo_id",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },  // Tampilkan kategori
                    { "data": "promo_type",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, 
                    { "data": "category_code",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    }, 
                    { "data": "vendor_name",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },  // Tampilkan penulis
                    { "data": "brand",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },  // Tampilkan tgl posting
                    { "data": "barcode",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "pos_pname",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                     // Tampilkan tgl posting
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
                    { "data": "promo_desc",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "current_price",
                        "render": function ( data, type, row ) {
                                return '<nobr>Rp '+rupiahjs(data)+'</nobr>';
                        },
                    },
                    { "data": "min_qty",
                        "render": function ( data, type, row ) {
                            return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "min_purchase",
                        "render": function ( data, type, row ) {
                            return '<nobr>Rp '+rupiahjs(data)+'</nobr>';
                        },
                    },
                    { "data": "disc_percentage",
                        "render": function ( data, type, row ) {
                            return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "disc_amount",
                        "render": function ( data, type, row ) {
                            return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "add_disc_percentage",
                        "render": function ( data, type, row ) {
                            return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "free_qty",
                        "render": function ( data, type, row ) {
                            return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "special_price",
                        "render": function ( data, type, row ) {
                                return '<nobr>Rp '+rupiahjs(data)+'</nobr>';
                        },
                    },
                    { "data": "aktif",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "active_monday",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "active_tuesday",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "active_wednesday",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "active_thursday",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "active_friday",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "active_saturday",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "active_sunday",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "DIVISION",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "SUB_DIVISION",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "DEPT",
                        "render": function ( data, type, row ) {
                                return '<nobr>'+data+'</nobr>';
                        },
                    },
                    { "data": "SUB_DEPT",
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
                    $("#export-promo").show();
                    $("#filter-promo").show();
                    $('.list_user_brand').html(data);
                },
                beforeSend: function( xhr ) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-promo").hide();
                    $("#export-promo").hide();
                }
            });
        }

        // function get_list_barcode(){
        //     $.ajax({
        //         type: "GET",
        //         url: "<?= base_url('Masterdata'); ?>/get_list_barcode",
        //         dataType: "html",
        //         success: function(data) {
        //             // console.log(data);
        //             $(".loading").hide();
        //             $("#export-promo").show();
        //             $("#filter-promo").show();
        //             $('.list_barcode').html(data);
        //         },
        //         beforeSend: function( xhr ) {
        //             // console.log(xhr);
        //             $(".loading").show();
        //             $("#filter-promo").hide();
        //             $("#export-promo").hide();
        //         }
        //     });
        // }

        function get_list_prmotype(){
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_list_prmotype",
                dataType: "html",
                success: function(data) {
                    // console.log(data);
                    $(".loading").hide();
                    $("#export-promo").show();
                    $("#filter-promo").show();
                    $('.list_tipe_promo').html(data);
                },
                beforeSend: function( xhr ) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-promo").hide();
                    $("#export-promo").hide();
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

