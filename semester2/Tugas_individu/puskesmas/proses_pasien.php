<?php
require_once 'dbkoneksi.php';
session_start();

// Fungsi sanitasi input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

try {
    $_proses = $_POST['proses'] ?? '';
    $redirect = 'data_pasien.php';

    if ($_proses === "Tambah") {
        $kode = clean_input($_POST['kode'] ?? '');
        $nama = clean_input($_POST['nama'] ?? '');
        $tmp_lahir = clean_input($_POST['tmp_lahir'] ?? '');
        $tgl_lahir = clean_input($_POST['tgl_lahir'] ?? '');
        $gender = clean_input($_POST['gender'] ?? '');
        $email = clean_input($_POST['email'] ?? '');
        $alamat = clean_input($_POST['alamat'] ?? '');
        $kelurahan_id = $_POST['kelurahan_id'] ?? null;
        $kelurahan_id = !empty($kelurahan_id) ? clean_input($kelurahan_id) : null;

        // Validasi wajib isi
        if (empty($kode) || empty($nama) || empty($tmp_lahir) || empty($tgl_lahir) || empty($gender) || empty($alamat)) {
            $_SESSION['message'] = 'Semua field yang bertanda bintang (*) wajib diisi.';
            $_SESSION['status'] = 'gagal';
            header('Location: form_pasien.php');
            exit();
        }

        // Validasi kode pasien
        if (!preg_match('/^[A-Z0-9]{3,10}$/', $kode)) {
            $_SESSION['message'] = 'Kode pasien tidak valid (3-10 karakter, huruf kapital dan angka).';
            $_SESSION['status'] = 'gagal';
            header('Location: form_pasien.php');
            exit();
        }

        // Validasi email
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['message'] = 'Format email tidak valid.';
            $_SESSION['status'] = 'gagal';
            header('Location: form_pasien.php');
            exit();
        }

        // Validasi tanggal lahir
        $tanggal_sekarang = date('Y-m-d');
        if ($tgl_lahir > $tanggal_sekarang) {
            $_SESSION['message'] = 'Tanggal lahir tidak boleh melebihi tanggal sekarang.';
            $_SESSION['status'] = 'gagal';
            header('Location: form_pasien.php');
            exit();
        }

        // Insert data
        $sql = "INSERT INTO pasien (kode, nama, tmp_lahir, tgl_lahir, gender, email, alamat, kelurahan_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $kode);
        $stmt->bindValue(2, $nama);
        $stmt->bindValue(3, $tmp_lahir);
        $stmt->bindValue(4, $tgl_lahir);
        $stmt->bindValue(5, $gender);
        $stmt->bindValue(6, $email);
        $stmt->bindValue(7, $alamat);
        $stmt->bindValue(8, $kelurahan_id, $kelurahan_id !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Data pasien berhasil ditambahkan.';
            $_SESSION['status'] = 'sukses';
        } else {
            $_SESSION['message'] = 'Gagal menambahkan data pasien.';
            $_SESSION['status'] = 'gagal';
        }
        header('Location: data_pasien.php');
        exit();

    } elseif ($_proses === "Ubah") {
        $id = intval($_POST['id'] ?? 0);
        $kode = clean_input($_POST['kode'] ?? '');
        $nama = clean_input($_POST['nama'] ?? '');
        $tmp_lahir = clean_input($_POST['tmp_lahir'] ?? '');
        $tgl_lahir = clean_input($_POST['tgl_lahir'] ?? '');
        $gender = clean_input($_POST['gender'] ?? '');
        $email = clean_input($_POST['email'] ?? '');
        $alamat = clean_input($_POST['alamat'] ?? '');
        $kelurahan_id = $_POST['kelurahan_id'] ?? null;
        $kelurahan_id = !empty($kelurahan_id) ? clean_input($kelurahan_id) : null;

        // Validasi wajib isi
        if (empty($kode) || empty($nama) || empty($tmp_lahir) || empty($tgl_lahir) || empty($gender) || empty($alamat)) {
            $_SESSION['message'] = 'Semua field wajib diisi.';
            $_SESSION['status'] = 'gagal';
            header("Location: form_pasien.php?id=$id");
            exit();
        }

        // Validasi kode
        if (!preg_match('/^[A-Z0-9]{3,10}$/', $kode)) {
            $_SESSION['message'] = 'Kode pasien tidak valid.';
            $_SESSION['status'] = 'gagal';
            header("Location: form_pasien.php?id=$id");
            exit();
        }

        // Validasi email
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['message'] = 'Format email tidak valid.';
            $_SESSION['status'] = 'gagal';
            header("Location: form_pasien.php?id=$id");
            exit();
        }

        $sql = "UPDATE pasien SET kode = ?, nama = ?, tmp_lahir = ?, tgl_lahir = ?, gender = ?, email = ?, alamat = ?, kelurahan_id = ?
                WHERE id = ?";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $kode);
        $stmt->bindValue(2, $nama);
        $stmt->bindValue(3, $tmp_lahir);
        $stmt->bindValue(4, $tgl_lahir);
        $stmt->bindValue(5, $gender);
        $stmt->bindValue(6, $email);
        $stmt->bindValue(7, $alamat);
        $stmt->bindValue(8, $kelurahan_id, $kelurahan_id !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(9, $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Data pasien berhasil diubah.';
            $_SESSION['status'] = 'sukses';
        } else {
            $_SESSION['message'] = 'Gagal mengubah data pasien.';
            $_SESSION['status'] = 'gagal';
        }
        header('Location: data_pasien.php');
        exit();

    } elseif (isset($_GET['delete'])) {
        $id = intval($_GET['delete']);

        // Cek apakah data ada
        $checkStmt = $dbh->prepare("SELECT * FROM pasien WHERE id = ?");
        $checkStmt->execute([$id]);
        $pasien = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($pasien) {
            $deleteStmt = $dbh->prepare("DELETE FROM pasien WHERE id = ?");
            if ($deleteStmt->execute([$id])) {
                $_SESSION['message'] = 'Data pasien berhasil dihapus.';
                $_SESSION['status'] = 'sukses';
            } else {
                $_SESSION['message'] = 'Gagal menghapus data pasien.';
                $_SESSION['status'] = 'gagal';
            }
        } else {
            $_SESSION['message'] = 'Pasien tidak ditemukan.';
            $_SESSION['status'] = 'gagal';
        }
        header('Location: data_pasien.php');
        exit();
    }

} catch (PDOException $e) {
    $_SESSION['message'] = 'Terjadi kesalahan database: ' . $e->getMessage();
    $_SESSION['status'] = 'gagal';
    error_log('Database Error: ' . $e->getMessage());
    header('Location: data_pasien.php');
    exit();
} finally {
    if (isset($stmt)) $stmt = null;
    if (isset($dbh)) $dbh = null;
}
?>
