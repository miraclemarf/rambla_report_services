<style>
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
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
    <div class="row">
        <div class="col-xl-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <h4 class="card-title mb-3">Top 10 Sales by brand last 3 month</h4>
                    </div>
                    <div id="loader-wrapper">
                        <div class="h-100 d-flex align-items-center justify-content-center">
                            <div class="loader"></div>
                        </div>
                    </div>
                    <div class="top10sales">
                        <div class="table-responsive ">
                            <table class="table table-striped table-dark" id="tb_ranking">
                                <thead class="">
                                    <tr>
                                        <th style="background-color: #2E20C7; color: white">
                                            <nobr>Ranking</nobr>
                                        </th>
                                        <th style="background-color: #2E20C7; color: white">
                                            <nobr>Periode</nobr>
                                        </th>
                                        <th style="background-color: #2E20C7; color: white">
                                            <nobr>Brand Name</nobr>
                                        </th>
                                        <th style="background-color: #2E20C7; color: white">
                                            <nobr>Tot Qty</nobr>
                                        </th>
                                        <th style="background-color: #2E20C7; color: white">
                                            <nobr>Tot Net</nobr>
                                        </th>
                                        <th style="background-color: #0F5ED5FF; color: white">
                                            <nobr>Ranking</nobr>
                                        </th>
                                        <th style="background-color: #0F5ED5FF; color: white">
                                            <nobr>Periode</nobr>
                                        </th>
                                        <th style="background-color: #0F5ED5FF; color: white">
                                            <nobr>Brand Name</nobr>
                                        </th>
                                        <th style="background-color: #0F5ED5FF; color: white">
                                            <nobr>Tot Qty</nobr>
                                        </th>
                                        <th style="background-color: #0F5ED5FF; color: white">
                                            <nobr>Tot Net</nobr>
                                        </th>
                                        <th style="background-color: #2b80ff; color: white;">
                                            <nobr>Ranking</nobr>
                                        </th>
                                        <th style="background-color: #2b80ff; color: white;">
                                            <nobr>Periode</nobr>
                                        </th>
                                        <th style="background-color: #2b80ff; color: white;">
                                            <nobr>Brand Name</nobr>
                                        </th>
                                        <th style="background-color: #2b80ff; color: white;">
                                            <nobr>Tot Qty</nobr>
                                        </th>
                                        <th style="background-color: #2b80ff; color: white;">
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

        <script type="text/javascript" src="<?= base_url(); ?>assets/vendor/chartjs/js/loader.js"></script>
        <script src="<?= base_url(); ?>assets/vendor/chartjs/js/chart.js"></script>
        <script type="text/JavaScript">
            $( document ).ready(function() {    

        GetTop10Rank();
    
   

    function GetTop10Rank(){
        $.ajax({
            type: "GET",
            url: "<?= base_url(); ?>Dashboard/get_top10_rank",
            dataType: "JSON",
            beforeSend: function( xhr ) {
                // console.log(xhr);
                $('.top10sales').hide();
                $('#loader-wrapper').show();
            },
            success: function(data) { 
                // console.log(data["hasil"]);
                $('.top10sales').show();
                $('#loader-wrapper').hide();
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


    });
</script>