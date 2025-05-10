<?php
include 'dbkoneksi.php';

session_start();

// Fungsi untuk membersihkan input (mencegah XSS)
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Proses Ubah Password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ubah_password'])) {
    $password_lama = clean_input($_POST['password_lama']);
    $password_baru = clean_input($_POST['password_baru']);
    $konfirmasi_password = clean_input($_POST['konfirmasi_password']);

    // Validasi
    if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_password)) {
        echo json_encode(['status' => 'gagal', 'message' => "Semua field wajib diisi."]); // Kirim JSON
    } elseif ($password_baru != $konfirmasi_password) {
        echo json_encode(['status' => 'gagal', 'message' => "Password baru dan konfirmasi tidak cocok."]); // Kirim JSON
    } else {
        //  Di sini, Anda harus menambahkan logika untuk memverifikasi 'password_lama'
        //  dengan password yang tersimpan di database untuk pengguna yang sedang login.
        //  Karena kita tidak memiliki otentikasi pengguna dalam kode yang diberikan,
        //  saya akan memberikan contoh placeholder.

        //  *** PLACEHOLDER - GANTI DENGAN LOGIKA YANG BENAR  ***
        $user_id = 1; // Misalnya, ID pengguna yang sedang login
        // Ambil password lama dari database berdasarkan $user_id
        $sql_cek_lama = "SELECT password FROM users WHERE id = ?"; // Sesuaikan tabel dan field
        $stmt_cek_lama = $dbh->prepare($sql_cek_lama);
        $stmt_cek_lama->execute([$user_id]);
        $row = $stmt_cek_lama->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($password_lama, $row['password'])) { // Gunakan password_verify jika password di-hash
            // Hash password baru sebelum disimpan
            $hashed_password_baru = password_hash($password_baru, PASSWORD_DEFAULT);

            // Update password di database
            $sql_update = "UPDATE users SET password = ? WHERE id = ?"; // Sesuaikan tabel dan field
            $stmt_update = $dbh->prepare($sql_update);
            $stmt_update->execute([$hashed_password_baru, $user_id]);

            echo json_encode(['status' => 'sukses', 'message' => "Password berhasil diubah."]); // Kirim JSON
        } else {
            echo json_encode(['status' => 'gagal', 'message' => "Password lama salah."]); // Kirim JSON
        }
    }
    exit(); // Penting: Hentikan eksekusi setelah AJAX
}

// ... (Kode untuk Reset Password dan Update Password lainnya, tetap sama) ...
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ... (Style dari dashboard.php) ... */

        .setting-section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .setting-label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }

        .setting-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .setting-button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .setting-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <?php include 'sidebar.php'; ?>

    <div class="content">
        <div class="topbar">
        </div>

        <div class="container mt-4">
            <h1>Pengaturan</h1>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['status'] == 'sukses' ? 'success' : 'danger' ?> alert-dismissible fade show"
                     role="alert">
                    <?= htmlspecialchars($_SESSION['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['message']); unset($_SESSION['status']); ?>
            <?php endif; ?>

            <div class="setting-section">
                <h2 class="setting-label"><i class="fas fa-lock me-2"></i>Ubah Password</h2>
                <form method="POST" action="pengaturan.php" id="ubahPasswordForm">
                    <input type="hidden" name="ubah_password" value="1">
                    <div class="mb-3">
                        <label for="password_lama" class="form-label">Password Lama</label>
                        <input type="password" class="form-control setting-input" id="password_lama"
                               name="password_lama" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_baru" class="form-label">Password Baru</label>
                        <input type="password" class="form-control setting-input" id="password_baru"
                               name="password_baru" required>
                    </div>
                    <div class="mb-3">
                        <label for="konfirmasi_password" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control setting-input" id="konfirmasi_password"
                               name="konfirmasi_password" required>
                    </div>
                    <button type="submit" class="btn setting-button" id="submitPassword">Ubah Password</button>
                    <div class="mt-3">
                        <a href="?reset=true">Lupa Password?</a>
                    </div>
                </form>
            </div>

            <?php if (isset($_GET['reset']) && $_GET['reset'] == 'true' && !isset($_GET['token'])): ?>
                <div class="setting-section">
                    <h2 class="setting-label"><i class="fas fa-question-circle me-2"></i>Reset Password</h2>
                    <p>Masukkan alamat email Anda untuk memulai proses reset password.</p>
                    <form method="POST" action="pengaturan.php">
                        <input type="hidden" name="reset_password" value="1">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control setting-input" id="email"
                                   name="email" required>
                        </div>
                        <button type="submit" class="btn setting-button">Kirim Instruksi Reset</button>
                    </form>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['reset']) && $_GET['reset'] == 'true' && isset($_GET['token'])): ?>
                <div class="setting-section">
                    <h2 class="setting-label"><i class="fas fa-key me-2"></i>Masukkan Password Baru</h2>
                    <p>Masukkan password baru Anda.</p>
                    <form method="POST" action="pengaturan.php?reset=true&token=<?= $_GET['token'] ?>">
                        <input type="hidden" name="update_password" value="1">
                        <div class="mb-3">
                            <label for="password_baru" class="form-label">Password Baru</label>
                            <input type="password" class="form-control setting-input" id="password_baru"
                                   name="password_baru" required>
                        </div>
                        <div class="mb-3">
                            <label for="konfirmasi_password" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control setting-input" id="konfirmasi_password"
                                   name="konfirmasi_password" required>
                        </div>
                        <button type="submit" class="btn setting-button">Update Password</button>
                    </form>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ubahPasswordForm = document.getElementById('ubahPasswordForm');
            if (ubahPasswordForm) {
                ubahPasswordForm.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const formData = new FormData(ubahPasswordForm);

                    fetch('pengaturan.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json()) // Ubah ke response.json()
                    .then(data => {
                        let alertClass = 'alert-danger';
                        let message = 'Terjadi kesalahan saat mengubah password.';

                        if (data.status === 'sukses') { // Cek status dari JSON
                            alertClass = 'alert-success';
                            message = data.message;
                            ubahPasswordForm.reset();
                        } else if (data.status === 'gagal') {
                            message = data.message;
                        }

                        const alertDiv = document.createElement('div');
                        alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
                        alertDiv.setAttribute('role', 'alert');
                        alertDiv.innerHTML = `
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;

                        const messageContainer = document.querySelector('.container > .alert');
                        if (messageContainer) {
                            messageContainer.remove();
                        }
                        ubahPasswordForm.parentNode.insertBefore(alertDiv, ubahPasswordForm);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const errorAlert = document.createElement('div');
                        errorAlert.className = 'alert alert-danger alert-dismissible fade show';
                        errorAlert.setAttribute('role', 'alert');
                        errorAlert.innerHTML = `
                            Terjadi kesalahan jaringan. Silakan coba lagi.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        const messageContainer = document.querySelector('.container > .alert');
                        if (messageContainer) {
                            messageContainer.remove();
                        }
                        ubahPasswordForm.parentNode.insertBefore(errorAlert, ubahPasswordForm);
                    });
                });
            }
        });
    </script>
</body>

</html>