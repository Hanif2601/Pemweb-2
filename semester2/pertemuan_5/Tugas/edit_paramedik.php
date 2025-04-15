<?php include 'config.php'; ?>
<h2>Tambah Paramedik</h2>
<form method="post">
    Nama: <input type="text" name="nama"><br>
    Gender: <select name="gender">
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
    </select><br>
    Tempat Lahir: <input type="text" name="tmp_lahir"><br>
    Tanggal Lahir: <input type="date" name="tgl_lahir"><br>
    Kategori:
    <select name="kategori">
        <option value="dokter">Dokter</option>
        <option value="perawat">Perawat</option>
        <option value="bidan">Bidan</option>
    </select><br>
    Telepon: <input type="text" name="tlpon"><br>
    Alamat: <input type="text" name="alamat"><br>
    Unit Kerja:
    <select name="unit_kerja_id">
        <?php
        $result = mysqli_query($conn, "SELECT * FROM unit_kerja");
        while ($u = mysqli_fetch_assoc($result)) {
            echo "<option value='{$u['id']}'>{$u['nama']}</option>";
        }
        ?>
    </select><br>
    <button type="submit" name="simpan">Simpan</button>
</form>

<?php
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $gender = $_POST['gender'];
    $tmp = $_POST['tmp_lahir'];
    $tgl = $_POST['tgl_lahir'];
    $kategori = $_POST['kategori'];
    $tlpon = $_POST['tlpon'];
    $alamat = $_POST['alamat'];
    $unit = $_POST['unit_kerja_id'];

    $sql = "INSERT INTO paramedik (nama, gender, tmp_lahir, tgl_lahir, kategori, tlpon, alamat, unit_kerja_id)
            VALUES ('$nama','$gender','$tmp','$tgl','$kategori','$tlpon','$alamat','$unit')";
    mysqli_query($conn, $sql);
    header("Location: index.php");
}
?>
