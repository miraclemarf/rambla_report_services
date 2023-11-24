<div class="content-wrapper">
<?php $this->load->view('modal/export-masteritem', true); ?>
<?php $this->load->view('modal/filter-masteritem', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <h4 class="card-title mb-3">Laporan List Item Master</h4>
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
                            <th><nobr>Normal Price</nobr></th>
                            <th><nobr>Tag 5</nobr></th>
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
        load_data_masteritem(null,null,null);
        get_user_brand();
        get_list_barcode();
        var brand_code  = null;
        var barcode     = null;
        var params1     = '';
        var params2     = '';
        var params3     = '';
        var format      = null;
    

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
            if(brand_code == null){
                brand_code = '';
            }else{
                brand_code = brand_code;
            }
            if(format == "csv"){
                window.location.href = "<?= base_url('Laporan/export_csv_masteritem/'); ?>"+brand_code;
            }else if(format == "xls"){
                window.location.href = "<?= base_url('Laporan/export_excel_masteritem/'); ?>"+brand_code;
            }
        });

        $('.btn-submit-filter').on("click", function(){
            params1 = brand_code;
            params2 = barcode;
            // console.log(params1,params2);
            load_data_masteritem(params1,params2,null);
        });

        function load_data_masteritem(params1, params2, params3){
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
                    "data":  { "params1": params1,"params2": params2,"params3": params3 }, 
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
                    { "data": "normal_price",
                        "render": function ( data, type, row ) {
                                return '<nobr>RP '+rupiahjs(data)+'</nobr>';
                        },
                    },
                    { "data": "tag_5",
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

        function get_list_barcode(){
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_list_barcode",
                dataType: "html",
                success: function(data) {
                    // console.log(data);
                    $(".loading").hide();
                    $("#export-masteritem").show();
                    $("#filter-masteritem").show();
                    $('.list_barcode').html(data);
                },
                beforeSend: function( xhr ) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-masteritem").hide();
                    $("#export-masteritem").hide();
                }
            });
        }
    });
</script>

