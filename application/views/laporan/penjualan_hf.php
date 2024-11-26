<div class="content-wrapper">
    <?php $this->load->view('modal/filter-penjualanartikel', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <div>
                            <h4 class="card-title mb-0">Laporan Penjualan Happy Fresh</h4>
                        </div>
                    </div>
                    <div class="">
                        <table class="table table-striped table-custom" id="tb_penjualanartikel_list">
                            <thead class="table-rambla">
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <nobr>Trans No.</nobr>
                                    </th>
                                    <th>
                                        <nobr>Periode</nobr>
                                    </th>
                                    <th>
                                        <nobr>Jam</nobr>
                                    </th>
                                    <th>
                                        <nobr>DIVISION</nobr>
                                    </th>
                                    <th>
                                        <nobr>SUB DIVISION</nobr>
                                    </th>
                                    <th>
                                        <nobr>DEPT</nobr>
                                    </th>
                                    <th>
                                        <nobr>SUB DEPT</nobr>
                                    </th>
                                    <th>
                                        <nobr>Article Code</nobr>
                                    </th>
                                    <th>
                                        <nobr>Barcode</nobr>
                                    </th>
                                    <th>
                                        <nobr>Kode Brand</nobr>
                                    </th>
                                    <th>
                                        <nobr>Nama Brand</nobr>
                                    </th>
                                    <th>
                                        <nobr>Nama Produk</nobr>
                                    </th>
                                    <th>
                                        <nobr>Harga</nobr>
                                    </th>
                                    <th>
                                        <nobr>Member</nobr>
                                    </th>
                                    <th>
                                        <nobr>Total Qty(Pcs)</nobr>
                                    </th>
                                    <th>
                                        <nobr>Total Berat(Kg)</nobr>
                                    </th>
                                    <th>
                                        <nobr>Disc(%)</nobr>
                                    </th>
                                    <th>
                                        <nobr>Total Disc(Rp)</nobr>
                                    </th>
                                    <th>
                                        <nobr>Disc. Tambahan(%)</nobr>
                                    </th>
                                    <th>
                                        <nobr>Disc. Tambahan(Rp)</nobr>
                                    </th>
                                    <th>
                                        <nobr>Gross(Rp)</nobr>
                                    </th>
                                    <th>
                                        <nobr>Net (Rp)</nobr>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $("#tb_penjualanartikel_list").DataTable({
            "processing": true,
            "ajax": {
                "url": "<?= base_url('LaporanExt/list_sales_hf'); ?>",
                "type": "POST"
            },
            "scrollX": true,
            "dom": 'lBfrtip', // Add buttons to the DOM
            "buttons": [{
                    "extend": 'csvHtml5',
                    "title": 'Laporan Penjualan Happy Fresh',
                    "filename": 'Sales-HFxHH-' + moment().format("YYYYMMDD")
                },
                {
                    "extend": 'excelHtml5',
                    "title": 'Laporan Penjualan Happy Fresh',
                    "filename": 'Sales-HFxHH-' + moment().format("YYYYMMDD")
                }
            ],
            "columns": [{
                    "data": "periode",
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "trans_no",
                    "type": "any-number",
                },
                {
                    "data": "periode",
                    "render": function(data, type, row, meta) {
                        if (type === 'sort') {
                            return moment(data).format("X");
                        }
                        return moment(data).format("DD MMM YYYY");
                    }

                },
                {
                    "data": "trans_time",
                    "type": "any-number",
                    "render": function(data, type, row) {
                        const jam = data.split(":");
                        return jam[0] + ':' + jam[1];
                    },

                },
                {
                    "data": "DIVISION"
                },
                {
                    "data": "SUB_DIVISION"
                },
                {
                    "data": "DEPT"
                },
                {
                    "data": "SUB_DEPT"
                },
                {
                    "data": "article_code"
                },
                {
                    "data": "barcode"
                },
                {
                    "data": "brand_code"
                },
                {
                    "data": "brand_name"
                },
                {
                    "data": "article_name"
                },
                {
                    "data": "price",
                    "type": "any-number",
                    "render": function(data, type, row) {
                        return rupiah(data);
                    },

                },
                {
                    "data": "member_name"
                },
                {
                    "data": "tot_qty",
                    "type": "any-number"
                },
                {
                    "data": "tot_berat",
                    "type": "any-number",
                    "render": function(data, type, row) {
                        data = parseFloat(data);
                        if (data > 0) {
                            return data.toLocaleString("de-DE");
                        } else {
                            return '-';
                        }
                    },
                },
                {
                    "data": "disc_pct",
                    "type": "any-number",
                    "render": function(data, type, row) {
                        data = parseInt(data);
                        if (data > 0) {
                            return data + "%";
                        } else {
                            return '-';
                        }
                    },

                },
                {
                    "data": "total_disc_amt",
                    "type": "any-number",
                    "render": function(data, type, row) {
                        data = parseInt(data);
                        if (data > 0) {
                            return rupiah(data);
                        } else {
                            return '-';
                        }
                    },

                },
                {
                    "data": "moredisc_pct",
                    "type": "any-number",
                    "render": function(data, type, row) {
                        if (parseInt(data) > 0) {
                            data = parseInt(data);
                            return data + "%";
                        } else {
                            return '-';
                        }
                    },
                },
                {
                    "data": "total_moredisc_amt",
                    "type": "any-number",
                    "render": function(data, type, row) {
                        data = parseInt(data);
                        if (data > 0) {
                            return rupiah(data);
                        } else {
                            return '-';
                        }
                    },
                },
                {
                    "data": "gross",
                    "type": "any-number",
                    "render": function(data, type, row) {
                        return  data;
                        },
                },
                {
                    "data": "net",
                    "type": "any-number",
                    "render": function(data, type, row) {
                        return  data;
                        },
                }
            ]
        });
    });
</script>