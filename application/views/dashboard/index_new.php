<style>
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #f2125e;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        /* Safari */
        animation: spin 2s linear infinite;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<div class="content-wrapper">
    <?php $this->load->view('modal/filter-chartpenjualanbybrand', true); ?>
    <div class="row">
        <div class="col-xl-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <div>
                            <h4 class="card-title mb-0">Chart Penjualan By Brand</h4>
                            <p class="text-muted mb-2">Terapkan filter untuk menampilkan data.</p>
                        </div>
                        <div class="align-self-end">
                            <button type="button" class="btn btn-info btn-sm btn-icon-text btn-filter-penjualanbybrand" style="float:right">
                                <i class="typcn typcn-filter"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                    <div class="loader-wrapper">
                        <div class="h-100 d-flex align-items-center justify-content-center">
                            <div class="loader"></div>
                        </div>
                    </div>
                    <div class="embed-responsive embed-responsive-4by3" style="height:550px">
                        <iframe id="iFrameSalesMetaByBrand" class="embed-responsive-item" src="" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <h4 class="card-title pt-2">Top 10 Sales by brand last 3 month</h4>
                        <div class="mb-xl-0 pr-1 p-0">
                            <div class="dropdown">
                                <button class="btn bg-white btn-sm dropdown-toggle btn-icon-text border mr-2" type="button"
                                    id="choose-store" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php
                                    if ($storeid == 'R001')
                                        $storename = 'Rambla Kelapa Gading (R001)';
                                    if ($storeid == 'R002')
                                        $storename = 'Rambla Bandung (R002)';
                                    if ($storeid == 'V001')
                                        $storename = 'Happy Harvest Bandung (V001)';
                                    if ($storeid == 'S002')
                                        $storename = 'Star SMS (S002)';
                                    if ($storeid == 'S003')
                                        $storename = 'Star SMB (S003)';
                                    if ($storeid == 'V002')
                                        $storename = 'Happy Harvest Bogor (V002)';
                                    if ($storeid == 'V003')
                                        $storename = 'Happy Harvest Bekasi (S003)';
                                    ?>
                                    <i class="typcn typcn-location mr-2"></i>
                                    <?= $storename ?>
                                </button>
                                <div class="dropdown-menu opt-store" aria-labelledby="dropdownMenuSizeButton3"
                                    data-x-placement="top-start" x-placement="bottom-start"
                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                                    <h6 class="dropdown-header"></h6>
                                    <?php foreach ($site as $row) : ?>
                                        <a class="dropdown-item" style="cursor:pointer" data="<?= $row->branch_id; ?>"><?= $row->branch_name; ?> (<?= $row->branch_id; ?>)</a>
                                    <?php endforeach; ?>
                                    <!-- <a class="dropdown-item" style="cursor:pointer" data="R001">Rambla Kelapa Gading</a>
                            <a class="dropdown-item" style="cursor:pointer" data="R002">Rambla Bandung</a>
                            <a class="dropdown-item" style="cursor:pointer" data="V001">Happy Harvest Bandung</a> -->

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="loader-wrapper">
                        <div class="h-100 d-flex align-items-center justify-content-center">
                            <div class="loader"></div>
                        </div>
                    </div>
                    <div class="top10sales">
                        <div class="table-responsive ">
                            <table class="table table-striped table-dark" id="tb_ranking">
                                <thead class="">
                                    <tr>
                                        <th style="background-color: #f2125e; color: white">
                                            <nobr>Ranking</nobr>
                                        </th>
                                        <th style="background-color: #f2125e; color: white">
                                            <nobr>Periode</nobr>
                                        </th>
                                        <th style="background-color: #f2125e; color: white">
                                            <nobr>Brand Name</nobr>
                                        </th>
                                        <th style="background-color: #f2125e; color: white">
                                            <nobr>Tot Qty</nobr>
                                        </th>
                                        <th style="background-color: #f2125e; color: white">
                                            <nobr>Tot Net</nobr>
                                        </th>
                                        <th style="background-color: #392ccd; color: white">
                                            <nobr>Ranking</nobr>
                                        </th>
                                        <th style="background-color: #392ccd; color: white">
                                            <nobr>Periode</nobr>
                                        </th>
                                        <th style="background-color: #392ccd; color: white">
                                            <nobr>Brand Name</nobr>
                                        </th>
                                        <th style="background-color: #392ccd; color: white">
                                            <nobr>Tot Qty</nobr>
                                        </th>
                                        <th style="background-color: #392ccd; color: white">
                                            <nobr>Tot Net</nobr>
                                        </th>
                                        <th style="background-color: #ff8300; color: white;">
                                            <nobr>Ranking</nobr>
                                        </th>
                                        <th style="background-color: #ff8300; color: white;">
                                            <nobr>Periode</nobr>
                                        </th>
                                        <th style="background-color: #ff8300; color: white;">
                                            <nobr>Brand Name</nobr>
                                        </th>
                                        <th style="background-color: #ff8300; color: white;">
                                            <nobr>Tot Qty</nobr>
                                        </th>
                                        <th style="background-color: #ff8300; color: white;">
                                            <nobr>Tot Net</nobr>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="ranking">
                                </tbody>
                            </table>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?= base_url(); ?>assets/vendor/chartjs/js/loader.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/chartjs/js/chart.js"></script>
    <script type="text/JavaScript">
        var store_id = '<?= $storeid; ?>';
    $( document ).ready(function() {   
        get_store();
        get_user_brand();
        var store = null;
        var params1 = null;
        var params2 = null;
        var brandselectedValues = null;
        var params3 = periode;

        $('.list_user_brand').on('change', function() {
            brandselectedValues = $(this).val();  // Get an array of selected values
        });

        $('.btn-filter-penjualanbybrand').on("click", function() {
            $('#modal-filter-penjualanbybrand').modal('show');
        });

        load_chart_penjualanbybrand(params1, params2, params3);

        $('.btn-submit-filter').on("click", function() {
            params1 = store;
            params2 = brandselectedValues;
            params3 = periode;
            
            if (params1 === "" || params1 == null) {
                params1 = null;
                alert('Store Harus Dipilih')
                return false;
            }
            if (params2 === "" || params2 == null) {
                params2 = null;
                alert('Brand Harus Dipilih')
                return false;
            }

            if (params3 === "") {
                params3 = null;
            }

            if (hitungSelihBulan(params3) > 0) {
                alert('Range Tanggal Maksimal 1 Bulan')
                return false;
            }

            load_chart_penjualanbybrand(params1, params2, params3);
        });

        function load_chart_penjualanbybrand(params1, params2, params3) {
            $.ajax({
                url: "<?= base_url('Dashboard/penjualan_brand_where'); ?>",
                method: "POST",
                data: {
                    params1: params1,
                    params2: params2,
                    params3: params3,
                },
                beforeSend: function( xhr ) {
                    // console.log(xhr);
                    $('.loader-wrapper').show();
                    $('.embed-responsive').hide();
                },
                success: function(data) {
                    $('.loader-wrapper').hide();
                    $('.embed-responsive').show();
                    var iframe = document.getElementById('iFrameSalesMetaByBrand');
                    iframe.src = data;
                }
            });
        }

        // function load_default() {
        //     $.ajax({
        //         url: "<?= base_url('Dashboard/default_load'); ?>",
        //         method: "POST",
        //         dataType: "json",
        //         success: function(data) {
        //             var html = '';
        //             $.each(data.kode_brand, function(index, item) {
        //                 html += '<li class="select2-selection__choice" title="WMS Luggage (ADE)" data-select2-id="180"><span class="select2-selection__choice__remove" role="presentation">×</span>WMS Luggage (ADE)</li><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="" style="width: 0.75em;"></li>';
        //             });
        //             $(".select2-selection__rendered").append(html);
        //             $('.list_store').append(`<option value=''>-- Pilih Datsa --</option>`);
        //             params1 = data.kode_toko;
        //         }
        //     });
        // }

            
        $('.opt-store .dropdown-item').on('click', function(){
            //console.log($(this).attr('data'));
            $('#choose-store').html('<i class="typcn typcn-location mr-2"></i>'+$(this).text());
            // $('#filter-store input').val($(this).attr('data'));
            store_id = $(this).attr('data');
            GetTop10Rank(store_id);
            // $('#filter-store form').trigger('submit');
        })

        GetTop10Rank(store_id);

        function GetTop10Rank(store_id){
            $.ajax({
                type: "GET",
                url: "<?= base_url(); ?>Dashboard/get_top10_rank/"+store_id,
                dataType: "JSON",
                beforeSend: function( xhr ) {
                    // console.log(xhr);
                    $('.top10sales').hide();
                    $('.loader-wrapper').show();
                },
                success: function(data) { 
                    // console.log(data["hasil"]);
                    $('.top10sales').show();
                    $('.loader-wrapper').hide();
                    var html = '';
                    var special_price = '';
                    for (var i = 0; data["hasil"]["length"] > i; i++) {
                        html += '<tr>' +
                            '<td class="py-2"><nobr>'+data["hasil"][i].ranking1+'</nobr></td>' +
                            '<td class="py-2"><nobr>'+data["hasil"][i].periode1+'</nobr></td>' +
                            '<td class="py-2"><nobr>'+data["hasil"][i].brand_name1+'</nobr></td>' +
                            '<td class="py-2"><nobr>'+data["hasil"][i].tot_qty1+'</nobr></td>' +
                            '<td class="py-2"><nobr>Rp '+rupiahjs(data["hasil"][i].tnet1)+'</nobr></td>' +
                            '<td class="py-2"><nobr>'+data["hasil"][i].ranking2+'</nobr></td>' +
                            '<td class="py-2"><nobr>'+data["hasil"][i].periode2+'</nobr></td>' +
                            '<td class="py-2"><nobr>'+data["hasil"][i].brand_name2+'</nobr></td>' +
                            '<td class="py-2"><nobr>'+data["hasil"][i].tot_qty2+'</nobr></td>' +
                            '<td class="py-2"><nobr>Rp '+rupiahjs(data["hasil"][i].tnet2)+'</nobr></td>' +
                            '<td class="py-2"><nobr>'+data["hasil"][i].ranking3+'</nobr></td>' +
                            '<td class="py-2"><nobr>'+data["hasil"][i].periode3+'</nobr></td>' +
                            '<td class="py-2"><nobr>'+data["hasil"][i].brand_name3+'</nobr></td>' +
                            '<td class="py-2"><nobr>'+data["hasil"][i].tot_qty3+'</nobr></td>' +
                            '<td class="py-2"><nobr>Rp '+rupiahjs(data["hasil"][i].tnet3)+'</nobr></td>' +
                            '</tr>';
                    }
                    $("#ranking").html(html);
                }
            });
        }

        function get_user_brand() {
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
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanartikel").hide();
                    $("#export-penjualanartikel").hide();
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
            $.ajax({
                url: "<?= base_url('Masterdata'); ?>/get_list_division",
                data: {
                    store: store
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    // console.log(data);
                    $(".loading").hide();
                    $('.list_division').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                }
            });
        })

    });
</script>