<?php
include 'dbkoneksi.php';

// Fungsi untuk menghasilkan laporan (contoh sederhana)
function generateLaporanPasien($dbh) {
    $sql = "SELECT p.*, k.nama_kelurahan 
            FROM pasien p
            LEFT JOIN kelurahan k ON p.kelurahan_id = k.id
            ORDER BY p.tgl_lahir"; // Contoh order by tanggal lahir
    $stmt = $dbh->query($sql);
    $data_pasien = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $data_pasien;
}

$laporan_pasien = generateLaporanPasien($dbh);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            color: black;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
            margin: 0 4px;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }

        /* Style Tambahan */
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            transition: width 0.3s ease;
        }

        .sidebar-brand {
            padding: 1rem;
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu-item {
            padding: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .sidebar-menu-item:hover {
            background-color: #495057;
        }

        .sidebar-menu-item a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .topbar {
            background-color: #f8f9fa;
            padding: 1rem;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #sidebarToggle {
            cursor: pointer;
        }

        .search-wrapper {
            display: flex;
            align-items: center;
        }

        .search-input {
            padding: 0.5rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            margin-right: 0.5rem;
        }

        .notification-icon {
            position: relative;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            padding: 5px 10px;
            border-radius: 50%;
            background-color: red;
            color: white;
            font-size: 0.7rem;
        }

        .profile-dropdown {
            position: absolute;
            top: 60px;
            right: 0;
            background-color: white;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 0.5rem;
            display: none;
        }

        .profile-link {
            display: block;
            padding: 0.5rem;
            color: #333;
            text-decoration: none;
        }

        .profile-link:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>

    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="content">
        <div class="topbar">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="fas fa-bars fa-lg me-3" id="sidebarToggle"></i>
                </div>
                <div class="col">
                    <div class="search-wrapper">
                        <input type="text" class="search-input" placeholder="Cari...">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="notification-icon">
                        <i class="fas fa-bell fa-lg"></i>
                        <span class="notification-badge">3</span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="profile-icon" onclick="toggleProfileDropdown()">
                        <i class="fas fa-user-circle fa-lg"></i>
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="#" class="profile-link">Profil</a>
                            <a href="#" class="profile-link">Keluar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <h1>Laporan Data Pasien Puskesmas</h1>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pasien</th>
                            <th>Nama Pasien</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Kelurahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($laporan_pasien) > 0): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($laporan_pasien as $pasien): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($pasien['kode']) ?></td>
                                    <td><?= htmlspecialchars($pasien['nama']) ?></td>
                                    <td><?= htmlspecialchars($pasien['tmp_lahir']) ?></td>
                                    <td><?= htmlspecialchars($pasien['tgl_lahir']) ?></td>
                                    <td><?= htmlspecialchars($pasien['gender'] == 'L' ? 'Laki-laki' : 'Perempuan') ?></td>
                                    <td><?= htmlspecialchars($pasien['email'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($pasien['alamat']) ?></td>
                                    <td><?= htmlspecialchars($pasien['nama_kelurahan'] ?? 'Tidak Diketahui') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">Tidak ada data pasien.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Sidebar Toggle
        const sidebar = document.querySelector('.sidebar');
        const content = document.querySelector('.content');
        const sidebarToggle = document.querySelector('#sidebarToggle');

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('collapsed');
        });

        // Profile Dropdown
        function toggleProfileDropdown() {
            var dropdown = document.getElementById("profileDropdown");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.profile-icon')) {
                var dropdowns = document.getElementsByClassName("profile-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>