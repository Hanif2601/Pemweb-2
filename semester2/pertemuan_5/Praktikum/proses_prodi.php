<?php
require_once 'dbkoneksi.php';

// 1) tangkap data dari form
$_kode = $_POST['kode'];
$_nama = $_POST['nama'];
$_kaprodi = $_POST['kaprodi'];
$_proses = $_POST['proses'];

// buat array
$ar_data = [$_kode,$_nama,$_kaprodi];

// buat query
if($_proses == "Simpan"){
    $sql = "INSERT INTO prodi(kode,nama,kaprodi) VALUES(?,?,?)";
}elseif($_proses == "Update"){
    $id_edit =$_POST['id_edit'];// 4
    $ar_data[] = $id_edit;
    $sql = "UPDATE prodi SET nama=?,kaprodi=?,kode=? WHERE id=?";
}elseif($_proses == "Hapus"){
    $id_hapus =$_POST['id_hapus'];
    $ar_data = [$id_hapus];
    $sql = "DELETE FROM prodi WHERE id=?";
}

// 5) buat statement 
$stmt = $dbh->prepare ($sql);
// 6) jalankan query 
$stmt->execute ($ar_data);
// redirect ke halaman list_prodi.php
header('Location: list_prodi.php');


?>