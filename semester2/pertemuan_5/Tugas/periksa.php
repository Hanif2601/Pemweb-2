<?php include 'config.php'; ?>
<h2>Form Pemeriksaan Pasien</h2>
<form method="post" action="proses_periksa.php">
    Tanggal: <input type="date" name="tanggal"><br>
    Berat: <input type="text" name="berat"><br>
    Tinggi: <input type="text" name="tinggi"><br>
    Tensi: <input type="text" name="tensi"><br>
    Keterangan: <input type="text" name="keterangan"><br>

    Pasien:
    <select name="pasien_id">
        <?php
        $result = mysqli_query($conn, "SELECT * FROM pasien");
        while ($p = mysqli_fetch_assoc($result)) {
            echo "<option value='{$p['id']}'>{$p['nama']}</option>";
        }
        ?>
    </select><br>

    Dokter:
    <select name="dokter_id">
        <?php
        $result = mysqli_query($conn, "SELECT * FROM paramedik WHERE kategori='dokter'");
        while ($d = mysqli_fetch_assoc($result)) {
            echo "<option value='{$d['id']}'>{$d['nama']}</option>";
        }
        ?>
    </select><br>

    <button type="submit">Simpan Pemeriksaan</button>
</form>
