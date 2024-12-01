<?php
require 'function.php';
require 'cek.php';

// Proses Barang Keluar
if (isset($_POST['barangkeluar'])) {
    $idbarang = mysqli_real_escape_string($conn, $_POST['barangnya']);
    $qty = mysqli_real_escape_string($conn, $_POST['qty']);
    $penerima = mysqli_real_escape_string($conn, $_POST['penerima']);
    $hargajual = mysqli_real_escape_string($conn, $_POST['hargajual']);
    $totalharga = $qty * $hargajual;

    // Cek stock barang
    $checkStock = mysqli_query($conn, "SELECT stock FROM stock WHERE idbarang = '$idbarang'");
    $stockData = mysqli_fetch_array($checkStock);
    $currentStock = $stockData['stock'];

    if ($qty > $currentStock) {
        echo "<script>alert('Stock tidak mencukupi!');</script>";
    } else {
        // Kurangi stock dan masukkan data ke tabel keluar
        $updateStockQuery = "UPDATE stock SET stock = stock - $qty WHERE idbarang = '$idbarang'";
        mysqli_query($conn, $updateStockQuery);

        $query = "INSERT INTO keluar (idbarang, tanggal, penerima, qty, hargajual, totalharga) 
                  VALUES ('$idbarang', NOW(), '$penerima', '$qty', '$hargajual', '$totalharga')";
        mysqli_query($conn, $query);
    }
}

// Proses Update Barang Keluar
if (isset($_POST['updatebarangkeluar'])) {
    $idk = mysqli_real_escape_string($conn, $_POST['idk']);
    $idbarang = mysqli_real_escape_string($conn, $_POST['idb']);
    $penerima = mysqli_real_escape_string($conn, $_POST['penerima']);
    $qty = mysqli_real_escape_string($conn, $_POST['qty']);
    $hargajual = mysqli_real_escape_string($conn, $_POST['hargajual']);
    $totalharga = $qty * $hargajual;

    // Update data keluar
    $updateQuery = "UPDATE keluar SET penerima='$penerima', qty='$qty', hargajual='$hargajual', totalharga='$totalharga' WHERE idkeluar='$idk'";
    mysqli_query($conn, $updateQuery);
}

// Proses Hapus Barang Keluar
if (isset($_POST['hapusbarangkeluar'])) {
    $idk = mysqli_real_escape_string($conn, $_POST['idk']);
    $idbarang = mysqli_real_escape_string($conn, $_POST['idb']);
    $qty = mysqli_real_escape_string($conn, $_POST['qty']);

    // Kembalikan stock barang
    $restoreStockQuery = "UPDATE stock SET stock = stock + $qty WHERE idbarang = '$idbarang'";
    mysqli_query($conn, $restoreStockQuery);

    // Hapus data keluar
    $deleteQuery = "DELETE FROM keluar WHERE idkeluar='$idk'";
    mysqli_query($conn, $deleteQuery);
}
?>

<script>
    document.addEventListener('input', function () {
        const qty = document.getElementById('qty').value;
        const hargajual = document.getElementById('hargajual').value;
        const totalharga = document.getElementById('totalharga');

        if (qty && hargajual) {
            totalharga.value = qty * hargajual;
        } else {
            totalharga.value = '';
        }
    });
</script>

<script>
    document.addEventListener('input', function () {
        <?php
        $ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM keluar");
        while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
            $idk = $data['idkeluar'];
        ?>
        const qty<?= $idk; ?> = document.getElementById('qty<?= $idk; ?>').value;
        const hargajual<?= $idk; ?> = document.getElementById('hargajual<?= $idk; ?>').value;
        const totalharga<?= $idk; ?> = document.getElementById('totalharga<?= $idk; ?>');

        if (qty<?= $idk; ?> && hargajual<?= $idk; ?>) {
            totalharga<?= $idk; ?>.value = qty<?= $idk; ?> * hargajual<?= $idk; ?>;
        } else {
            totalharga<?= $idk; ?>.value = '';
        }
        <?php
        }
        ?>
    });
