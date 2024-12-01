<?php
require 'function.php';
require 'cek.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Barang Masuk</title>
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
                        <h1 class="mt-4">Barang Masuk</h1>
                        <div class="card mb-4">
                            <div class="card-header">
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Barang Masuk
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Nama Barang</th>
                                                <th>Penerima</th>
                                                <th>Jumlah Masuk</th>
                                                <th>Harga Satuan</th>
                                                <th>Harga Jual</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $ambilsemuadatastock = mysqli_query($conn, "SELECT m.*, s.namabarang FROM masuk m JOIN stock s ON s.idbarang = m.idbarang");
                                        while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
                                            $idb = $data['idbarang'];
                                            $idm = $data['idmasuk'];
                                            $tanggal = $data['tanggal'];
                                            $namabarang = $data['namabarang'];
                                            $keterangan = $data['keterangan'];
                                            $qty = $data['qty'];            
                                            $hargasatuan = $data['hargasatuan']; 
                                            $hargajual = $data['hargajual'];
                                        ?>
                                        <tr>
                                            <td><?= $tanggal; ?></td>
                                            <td><?= $namabarang; ?></td>
                                            <td><?= $keterangan; ?></td>
                                            <td><?= $qty; ?></td> 
                                            <td><?= $hargasatuan; ?></td> 
                                            <td><?= $hargajual; ?></td>
                            
                                            <td>
                                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?=$idm;?>">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?=$idm;?>">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="edit<?= $idm; ?>">
                                            <div class="modal-dialog modal-lg"> <!-- Tambahkan kelas modal-lg di sini -->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <form method="post" action="masuk.php">
                                                        <div class="modal-body">
                                                            <label for="namabarang">Nama Barang</label>
                                                            <input type="text" name="namabarang" value="<?= $namabarang; ?>" class="form-control" required>
                                                            <br>
                                                            <label for="keterangan">Keterangan</label>
                                                            <input type="text" name="keterangan" value="<?= $keterangan; ?>" class="form-control" required>
                                                            <br>
                                                            <label for="qty">Quantity</label>
                                                            <input type="number" name="qty" value="<?= $qty; ?>" class="form-control" required>
                                                            <br>
                                                            <label for="hargasatuan">Harga Satuan</label>
                                                            <input type="number" name="hargasatuan" value="<?= $hargasatuan; ?>" class="form-control" required>
                                                            <br>
                                                            <label for="hargajual">Harga Jual</label>
                                                            <input type="number" name="hargajual" value="<?= $hargajual; ?>" class="form-control" required>
                                                            <br>
                                                            <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                            <input type="hidden" name="idm" value="<?= $idm; ?>">
                                                            <button type="submit" class="btn btn-primary mt-2" name="updatebarangmasuk">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>



                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="delete<?=$idm;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus barang?</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            Apakah anda yakin ingin menghapus <?=$namabarang;?>?
                                                            <input type="hidden" name="idb" value="<?=$idb;?>">
                                                            <input type="hidden" name="kty" value="<?=$qty;?>">
                                                            <input type="hidden" name="idm" value="<?=$idm;?>">
                                                            <br><br>
                                                            <button type="submit" class="btn btn-danger" name="hapusbarangmasuk">Hapus</button>
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
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- Modal for Tambah Barang Masuk -->
        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Barang Masuk</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form method="post" action="masuk.php">
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
                            <input type="number" name="qty" class="form-control" placeholder="Quantity" required>
                            <br>
                            <input type="text" name="penerima" class="form-control" placeholder="Penerima" required>
                            <br>
                            <input type="text" name="hargasatuan" class="form-control" placeholder="Harga Satuan" required>
                            <br>
                            <input type="number" name="hargajual" class="form-control" placeholder="Harga Jual" required>
                            <br>
                            <button type="submit" class="btn btn-primary mt-2" name="barangmasuk">Submit</button>
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
