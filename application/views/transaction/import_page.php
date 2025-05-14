
<div class="content-wrapper">
    <div class="row">
        <div class="col-xl-12 grid-margin stretch-card">     
            <div class="card">
                <div class="card-body">
                    <div class="container">
                    <br>
                    <form method="post" action="<?= base_url('Transaction/import_page'); ?>/<?= $store; ?>" enctype="multipart/form-data">
                        <a href="<?= base_url('Transaction/upload_sales'); ?>" class="btn btn-sm btn-light">Kembali</a>
                        <a href="<?= base_url('assets/template_excel'); ?>/Template_Sales.xlsx" class="btn btn-sm btn-success">Download Format</a> 
                        <br><br>
                            <input type="file" name="file" class="form-control fileToUpload">
                            <br>
                            <button type="submit" name="preview" class="btn btn-sm btn-warning btn-preview text-white" disabled="true">Preview</button>
                    </form>
                    <hr>

    <?php
    // Jika user telah mengklik tombol Preview
    if (isset($_POST['preview'])) {
        $tgl_sekarang = date('YmdHis'); // Ini akan mengambil waktu sekarang dengan format yyyymmddHHiiss
        $nama_file_baru = 'data' . $tgl_sekarang . '.xlsx';

        // Cek apakah terdapat file data.xlsx pada folder tmp
        if (is_file('assets/excel/' . $nama_file_baru)) // Jika file tersebut ada
            unlink('assets/excel/' . $nama_file_baru); // Hapus file tersebut

        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION); // Ambil ekstensi filenya apa
        $tmp_file = $_FILES['file']['tmp_name'];

        // Cek apakah file yang diupload adalah file Excel 2007 (.xlsx)
     
        if ($ext == "xlsx") {
            // Upload file yang dipilih ke folder tmp
            // dan rename file tersebut menjadi data{tglsekarang}.xlsx
            // {tglsekarang} diganti jadi tanggal sekarang dengan format yyyymmddHHiiss
            // Contoh nama file setelah di rename : data20210814192500.xlsx
            move_uploaded_file($tmp_file, 'assets/excel/' . $nama_file_baru);

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load('assets/excel/' . $nama_file_baru); // Load file yang tadi diupload ke folder tmp
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            // Get data starting from row 2
            $data = $sheet->rangeToArray(
                'A2:' . $highestColumn . $highestRow, // range starting from row 2
                null, // null value
                true, // calculate formulas
                true, // format data
                true  // use column letters as keys
            );

            // Buat sebuah tag form untuk proses import data ke database
            // echo "<form method='post' action='" .base_url('Transaction/import_process'). "'>";

            // Disini kita buat input type hidden yg isinya adalah nama file excel yg diupload
            // ini tujuannya agar ketika import, kita memilih file yang tepat (sesuai yg diupload)
            echo "<input type='hidden' id='store' name='store' value='" . $store . "'>";
            echo "<input type='hidden' id='namafile' name='namafile' value='" . $nama_file_baru . "'>";

            // Buat sebuah div untuk alert validasi kosong

            echo '<div class="alert alert-danger alert-dismissible fade show d-none" role="alert" id="kosong">
                <strong><span id="jumlah_kosong"></span> data yang belum diisi.</strong> mohon lengkapi kembali datanya!.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';

            echo "<table border='1' cellpadding='5' class='table-striped'>
					<tr>
						<th colspan='10' class='text-center bg-info' style='color: white'>Preview Data</th>
					</tr>
					<tr>
                        <th>#</th>
                        <th>Barcode</th>
						<th>Quantity</th>
						<th>Price Item</th>
						<th>Disc Percentage (%)</th>
                        <th>More Disc Percentage (%)</th>
                        <th>Net Price / Paid Amount</th>
                        <th>Payment Type</th>
                        <th>Market Place</th>
                        <th>No Ref</th>
					</tr>";

            $numrow = 1;
            $kosong = 0;
            $i = 0;
            foreach ($data as $row) { // Lakukan perulangan dari data yang ada di excel
                // Ambil data pada excel sesuai Kolom
                $barcode        = $row['A']; 
                $quantity       = $row['B']; 
                $price_item     = $row['C']; 
                $disc_pct       = $row['D']; 
                $more_disc_pct  = $row['E']; 
                $net_price      = $row['F']; 
                $payment_type   = $row['G']; 
                $marketplace    = $row['H']; 
                $no_ref         = $row['I']; 

                // Cek jika semua data tidak diisi
                if ($barcode == "" && $quantity == "" && $payment_type == "" && $marketplace == "" && $no_ref == "")
                    continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)

                // Cek $numrow apakah lebih dari 1
                // Artinya karena baris pertama adalah nama-nama kolom
                // Jadi dilewat saja, tidak usah diimport
                if ($numrow > 1) {
                    // Validasi apakah semua data telah diisi 
                    $price_item_td = "";
                    $disc_pct_td = "";
                    $more_disc_pct_td = "";
                    $net_price_td = "";

                    if(empty($barcode)){
                        $barcode    = "Barcode tidak boleh kosong!";
                        $barcode_td = " style='background: #FF0000FF; color: white'";
                        $kosong++;
                    } else {
                        $barcode    = $barcode;
                        $barcode_td = "";
                    }

                    if(empty($quantity)){
                        $quantity    = "Quantity tidak boleh kosong!";
                        $quantity_td = " style='background: #FF0000FF; color: white'";
                        $kosong++;
                    } else {
                        $quantity    = $quantity;
                        $quantity_td = "";
                    }

                    if(empty($payment_type)){
                        $payment_type    = "Payment Type tidak boleh kosong!";
                        $payment_type_td = " style='background: #FF0000FF; color: white'";
                        $kosong++;
                    } else {
                        $payment_type    = $payment_type;
                        $payment_type_td = "";
                    }

                    if(empty($marketplace)){
                        $marketplace    = "Market Place tidak boleh kosong!";
                        $marketplace_td = " style='background: #FF0000FF; color: white'";
                        $kosong++;
                    } else {
                        $marketplace    = $marketplace;
                        $marketplace_td = "";
                    }

                    if(empty($no_ref)){
                        $no_ref    = "Market Place tidak boleh kosong!";
                        $no_ref_td = " style='background: #FF0000FF; color: white'";
                        $kosong++;
                    } else {
                        $no_ref    = $no_ref;
                        $no_ref_td = "";
                    }

                    // Jika salah satu data ada yang kosong
                    // if ($barcode == "" && $quantity == "" && $payment_type == "" && $marketplace == "" && $no_ref == "") {
                    //     $kosong++; // Tambah 1 variabel $kosong
                    // }

                    echo "<tr>";
                    echo "<td>".$i."</td>";
                    echo "<td" . $barcode_td . ">" . $barcode . "</td>";
                    echo "<td" . $quantity_td . ">" . $quantity . "</td>";
                    echo "<td" . $price_item_td . ">" . $price_item . "</td>";
                    echo "<td" . $disc_pct_td . ">" . $disc_pct . "</td>";
                    echo "<td" . $more_disc_pct_td . ">" . $more_disc_pct . "</td>";
                    echo "<td" . $net_price_td . ">" . $net_price . "</td>";
                    echo "<td" . $payment_type_td . ">" . $payment_type . "</td>";
                    echo "<td" . $marketplace_td . ">" . $marketplace . "</td>";
                    echo "<td" . $no_ref_td . ">" . $no_ref . "</td>";
                    echo "</tr>";
                }
                $i++;

                $numrow++; // Tambah 1 setiap kali looping
            }

            echo "</table>";

            // Cek apakah variabel kosong lebih dari 0
            // Jika lebih dari 0, berarti ada data yang masih kosong
            if ($kosong > 0) { ?>
                <script>
                    $(document).ready(function() {
                        // Ubah isi dari tag span dengan id jumlah_kosong dengan isi dari variabel kosong
                        $("#jumlah_kosong").html('<?php echo $kosong; ?>');

                        $("#kosong").show(); // Munculkan alert validasi kosong
                    });
                </script>
            <?php
            } else { // Jika semua data sudah diisi ?>
                <script>
                    $(document).ready(function() {
                        $("#kosong").hide(); // Munculkan alert validasi kosong
                    });
                </script>
               <?php  
                echo "<hr>";
                // Buat sebuah tombol untuk mengimport data ke database
                echo "<button type='button' name='import' id='btn-import' class='btn btn-info btn-sm'>Import</button>";
            }
            // echo "</form>";
        } else { // Jika file yang diupload bukan File Excel 2007 (.xlsx)
            // Munculkan pesan validasi
            echo "<div style='color: red;margin-bottom: 10px;'>Pastikan Upload File Excel dengan Benar!</div>";
        }
    }
        ?>
    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/JavaScript">
        $(document).ready(function() {
            var base_url = "<?= base_url(); ?>";
            $(".fileToUpload").on('change', function() {
                $(".btn-preview").prop('disabled', false);
            });

            $("#btn-import").on('click', function(){
                store = $("#store").val();
                namafile = $("#namafile").val();
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('Transaction'); ?>/import_process",
                    dataType: "json",
                    data: {
                        "store"     : store,
                        "namafile"  : namafile
                    },
                    success: function(data) {
                        // console.log(data);
                        if(data["status"] == "1"){
                            swal("Success", data["message"] , "success");
                            setTimeout(
                            function() {
                                window.location = base_url+"Transaction/upload_sales";
                            }, 2000);
                        }else{
                            swal({
                                title: "Oops !",
                                text: data["message"],
                                type: "error"
                            }, function() {
                                window.location = base_url+"Transaction/import_page/"+store;
                            });
                        }
                    },
                    beforeSend: function(){
                        swal("Loading", "Harap Tunggu..." , "warning");
                    },
                    error: function (jqXHR, exception) {
                        console.log(exception);
                        swal("Oops !", "Harap Hubungi IT" , "error");
                    },
                });
            });
        });
        
    </script>




   