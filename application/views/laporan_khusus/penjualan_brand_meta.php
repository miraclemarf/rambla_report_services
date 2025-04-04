<style>
    table,
    th,
    td {
        border: 1px solid white;
    }

    @media (min-width: 768px) {
    .member-only {
        padding: 0px;
    }
    .frame-meta {
        height:550px;
    }
}

@media (min-width: 576px) and (max-width: 767.98px) {
    .member-only {
        padding: 0px;
    }
}


/* Responsive columns */
@media (max-width: 575.98px) {
    .card {
        min-height: 500px;
        max-height: 100%;
    }

    .embed-responsive {
        height: 100%;
    }
    .member-only {
        padding: 15px;
    }
}
</style>
<div class="content-wrapper">
    <?php $this->load->view('modal/export-penjualanbrand', true); ?>
    <?php $this->load->view('modal/filter-penjualanbrand', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <div>
                            <h4 class="card-title mb-0">Laporan Penjualan By Brand - Meta</h4>
                            <p class="text-muted mb-2">Terapkan filter untuk menampilkan data.</p>
                        </div>
                        <div class="align-self-end">
                            <button type="button" class="btn btn-info btn-sm btn-icon-text btn-filter-pejualanbrand" style="float:right">
                                <i class="typcn typcn-filter"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                    <div class="embed-responsive embed-responsive-4by3">
                        <iframe id="iFrameSalesMetaByBrand" class="embed-responsive-item" src="" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

        $('#modal-filter-penjualanbrand').modal('show');

        $('.btn-filter-pejualanbrand').on("click", function() {
            $('#modal-filter-penjualanbrand').modal('show');
        });

        $('.list_user_brand').on('change', function(e) {
            brand_code = this.value;
        });

        $('.list_source').on('change', function(e) {
            source = this.value;
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
            load_data_penjualanbrand(params1, params2, params3, params4, params5, params6, params7, params8, params9);
        });

        function resizeIframe() {
            var iframe = document.getElementById('iFrameSalesMetaByBrand');
            iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
        }

        // Panggil fungsi resizeIframe setelah iframe selesai dimuat
        window.onload = function() {
            resizeIframe();
        };

        function load_data_penjualanbrand(params1, params2, params3, params4, params5, params6, params7, params8, params9) {
            $.ajax({
                url: "<?= base_url('LaporanKhusus/penjualan_brand_meta_where'); ?>",
                method: "POST",
                data: {
                    params1: params1,
                    params2: params2,
                    params3: params3,
                    params4: params4,
                    params5: params5,
                    params6: params6,
                    params7: params7,
                    params8: params8,
                    params9: params9
                },
                success: function(data) {
                    var iframe = document.getElementById('iFrameSalesMetaByBrand');
                    iframe.src = data;
                }
            });
            // document.getElementById('iFrameSalesMetaByBrand').src = baseUrl;
        }

        function get_user_brand() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_user_brand",
                dataType: "html",
                success: function(data) {
                    $(".loading").hide();
                    $("#export-penjualanbrand").show();
                    $("#filter-penjualanbrand").show();
                    $('.list_user_brand').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-penjualanbrand").hide();
                    $("#export-penjualanbrand").hide();
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
                    $("#export-penjualanbrand").show();
                    $("#filter-penjualanbrand").show();
                    $('.list_store').html(data);
                },
                beforeSend: function(xhr) {
                    $(".loading").show();
                    $("#filter-penjualanbrand").hide();
                    $("#export-penjualanbrand").hide();
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