</script>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Barang Keluar</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">SMKN 2 SUMEDANG</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="master.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Master Barang
                            </a>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Stock Barang
                            </a>
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="keluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-box-open"></i></div>
                                Barang Keluar
                            </a>

                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                                Logout
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Barang Keluar</h1>
                        <div class="card mb-4">
                            <div class="card-header">
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Barang Keluar
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Id Barang</th>
                                                <th>Tanggal</th>
                                                <th>Penerima</th>
                                                <th>Jumlah Keluar</th>
                                                <th>Harga Jual</th>
                                                <th>Total Harga</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 1;  // Inisialisasi variabel $i untuk nomor urut
                                        $ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM keluar k, stock s WHERE s.idbarang = k.idbarang");
                                        while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
                                            $idb = $data['idbarang'];
                                            $idk = $data['idkeluar'];
                                            $tanggal = $data['tanggal'];
                                            $penerima = $data['penerima'];
                                            $qty = $data['qty'];
                                            $hargajual = $data['hargajual'];
                                            $totalharga = $data['5'];
                                        ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $idb; ?></td>
                                            <td><?= $tanggal; ?></td>
                                            <td><?= $penerima; ?></td>
                                            <td><?= $qty; ?></td>
                                            <td><?= $hargajual; ?></td>
                                            <td><?= $totalharga; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?=$idk;?>">Edit</button>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?=$idk;?>">Delete</button>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="edit<?= $idk; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang Keluar</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <form method="post" action="">
                                                    <div class="modal-body">
                                                        <label for="penerima">Penerima</label>
                                                        <input type="text" name="penerima" value="<?= $penerima; ?>" class="form-control" required>
                                                        <br>
                                                        <label for="qty">Quantity</label>
                                                        <input type="number" id="qty<?=$idk;?>" name="qty" value="<?= $qty; ?>" class="form-control" required>
                                                        <br>
                                                        <label for="hargajual">Harga Jual</label>
                                                        <input type="number" id="hargajual<?=$idk;?>" name="hargajual" value="<?= $hargajual; ?>" class="form-control" required>
                                                        <br>
                                                        <label for="totalharga">Total Harga</label>
                                                        <input type="number" id="totalharga<?=$idk;?>" name="totalharga" value="<?= $totalharga; ?>" class="form-control" readonly>
                                                        <br>
                                                        <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                        <input type="hidden" name="idk" value="<?= $idk; ?>">
                                                        <button type="submit" class="btn btn-primary mt-2" name="updatebarangkeluar">Submit</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="delete<?=$idk;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Barang Keluar?</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus barang keluar ini?
                                                            <input type="hidden" name="idb" value="<?=$idb;?>">
                                                            <input type="hidden" name="idk" value="<?=$idk;?>">
                                                            <br><br>
                                                            <button type="submit" class="btn btn-danger" name="hapusbarangkeluar">Hapus</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <!-- Modal for Tambah Barang Keluar -->
        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Barang Keluar</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form method="post" action="keluar.php">
                    <div class="modal-body">
                        <select name="barangnya" class="form-control">
                            <?php
                            $ambilsemuadatanya = mysqli_query($conn, "SELECT * FROM stock");
                            while ($fetcharray = mysqli_fetch_array($ambilsemuadatanya)) {
                                $namabarangnya = $fetcharray['namabarang'];
                                $idbarangnya = $fetcharray['idbarang'];
                            ?>
                            <option value="<?=$idbarangnya;?>"><?=$namabarangnya;?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <br>
                        <input type="number" name="qty" id="qty" class="form-control" placeholder="Quantity" required>
                        <br>
                        <input type="text" name="penerima" class="form-control" placeholder="Penerima" required>
                        <br>
                        <input type="number" name="hargajual" id="hargajual" class="form-control" placeholder="Harga Jual" required>
                        <br>
                        <input type="number" name="totalharga" id="totalharga" class="form-control" placeholder="Total Harga" readonly>
                        <br>
                        <button type="submit" class="btn btn-primary mt-2" name="barangkeluar">Submit</button>
                    </div>
                </form>

                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
</html>
