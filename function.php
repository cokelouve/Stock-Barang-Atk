<?php

// Koneksi ke database
$conn = mysqli_connect('localhost', 'root', '', 'stockbarang');
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}


// Menambah barang baru
if (isset($_POST['addnewbarang'])) {
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock) VALUES('$namabarang','$deskripsi','$stock')");
    if ($addtotable) {
        header('location:index.php');
    } else {
        echo 'Gagal menambah barang: ' . mysqli_error($conn);
    }
};

// Menambah barang masuk
if (isset($_POST['barangmasuk'])) {
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];
    $hargajual = $_POST['hargajual']; // Harga jual dari input pengguna

    // Cek stok saat ini
    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    if ($ambildatanya) {
        // Barang sudah ada, update stok
        $stocksekarang = $ambildatanya['stock'];
        $tambahkestocksekarangdenganquantity = $stocksekarang + $qty;
        $updatestockmaster = mysqli_query($conn, "UPDATE stock SET stock='$tambahkestocksekarangdenganquantity' WHERE idbarang='$barangnya'");
    } else {
        // Barang belum ada, tambahkan ke stock
        $updatestockmaster = mysqli_query($conn, "INSERT INTO stock (idbarang, namabarang, deskripsi, stock, hargasatuan, hargajual) 
                                                  VALUES('$barangnya', '$namabarang', '$deskripsi', '$qty', '$hargasatuan', '$hargajual')");
    }
    
}



 
// Menambah master barang
if (isset($_POST['masterbarang'])) {
    $barangnya = $_POST['barangnya'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $hargasatuan = $_POST['hargasatuan']; //Harga satuan dari input pengguna
    $hargajual = $_POST['hargajual']; // Harga jual dari input pengguna
    $qty = $_POST['qty'];

    // Cek apakah barang sudah ada di stock
    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    if ($ambildatanya) {
        // Barang sudah ada, update stok
        $stocksekarang = $ambildatanya['stock'];
        $tambahkestocksekarangdenganquantity = $stocksekarang + $qty;
        $updatestockmaster = mysqli_query($conn, "UPDATE stock SET stock='$tambahkestocksekarangdenganquantity' WHERE idbarang='$barangnya'");
    } else {
        // Barang belum ada, tambahkan ke stock
        $updatestockmaster = mysqli_query($conn, "INSERT INTO stock (idbarang, namabarang, deskripsi, stock) VALUES('$barangnya', '$namabarang', '$deskripsi', '$qty')");
    }

    // Tambahkan barang ke tabel master
    $addtomaster = mysqli_query($conn, "INSERT INTO master (idbarang, namabarang, deskripsi, hargasatuan, hargajual, qty) VALUES('$barangnya', '$namabarang', '$deskripsi', '$hargasatuan', '$hargajual', '$qty')");

    if ($addtomaster && $updatestockmaster) {
        header('location:master.php');
    } else {
        echo 'Gagal menambah master barang: ' . mysqli_error($conn);
    }
}


// Fungsi menambah barang keluar
if (isset($_POST['addbarangkeluar'])) {
    $idbarang = $_POST['idbarang'];
    $jumlah = $_POST['jumlah'];
    $totalharga = $_POST['totalharga'];
    $tanggalkeluar = date("Y-m-d"); // Atau sesuaikan dengan field di database

    // Proses insert ke tabel barang keluar
    $query = mysqli_query($conn, "INSERT INTO barang_keluar (idbarang, jumlah, totalharga, tanggalkeluar) 
                                  VALUES ('$idbarang', '$jumlah', '$totalharga', '$tanggalkeluar')");

    if ($query) {
        // Update stock di tabel barang untuk mengurangi jumlah stock
        $updateStock = mysqli_query($conn, "UPDATE stock SET stock = stock - '$jumlah' WHERE idbarang = '$idbarang'");

        if ($updateStock) {
            echo "Data berhasil disimpan!";
        } else {
            echo "Gagal update stock!";
        }
    } else {
        echo "Gagal menambah barang keluar!";
    }
}




// Update info barang
if (isset($_POST['updatebarangmasuk'])) {
    $idm = $_POST['idm'];
    $idb = $_POST['idb'];
    $keterangan = $_POST['keterangan'];
    $qty = $_POST['qty'];
    $hargasatuan = $_POST['hargasatuan'];
    $hargajual = $_POST['hargajual'];

    // Pastikan data diterima dengan benar
    var_dump($idm, $idb, $keterangan, $qty, $hargasatuan, $hargajual); // Debug

    // Update data barang masuk
    $update = mysqli_query($conn, "UPDATE masuk SET keterangan = '$keterangan', qty = '$qty', hargasatuan = '$hargasatuan', hargajual = '$hargajual' WHERE idmasuk = '$idm'");

    if ($update) {
        echo "<script>alert('Barang Masuk berhasil diupdate');</script>";
        echo "<script>location='masuk.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, gagal update');</script>";
    }
}


// Update Barang
if (isset($_POST['updatebarang'])) {
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $hargasatuan = $_POST['hargasatuan'];
    $hargajual = $_POST['hargajual'];

    $updateQuery = "UPDATE master SET namabarang='$namabarang', deskripsi='$deskripsi', hargasatuan='$hargasatuan', hargajual='$hargajual' WHERE idb='$idbarang'";
    mysqli_query($conn, $updateQuery);
}




// Hapus Barang
if (isset($_POST['hapusbarang'])) {
    // Menghindari injeksi SQL dengan menggunakan parameter yang bersih
    $idbarang = mysqli_real_escape_string($conn, $_POST['idb']);
    
    // Menulis query hapus
    $deleteQuery = "DELETE FROM master WHERE id='$idbarang'";
    
    if ($update && $hapusdata) {
        header('location:master.php');
    } else {
        echo 'Gagal menghapus master barang: ' . mysqli_error($conn);
    }
}




// Mengubah data barang masuk
if (isset($_POST['updatebarangmasuk'])) {
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $keterangan = $_POST['keterangan'];
    $qty = $_POST['qty'];
    $hargajual = $_POST['hargajual'];


    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];


    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stockskrg + $selisih;
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stockskrg - $selisih;
    }



    $updatestock = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idb'");
    $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$keterangan', hargajual = '$hargajual' WHERE idmasuk='$idm'");


    if ($updatestock && $updatenya) {
        header('location:masuk.php');
    } else {
        echo 'Gagal mengupdate barang masuk: ' . mysqli_error($conn);
    }
}



