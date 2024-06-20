<div class="content-wrapper">
    <?php // $this->load->view('modal/export-users', true); 
    ?>
    <?php $this->load->view('modal/filter-users', true); ?>
    <div class="row">
        <div class="col-lg-12 d-flex grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between mb-3">
                        <div>
                            <h4 class="card-title mb-0">List User</h4>
                            <!-- <p class="text-muted mb-2">Terapkan filter untuk menampilkan data.</p> -->
                        </div>
                        <div class="align-self-end">
                            <button type="button" class="btn btn-success btn-sm btn-icon-text btn-export-users ml-2" style="float:right">
                                <i class="typcn typcn-download btn-icon-prepend"></i>
                                Export File
                            </button>
                            <button type="button" class="btn btn-info btn-sm btn-icon-text btn-filter-users" style="float:right">
                                <i class="typcn typcn-filter"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                    <div class="">
                        <table class="table table-striped table-custom d-none" id="tb_users">
                            <thead class="table-rambla">
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <nobr>operational</nobr>
                                    </th>
                                    <th>
                                        <nobr>username</nobr>
                                    </th>
                                    <th>
                                        <nobr>password</nobr>
                                    </th>
                                    <th>
                                        <nobr>email</nobr>
                                    </th>
                                    <th>
                                        <nobr>role_id</nobr>
                                    </th>
                                    <th>
                                        <nobr>role_name</nobr>
                                    </th>
                                    <th>
                                        <nobr>last_login_date</nobr>
                                    </th>
                                    <th>
                                        <nobr>account status</nobr>
                                    </th>
                                    <th>
                                        <nobr>action</nobr>
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
    var username = '';

    $(document).ready(function() {
        get_role();
        var params1 = null;
        var params2 = null;

        var role = null;
        var aktif = null;



        load_data_users(params1, params2);

        $('.btn-filter-users').on("click", function() {
            $('#modal-filter-users').modal('show');
        });

        $('.list_aktif').on('change', function(e) {
            aktif = this.value;
        });

        $('.list_role').on('change', function(e) {
            role = this.value;
        });

        $('.btn-submit-filter').on("click", function() {
            params1 = aktif;
            params2 = role;
            if (params1 === "") {
                params1 = null;
            }
            if (params2 === "") {
                params2 = null;
            }
            // console.log(params1, params2);
            load_data_users(params1, params2);
        });

        function load_data_users_test(params1, params2) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('Configuration/users_where'); ?>",
                dataType: "JSON",
                data: {
                    "params1": params1,
                    "params2": params2
                },
                success: function(data) {
                    console.log(data);
                }
            });
        }

        function load_data_users(params1, params2) {
            $('#tb_users').removeClass('d-none');
            tabel = $('#tb_users').DataTable({
                "processing": true,
                "responsive": true,
                "serverSide": true,
                "bDestroy": true,
                "ordering": true, // Set true agar bisa di sorting
                "order": [
                    [0, 'asc']
                ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
                "ajax": {
                    "url": "<?= base_url('Configuration/users_where'); ?>", // URL file untuk proses select datanya
                    "type": "POST",
                    "data": {
                        "params1": params1,
                        "params2": params2
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
                        "data": 'username',
                        "sortable": false,
                        // "render": function ( data, type, row, meta ) {
                        //     var i = meta.row + meta.settings._iDisplayStart + 1;
                        //     return '<div class="form-check"><label class="form-check-label text-muted"><input type="checkbox" class="form-check-input" name="checkbox_'+i+'"><i class="input-helper"></i></label></div>';
                        // },
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        "data": "operational",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "username",
                        "render": function(data, type, row) {
                            username = data;
                            return '<nobr>' + username + '</nobr>';
                        },
                    },
                    {
                        "data": "password",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "email",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "role_id",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "role_name",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "last_login_date",
                        "render": function(data, type, row) {
                            return '<nobr>' + data + '</nobr>';
                        },
                    },
                    {
                        "data": "is_active",
                        "render": function(data, type, row) {
                            if (data == '1')
                                return '<nobr><label class="badge badge-info">Active</nobr></label>';
                            if (data == '0')
                                return '<nobr><label class="badge badge-danger">Non Active</nobr></label>';

                        },
                    },
                    {
                        "data": "",
                        "render": function(data, type, row, meta) {

                            return '<nobr><button data-username=' + username + ' type="button" class="btn btn-light btn-sm btn-view">view</button>&nbsp;<button data-id=' + username + ' type="button" class="btn btn-warning btn-sm text-white">Edit</button></nobr>';


                        },
                    }
                ],
            });
        }
        $('.btn-view').on('click', function() {
            var d = $(this).attr('data-username');
            alert('a');
        });


        function get_role() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('Masterdata'); ?>/get_role",
                dataType: "html",
                success: function(data) {
                    $(".loading").hide();
                    $("#export-users").show();
                    $("#filter-users").show();
                    $('.list_role').html(data);
                },
                beforeSend: function(xhr) {
                    // console.log(xhr);
                    $(".loading").show();
                    $("#filter-users").hide();
                    $("#export-users").hide();
                }
            });
        }

    });
</script>