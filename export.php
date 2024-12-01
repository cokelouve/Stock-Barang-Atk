<?php
// Import koneksi ke database
include 'function.php'; // Pastikan Anda sudah membuat file koneksi.php untuk koneksi ke DB
?>
<html>
<head>
  <title>Stock Barang ATK</title>
  <!-- Memastikan semua link dan script di-load dengan benar -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
<div class="container">
  <h2>Stock Bahan ATK</h2>
  <h4>(Inventory)</h4>
  <div class="data-tables datatable-dark">
    <!-- Menambahkan class table-striped untuk styling tabel -->
    <table class="table table-bordered table-striped" id="mauexport" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th>No</th>
          <th>Id Barang</th>
          <th>Nama Barang</th>
          <th>Stock</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Ambil data stock dari database
        $ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM v_stock_barang");
        $i = 1;
        while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
          $idbarang = $data['id_barang'];
          $namabarang = $data['namabarang'];
          $stock = $data['stok'];

          // Tampilkan data ke tabel
        ?>
        <tr>
          <td><?=$i++;?></td>
          <td><?php echo $idbarang;?></td>
          <td><?php echo $namabarang;?></td>
          <td><?php echo $stock;?></td>
        </tr>

        

        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<script>
$(document).ready(function() {
    // Inisialisasi DataTable, pastikan elemen sudah ada saat DataTable diinisialisasi
    $('#mauexport').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
});
</script>

</body>
</html>
