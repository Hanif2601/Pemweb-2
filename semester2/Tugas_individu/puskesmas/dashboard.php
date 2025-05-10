<?php
include 'dbkoneksi.php';

// Ambil jumlah total pasien
$total_pasien = $dbh->query("SELECT COUNT(*) FROM pasien")->fetchColumn();

// Ambil distribusi gender
$distribusi_gender = $dbh->query("SELECT gender, COUNT(*) as jumlah FROM pasien GROUP BY gender")->fetchAll();

// Ambil data pasien terbaru (misalnya, 5 data terbaru)
$pasien_terbaru = $dbh->query("SELECT pasien.*, kelurahan.nama_kelurahan 
                              FROM pasien 
                              LEFT JOIN kelurahan ON pasien.kelurahan_id = kelurahan.id 
                              ORDER BY id DESC LIMIT 5")->fetchAll();

// Ambil data jumlah pasien per kelurahan
$distribusi_kelurahan = $dbh->query("SELECT kelurahan.nama_kelurahan, COUNT(pasien.id) as jumlah 
                                    FROM pasien 
                                    LEFT JOIN kelurahan ON pasien.kelurahan_id = kelurahan.id 
                                    GROUP BY kelurahan.nama_kelurahan")->fetchAll();

// Ambil data pertumbuhan pasien (contoh per bulan)
$pertumbuhan_pasien = $dbh->query("SELECT DATE_FORMAT(tgl_lahir, '%Y-%m') as bulan, COUNT(*) as jumlah 
                                  FROM pasien 
                                  GROUP BY bulan 
                                  ORDER BY bulan")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Puskesmas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* ... (Style yang sudah ada) ... */

        /* Tambahan Style */
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

<div class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-hospital me-2"></i>Puskesmas
    </div>
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item active">
            <a href="dashboard.php"><i class="fas fa-home me-1"></i>Dashboard</a>
        </li>
        <li class="sidebar-menu-item">
            <a href="data_pasien.php"><i class="fas fa-users me-1"></i>Data Pasien</a>
        </li>
        <li class="sidebar-menu-item">
            <a href="form_pasien.php"><i class="fas fa-plus-circle me-1"></i>Tambah Pasien</a>
        </li>
        <li class="sidebar-menu-item">
            <a href="laporan.php"><i class="fas fa-chart-bar me-1"></i>Laporan</a>
        </li>
        <li class="sidebar-menu-item">
            <a href="pengaturan.php"><i class="fas fa-cog me-1"></i>Pengaturan</a>
        </li>
    </ul>
</div>

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

    <section class="hero text-center mb-4">
        <div class="container">
            <h1>Selamat Datang di Dashboard Puskesmas</h1>
            <p>Pantau dan kelola data pasien dengan mudah dan efisien.</p>
            <a href="form_pasien.php" class="btn btn-success btn-lg mt-3">
                <i class="fas fa-user-plus me-2"></i>Tambah Pasien Baru
            </a>
        </div>
    </section>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-injured me-2"></i>Total Pasien
                </div>
                <div class="card-body text-center">
                    <p class="card-text display-4"><?= $total_pasien ?></p>
                    <a href="data_pasien.php" class="btn btn-info btn-sm">
                        <i class="fas fa-eye me-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-2"></i>Distribusi Gender Pasien
                </div>
                <div class="card-body">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-map-marked-alt me-2"></i>Distribusi Pasien per Kelurahan
                </div>
                <div class="card-body">
                    <canvas id="kelurahanChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-line me-2"></i>Pertumbuhan Pasien Bulanan
                </div>
                <div class="card-body">
                    <canvas id="pertumbuhanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-clock me-2"></i>Pasien Terbaru
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Gender</th>
                                    <th>Kelurahan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pasien_terbaru)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data pasien terbaru</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($pasien_terbaru as $index => $row): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                            <td class="text-center">
                                                <?php if ($row['gender'] === 'L'): ?>
                                                    <i class="fas fa-mars text-primary" title="Laki-laki"></i>
                                                <?php elseif ($row['gender'] === 'P'): ?>
                                                    <i class="fas fa-venus text-danger" title="Perempuan"></i>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($row['nama_kelurahan'] ?? '-') ?></td>
                                            <td>
                                                <a href="form_pasien.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="data_pasien.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm" title="Detail">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                                <a href="proses_pasien.php?delete&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin akan menghapus pasien <?= htmlspecialchars($row['nama']) ?>?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="data_pasien.php" class="btn btn-primary btn-sm float-end">
                        Lihat Semua Pasien
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Chart Gender
    const genderData = {
        labels: [
            <?php 
            foreach ($distribusi_gender as $gender) {
                echo "'" . ($gender['gender'] == 'L' ? 'Laki-laki' : 'Perempuan') . "',";
            }
            ?>
        ],
        datasets: [{
            data: [<?php foreach ($distribusi_gender as $gender) { echo $gender['jumlah'] . ","; } ?>],
            backgroundColor: ['#007bff', '#dc3545']
        }]
    };

    const genderChart = new Chart(document.getElementById('genderChart'), {
        type: 'pie',
        data: genderData
    });

    // Chart Kelurahan
    const kelurahanData = {
        labels: [<?php foreach ($distribusi_kelurahan as $kelurahan) { echo "'" . $kelurahan['nama_kelurahan'] . "',"; } ?>],
        datasets: [{
            label: 'Jumlah Pasien',
            data: [<?php foreach ($distribusi_kelurahan as $kelurahan) { echo $kelurahan['jumlah'] . ","; } ?>],
            backgroundColor: '#28a745'
        }]
    };

    const kelurahanChart = new Chart(document.getElementById('kelurahanChart'), {
        type: 'bar',
        data: kelurahanData
    });

    // Chart Pertumbuhan
    const pertumbuhanData = {
        labels: [<?php foreach ($pertumbuhan_pasien as $pertumbuhan) { echo "'" . $pertumbuhan['bulan'] . "',"; } ?>],
        datasets: [{
            label: 'Jumlah Pasien',
            data: [<?php foreach ($pertumbuhan_pasien as $pertumbuhan) { echo $pertumbuhan['jumlah'] . ","; } ?>],
            borderColor: '#17a2b8',
            fill: false
        }]
    };

    const pertumbuhanChart = new Chart(document.getElementById('pertumbuhanChart'), {
        type: 'line',
        data: pertumbuhanData
    });

    // Sidebar Toggle
    const sidebar = document.querySelector('.sidebar');
    const content = document.querySelector('.content');
    const sidebarToggle = document.querySelector('#sidebarToggle');

    sidebarToggle.addEventListener('click', function () {
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