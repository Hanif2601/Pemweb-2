<?php include 'config.php'; ?>
<h2>Daftar Paramedik</h2>
<a href="tambah_paramedik.php">Tambah Paramedik</a>
<table border="1" cellpadding="8">
    <tr>
        <th>Nama</th>
        <th>Gender</th>
        <th>Kategori</th>
        <th>Unit Kerja</th>
        <th>Aksi</th>
    </tr>
    <?php
    $sql = "SELECT p.*, u.nama AS unit FROM paramedik p
            LEFT JOIN unit_kerja u ON p.unit_kerja_id = u.id";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>{$row['nama']}</td>
            <td>{$row['gender']}</td>
            <td>{$row['kategori']}</td>
            <td>{$row['unit']}</td>
            <td>
                <a href='edit_paramedik.php?id={$row['id']}'>Edit</a> |
                <a href='hapus_paramedik.php?id={$row['id']}'>Hapus</a>
            </td>
        </tr>";
    }
    ?>
</table>
