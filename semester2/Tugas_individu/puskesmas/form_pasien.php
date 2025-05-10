<?php
require_once 'dbkoneksi.php';

// Inisialisasi variabel
$_id = $_GET['id'] ?? null;  // Ambil ID dari URL (jika ada)
$row = [];                   // Data pasien (jika ada)
$tombol = "Tambah";          // Teks tombol submit
$title = "Tambah Data Pasien"; // Judul form

// Jika ada ID, ambil data pasien (untuk edit)
if ($_id && is_numeric($_id)) {
    try {
        $sql = "SELECT * FROM pasien WHERE id = ?";
        $st = $dbh->prepare($sql);
        $st->execute([$_id]);
        $row = $st->fetch();

        if ($row) {
            $tombol = "Ubah";
            $title = "Edit Data Pasien";
        } else {
            $_id = null; // Reset ID jika data tidak ditemukan
            header('Location: data_pasien.php?status=gagal&message=Data pasien tidak ditemukan');
            exit();
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        header('Location: data_pasien.php?status=gagal&message=Gagal memuat data pasien');
        exit();
    }
}

// Ambil data kelurahan dari database
$kelurahan_list = [];
try {
    $sql_kelurahan = "SELECT id, nama_kelurahan FROM kelurahan ORDER BY nama_kelurahan";
    $st_kelurahan = $dbh->query($sql_kelurahan);
    $kelurahan_list = $st_kelurahan->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching kelurahan: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-label {
            font-weight: 500;
        }

        .required:after {
            content: " *";
            color: red;
        }

        .gender-icon {
            font-size: 1.2rem;
        }

        .form-control:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
            border-color: #4299e1;
        }

        .form-select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
            border-color: #4299e1;
        }

        .form-textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
            border-color: #4299e1;
        }

        .is-invalid {  /* Tambahkan style untuk menandai error */
            border-color: red;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-xl">
            <div class="bg-blue-600 py-4 px-6 rounded-t-lg">
                <h2 class="text-2xl font-semibold text-white"><?= htmlspecialchars($title) ?></h2>
            </div>
            <div class="py-8 px-6">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="bg-<?= $_SESSION['status'] == 'sukses' ? 'green' : 'red' ?>-100 border border-<?= $_SESSION['status'] == 'sukses' ? 'green' : 'red' ?>-400 text-<?= $_SESSION['status'] == 'sukses' ? 'green' : 'red' ?>-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold"><?= $_SESSION['status'] == 'sukses' ? 'Sukses!' : 'Error!' ?></strong>
                        <span class="block sm:inline"><?= htmlspecialchars($_SESSION['message']) ?></span>
                    </div>
                    <?php unset($_SESSION['message']); unset($_SESSION['status']); // Hapus session setelah ditampilkan ?>
                <?php endif; ?>

                <form method="POST" action="proses_pasien.php" id="formPasien" novalidate class="mt-4">
                    <?php if ($_id): ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($_id) ?>">
                    <?php endif; ?>
                    <input type="hidden" name="proses" value="<?= htmlspecialchars($tombol == 'Tambah' ? 'Tambah' : 'Ubah') ?>">

                    <div class="mb-4">
                        <label for="kode" class="block text-gray-700 text-sm font-bold mb-2 form-label required">
                            <i class="fas fa-id-card mr-2"></i>Kode Pasien
                        </label>
                        <input type="text" id="kode" name="kode" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:shadow-outline form-control" value="<?= htmlspecialchars($row['kode'] ?? '') ?>" required pattern="[A-Z0-9]{3,10}" title="Kode harus terdiri dari huruf kapital dan angka (3-10 karakter)">
                        <p class="text-red-500 text-xs italic mt-1 invalid-feedback">
                            Harap isi kode pasien (3-10 karakter, huruf kapital dan angka)
                        </p>
                    </div>

                    <div class="mb-4">
                        <label for="nama" class="block text-gray-700 text-sm font-bold mb-2 form-label required">
                            <i class="fas fa-user mr-2"></i>Nama Lengkap
                        </label>
                        <input type="text" id="nama" name="nama" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:shadow-outline form-control" value="<?= htmlspecialchars($row['nama'] ?? '') ?>" required>
                        <p class="text-red-500 text-xs italic mt-1 invalid-feedback">Harap isi nama lengkap</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="tmp_lahir" class="block text-gray-700 text-sm font-bold mb-2 form-label required">
                                <i class="fas fa-hospital mr-2"></i>Tempat Lahir
                            </label>
                            <input type="text" id="tmp_lahir" name="tmp_lahir" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:shadow-outline form-control" value="<?= htmlspecialchars($row['tmp_lahir'] ?? '') ?>" required>
                            <p class="text-red-500 text-xs italic mt-1 invalid-feedback">Harap isi tempat lahir</p>
                        </div>
                        <div>
                            <label for="tgl_lahir" class="block text-gray-700 text-sm font-bold mb-2 form-label required">
                                <i class="fas fa-calendar-alt mr-2"></i>Tanggal Lahir
                            </label>
                            <input type="date" id="tgl_lahir" name="tgl_lahir" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:shadow-outline form-control" value="<?= htmlspecialchars($row['tgl_lahir'] ?? '') ?>" required max="<?= date('Y-m-d') ?>">
                            <p class="text-red-500 text-xs italic mt-1 invalid-feedback">
                                Harap pilih tanggal lahir yang valid
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2 form-label required">
                            <i class="fas fa-venus-mars mr-2"></i>Jenis Kelamin
                        </label>
                        <div class="flex items-center">
                            <input type="radio" id="genderL" name="gender" value="L" class="mr-2 form-radio h-5 w-5 text-blue-500 focus:ring-blue-500" <?= (isset($row['gender']) && $row['gender'] == 'L') ? 'checked' : '' ?> required>
                            <label for="genderL" class="mr-4 text-gray-700">
                                <i class="fas fa-mars gender-icon text-blue-500 mr-1"></i>Laki-laki
                            </label>
                            <input type="radio" id="genderP" name="gender" value="P" class="mr-2 form-radio h-5 w-5 text-pink-500 focus:ring-pink-500" <?= (isset($row['gender']) && $row['gender'] == 'P') ? 'checked' : '' ?>>
                            <label for="genderP" class="text-gray-700">
                                <i class="fas fa-venus gender-icon text-pink-500 mr-1"></i>Perempuan
                            </label>
                        </div>
                        <p class="text-red-500 text-xs italic mt-1 invalid-feedback d-block">
                            Harap pilih jenis kelamin
                        </p>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2 form-label">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </label>
                        <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:shadow-outline form-control" value="<?= htmlspecialchars($row['email'] ?? '') ?>">
                        <p class="text-red-500 text-xs italic mt-1 invalid-feedback">Format email tidak valid</p>
                    </div>

                    <div class="mb-4">
                        <label for="alamat" class="block text-gray-700 text-sm font-bold mb-2 form-label required">
                            <i class="fas fa-map-marker-alt mr-2"></i>Alamat
                        </label>
                        <textarea id="alamat" name="alamat" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:shadow-outline form-textarea" required><?= htmlspecialchars($row['alamat'] ?? '') ?></textarea>
                        <p class="text-red-500 text-xs italic mt-1 invalid-feedback">Harap isi alamat</p>
                    </div>

                    <div class="mb-4">
                        <label for="kelurahan_id" class="block text-gray-700 text-sm font-bold mb-2 form-label">
                            <i class="fas fa-city mr-2"></i>Kelurahan
                        </label>
                        <select id="kelurahan_id" name="kelurahan_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:shadow-outline form-select">
                            <option value="">-- Pilih Kelurahan --</option>
                            <?php foreach ($kelurahan_list as $kelurahan): ?>
                                <option value="<?= htmlspecialchars($kelurahan['id']) ?>" <?= (isset($row['kelurahan_id']) && $row['kelurahan_id'] == $kelurahan['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kelurahan['nama_kelurahan']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" name="proses" value="<?= htmlspecialchars($tombol) ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:shadow-outline focus:outline-none">
                            <i class="fas fa-save mr-2"></i><?= htmlspecialchars($tombol) ?>
                        </button>
                        <a href="data_pasien.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                            <i class="fas fa-arrow-circle-left mr-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            'use strict';
            const form = document.getElementById('formPasien');
            const fields = form.querySelectorAll('.form-control');

            // Validasi setiap input berubah
            fields.forEach(field => {
                field.addEventListener('input', function () {
                    if (this.checkValidity()) {
                        this.classList.remove('is-invalid');
                    } else {
                        this.classList.add('is-invalid');
                    }
                });
            });

            form.addEventListener('submit', function (event) {
                let isValid = true;

                // Validasi semua field
                fields.forEach(field => {
                    if (!field.checkValidity()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    }
                });

                // Validasi radio button khusus
                const genderSelected = form.querySelector('input[name="gender"]:checked');
                if (!genderSelected) {
                    document.querySelector('.invalid-feedback.d-block').style.display = 'block';
                    isValid = false;
                } else {
                    document.querySelector('.invalid-feedback.d-block').style.display = 'none';
                }

                if (!isValid) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        })();
    </script>
</body>

</html>