//mengubah data mastter barang
if(isset($_POST['updatemasterbarang'])) {
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $hargasatuan = $_POST['hargasatuan'];
    $hargajual = $_POST['hargajual'];
   
    $qty = $_POST['qty'];


    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "SELECT * FROM master WHERE idmaster='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];


    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stockskrg + $selisih;
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stockskrg - $selisih;
    }



    $updatestock = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idb'");
    $updatenya = mysqli_query($conn, "UPDATE master SET qty='$qty', idbarang='$idb', namabarang = '$namabarang', deskripsi = '$deskripsi', hargasatuan =hargajual = '$hargajual' WHERE idmasuk='$idm'");


    if ($updatestock && $updatenya) {
        header('location:masuk.php');
    } else {
        echo 'Gagal mengupdate barang masuk: ' . mysqli_error($conn);

    }
}

// Mengedit master barang
if (isset($_POST['updatebarang'])) {
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
    die();
}

if (isset($_POST['updatebarang'])) {
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $hargasatuan = $_POST['hargasatuan'];
    $hargajual = $_POST['hargajual'];

    $query = mysqli_query($conn, "UPDATE stock SET 
        namabarang='$namabarang', 
        deskripsi='$deskripsi', 
        hargasatuan='$hargasatuan', 
        hargajual='$hargajual' 
        WHERE idbarang='$idb'");

    if ($query) {
        header('location:master.php');
    } else {
        echo 'Gagal mengedit barang: ' . mysqli_error($conn);
    }
}


//Mengubah data barang keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];
    
    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty > $qtyskrg){
        $selisih = $qty - $qtyskrg;
        $kurangin = $stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
        if($kurangistocknya && $updatenya){
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima', hargajual='$hargajual', totalharga='$totalharga' where idkeluar='$idk'");
        if($kurangistocknya && $updatenya){
            header('location:keluar.php');
        } else {
            echo 'Gagal';
            header('location:keluar.php');
        }
    }
}



// Menghapus barang masuk
if (isset($_POST['hapusbarangmasuk'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock - $qty;
    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk='$idm'");

    if ($update && $hapusdata) {
        header('location:masuk.php');
    } else {
        echo 'Gagal menghapus barang masuk: ' . mysqli_error($conn);
    }
}


//Menghapus barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok + $qty;

    $update = mysqli_query($conn, "update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "delete from keluar where idkeluar='$idk'");

    if($update && $hapusdata){
        header('location:keluar.php');
    } else {
        header('location:keluar.php');
    }
}

// Menghapus barang dari stock
if (isset($_POST['hapusbarang'])) {
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "DELETE FROM stock WHERE idbarang='$idb'");
    if ($hapus) {
        header('location:index.php');
    } else {
        echo 'Gagal menghapus barang: ' . mysqli_error($conn);
        echo 'Query: DELETE FROM stock WHERE idbarang=' . $idb;
    }
}


?>

