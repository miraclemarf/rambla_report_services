<div class="content-wrapper">

    <div class="row">
        <div class="col-xl-3 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="container-salesbyday">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h4 class="card-title mb-3">Sales by day</h4>
                        </div>
                        <div class="row mb-5">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="tb_sales">
                                                <thead class="" >
                                                    <tr>
                                                        <th>Periode</th>
                                                        <th>Tot_Qty</th>
                                                        <th>Net</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="sales">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-salesbymonth">
                        <hr>
                        <div class="d-flex flex-wrap justify-content-between mt-5">
                            <h4 class="card-title mb-3">Sales by month</h4>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="tb_sales">
                                                <thead class="" >
                                                    <tr>
                                                        <th>Periode</th>
                                                        <th>Tot_Qty</th>
                                                        <th>Net</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="sales2">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="col-xl-9 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <h4 class="card-title mb-3">Daily Sales Analytics</h4>
                    </div>
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="d-md-flex">
                                <div class="mr-md-3 mb-4">
                                    <div class="form-group">
                                        <h5 class="mb-1"><i class="typcn typcn-calendar mr-1"></i>Year</h5>
                                        <select id="tahun_omset" class="form-control form-control-lg" >
                                            <?php foreach($year as $row): ?>
                                                <option value="<?= $row->tahun; ?>" <?php if($row->tahun == date('Y')): ?>selected<?php endif; ?>><?= $row->tahun; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mr-md-3 mb-4">
                                    <div class="form-group">
                                    <h5 class="mb-1"><i class="typcn typcn-calendar mr-1"></i>Month</h5>
                                    <select id="bulan_omset" class="form-control" >
                                        <option value="">Pilih Bulan</option> 
                                        <option value="1" <?php if(date("m") == "1"): ?> selected <?php endif; ?>>Januari</option>
                                        <option value="2" <?php if(date("m") == "2"): ?> selected <?php endif; ?>>February</option>
                                        <option value="3" <?php if(date("m") == "3"): ?> selected <?php endif; ?>>Maret</option>
                                        <option value="4" <?php if(date("m") == "4"): ?> selected <?php endif; ?>>April</option>
                                        <option value="5" <?php if(date("m") == "5"): ?> selected <?php endif; ?>>May</option>
                                        <option value="6" <?php if(date("m") == "6"): ?> selected <?php endif; ?>>Juni</option>
                                        <option value="7" <?php if(date("m") == "7"): ?> selected <?php endif; ?>>Juli</option>
                                        <option value="8" <?php if(date("m") == "8"): ?> selected <?php endif; ?>>Agustus</option>
                                        <option value="9" <?php if(date("m") == "9"): ?> selected <?php endif; ?>>September</option>
                                        <option value="10" <?php if(date("m") == "10"): ?> selected <?php endif; ?>>Oktober</option>
                                        <option value="11" <?php if(date("m") == "11"): ?> selected <?php endif; ?>>November</option>
                                        <option value="12" <?php if(date("m") == "12"): ?> selected <?php endif; ?>>Desember</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="mr-md-3 mb-4">
                                    <div class="form-group">
                                        <h5 class="mb-1"><i class="typcn typcn-tags mr-1"></i>Brand</h5>
                                        <select id="brand_code" class="form-control form-control-lg js-example-basic-single">
                                            <option value="all">All</option>
                                            <?php foreach($list_brand as $row): ?>
                                                <option value="<?= $row->brand; ?>"><?= $row->brand_name; ?> (<?= $row->brand; ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <canvas id="chart-omset"></canvas> 
                        </div>
                    </div>
                    <div class="top10sales">
                        <hr>
                        <div class="d-flex flex-wrap justify-content-between mt-5">
                            <h4 class="card-title mb-3">Top 10 Sales by brand last 3 month</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="tb_ranking">
                                <thead class="">
                                    <tr>
                                    <th style="background-color: #17c964; color: white"><nobr>Ranking</nobr></th>
                                    <th style="background-color: #17c964; color: white"><nobr>Periode</nobr></th>
                                    <th style="background-color: #17c964; color: white"><nobr>Brand Name</nobr></th>
                                    <th style="background-color: #17c964; color: white"><nobr>Tot Qty</nobr></th>
                                    <th style="background-color: #17c964; color: white"><nobr>Tot Net</nobr></th>
                                    <th style="background-color: #f2125e; color: white"><nobr>Ranking</nobr></th>
                                    <th style="background-color: #f2125e; color: white"><nobr>Periode</nobr></th>
                                    <th style="background-color: #f2125e; color: white"><nobr>Brand Name</nobr></th>
                                    <th style="background-color: #f2125e; color: white"><nobr>Tot Qty</nobr></th>
                                    <th style="background-color: #f2125e; color: white"><nobr>Tot Net</nobr></th>
                                    <th style="background-color: #2b80ff; color: white;"><nobr>Ranking</nobr></th>
                                    <th style="background-color: #2b80ff; color: white;"><nobr>Periode</nobr></th>
                                    <th style="background-color: #2b80ff; color: white;"><nobr>Brand Name</nobr></th>
                                    <th style="background-color: #2b80ff; color: white;"><nobr>Tot Qty</nobr></th>
                                    <th style="background-color: #2b80ff; color: white;"><nobr>Tot Net</nobr></th>
                                    </tr>
                                </thead>
                                <tbody id="ranking">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url(); ?>assets/vendor/chartjs/js/loader.js"></script>
<script src="<?= base_url(); ?>assets/vendor/chartjs/js/chart.js"></script>
<script type="text/JavaScript">
    $( document ).ready(function() {    
        get_salesbyday();
        get_salesbymonth();
        GetTop10Rank();
        $("#loading").hide();
    
    $(document).on("change","#tahun_omset",function(e){
        var bulan = $('#bulan_omset').find(":selected").val();
        var tahun = this.value;
        var brand = $('#brand_code').find(":selected").val();
        loadGraphOffline(bulan,tahun, brand);
        loadGraphOnline(bulan,tahun, brand);
    });

    $(document).on("change","#bulan_omset",function(e){
        var bulan = this.value;
        var tahun = $('#tahun_omset').find(":selected").val();
        var brand = $('#brand_code').find(":selected").val();
        loadGraphOffline(bulan,tahun, brand);
        loadGraphOnline(bulan,tahun, brand);
    });

    $(document).on("change","#brand_code",function(e){
        var bulan = $('#bulan_omset').find(":selected").val();
        var tahun = $('#tahun_omset').find(":selected").val();
        var brand = this.value;
        loadGraphOffline(bulan,tahun, brand);
        loadGraphOnline(bulan,tahun, brand);
    });

    function loadGraphOffline(bulan, tahun, brand){
        $.ajax({
            url: '<?= base_url(); ?>Dashboard/fetch_data_omset',
            data: {
                tahun : tahun,
                bulan : bulan,
                brand : brand,
                fa    : 0
            },
            type: 'POST',
            dataType: 'JSON',
            success: function(data) {
                console.log(data);
                tgls = [];
                totals = [];
                $.each(data, function(key, val){
                    var tgl = val.periode;
                    var total = val.net;
                    tgls.push(tgl);
                    totals.push(total);
                });
                // console.log(tgls);
                // console.log(totals);
                chart.data.labels = tgls;
                chart.data.datasets[0].data = totals;
                chart.update();
            }
        });
    }
    function loadGraphOnline(bulan, tahun, brand){
        $.ajax({
            url: '<?= base_url(); ?>Dashboard/fetch_data_omset',
            data: {
                tahun : tahun,
                bulan : bulan,
                brand : brand,
                fa    : 1
            },
            type: 'POST',
            dataType: 'JSON',
            success: function(data) {
                // console.log(data);
                tgls2 = [];
                totals2 = [];
                $.each(data, function(key, val){
                    var tgl2 = val.periode;
                    var total2 = val.net;
                    tgls2.push(tgl2);
                    totals2.push(total2);
                });
                // console.log(tgls);
                // console.log(totals);
                chart.data.labels = tgls2;
                chart.data.datasets[1].data = totals2;
                chart.update();
            }
        });
    }

    function get_salesbyday(){
        $.ajax({
            type: "GET",
            url: "<?= base_url(); ?>Dashboard/get_salesbyday",
            dataType: "JSON",
            success: function(data) { 
                // console.log(data["hasil"]);
                $(".container-salesbyday").show();
                var html = '';
                let sum = 0;
                for (var i = 0; data["hasil"]["length"] > i; i++) {
                    html += '<tr>' +
                            '<td class="py-2"><nobr>'+ data["hasil"][i]["periode"] +'</nobr></td>' +
                            '<td class="py-2"><nobr>' + data["hasil"][i]["tot_qty"] +'</nobr></td>' +
                            '<td class="py-2"><nobr>  Rp ' + rupiahjs(data["hasil"][i]["net"]) +'</nobr></td>' +
                            '</tr>';
                    sum += parseInt(data["hasil"][i].net);
                }
                html += '<tr>' +
                    '<td colspan="2" style="text-align: center"><nobr><strong>Total</strong></nobr></td>'+
                    '<td class="py-2"><nobr><strong>Rp '+ rupiahjs(sum) + '</strong></nobr></td>'+
                    '</tr>';
                $("#sales").html(html);
            },
            beforeSend: function( xhr ) {
                // console.log(xhr);
                $(".container-salesbyday").hide();
            },
        });
    }

    function get_salesbymonth(){
        $.ajax({
            type: "GET",
            url: "<?= base_url(); ?>Dashboard/get_salesbymonth",
            dataType: "JSON",
            success: function(data) { 
                $(".container-salesbymonth").show();
                // console.log(data["hasil"]);
                var html = '';
                let sum = 0;
                for (var i = 0; data["hasil"]["length"] > i; i++) {
                    html += '<tr>' +
                            '<td class="py-2"><nobr>'+ data["hasil"][i]["periode"] +'</nobr></td>' +
                            '<td class="py-2"><nobr>' + data["hasil"][i]["tot_qty"] +'</nobr></td>' +
                            '<td class="py-2"><nobr>  Rp ' + rupiahjs(data["hasil"][i]["net"]) +'</nobr></td>' +
                            '</tr>';
                    sum += parseInt(data["hasil"][i].net);
                }
                html += '<tr>' +
                    '<td colspan="2" style="text-align: center"><nobr><strong>Total</strong></nobr></td>'+
                    '<td class="py-2"><nobr><strong>Rp '+ rupiahjs(sum) + '</strong></nobr></td>'+
                    '</tr>';
                $("#sales2").html(html);
            },
            beforeSend: function( xhr ) {
                // console.log(xhr);
                $(".container-salesbymonth").hide();
            },
        });
    }

    function GetTop10Rank(){
        $.ajax({
            type: "GET",
            url: "<?= base_url(); ?>Dashboard/get_top10_rank",
            dataType: "JSON",
            beforeSend: function( xhr ) {
                // console.log(xhr);
                $('.top10sales').hide();
            },
            success: function(data) { 
                // console.log(data["hasil"]);
                $('.top10sales').show();
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

    // BAR CHART
    var ctx = document.getElementById('chart-omset').getContext('2d');
    var chart = new Chart(ctx, {
    type: 'bar',
    // type: 'line',
    data: {
        labels: [
            <?php
                if (count($omset_date)>0) {
                    foreach ($omset_date as $tgl) {
                        echo "'".indo_date3($tgl->periode)."',";
                    }
                }   
            ?>
        ],
        datasets: [{
            label: 'Penjualan Offline',
            backgroundColor: '#5754f5',
            borderColor: '#6D6BF5',
            data: [
                <?php
                    if (count($omset_pos)>0) {
                        foreach ($omset_pos as $row) {
                            echo "'" .$row->net ."',";
                        }
                    }
                ?>
            ]
          },
          {
            label: 'Penjualan Online',
            backgroundColor: '#b1c3e6',
            borderColor: '#D1E0FE',
            data: [
                <?php
                    if (count($omset_apps)>0) {
                        foreach ($omset_apps as $row) {
                            echo "'" .$row->net ."',";
                        }
                    }
                ?>
            ]
          },
        ]
        },
    });
    // END BAR CHART

    });
</script>