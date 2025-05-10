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
        body {
            background-color: #f8f9fa;
        }

        /* Navbar Styles */
        .navbar {
            background-color: #343a40; /* Dark background */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .navbar-brand {
            color: #ffffff; /* White text */
            font-weight: bold; /* Bold text */
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.7); /* Slightly transparent white */
            transition: color 0.3s ease; /* Smooth transition */
        }

        .navbar-nav .nav-link:hover {
            color: #ffffff; /* White on hover */
        }

        .navbar-toggler-icon {
            background-color: #ffffff; /* White toggler icon */
        }

        /* Card Styles */
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out; /* Smooth transform */
        }

        .card:hover {
            transform: scale(1.02); /* Slight scale up on hover */
        }

        .card-header {
            background-color: #007bff; /* Blue header */
            color: white;
            border-radius: 10px 10px 0 0 !important;
            font-weight: bold; /* Bold header text */
            padding: 1rem; /* Increased padding */
        }

        .card-body {
            padding: 1.5rem; /* Increased padding */
        }

        /* Hero Section Styles */
        .hero {
            background: linear-gradient(to right, #4a90e2, #637ad6); /* Gradient background */
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.1rem;
        }

        /* Utility Styles */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
            border-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745; /* Green */
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #1e7e34; /* Darker green */
            border-color: #1e7e34;
        }

        .btn-info {
            background-color: #17a2b8; /* Teal */
            border-color: #17a2b8;
        }

        .btn-info:hover {
            background-color: #138d9d; /* Darker teal */
            border-color: #138d9d;
        }

        .btn-warning {
            background-color: #ffc107; /* Yellow */
            border-color: #ffc107;
        }

        .btn-warning:hover {
            background-color: #d39e00; /* Darker yellow */
            border-color: #d39e00;
        }

        .btn-danger {
            background-color: #dc3545; /* Red */
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333; /* Darker red */
            border-color: #c82333;
        }

        .btn-secondary {
            background-color: #6c757d; /* Gray */
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #545b62; /* Darker gray */
            border-color: #545b62;
        }

        .display-4 {
            font-size: 3.5rem; /* Larger display text */
            font-weight: bold;
            color: #333; /* Darker text */
        }

        .table-responsive {
            overflow-x: auto; /* Enable horizontal scrolling on small screens */
        }

        .table {
            margin-bottom: 1.5rem; /* Spacing below table */
        }

        /* Chart Styles */
        #genderChart {
            max-height: 300px; /* Limit chart height */
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">
            <i class="fas fa-hospital me-2"></i>Puskesmas Dashboard
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_pasien.php">
                        <i class="fas fa-users me-1"></i>Data Pasien
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="form_pasien.php">
                        <i class="fas fa-plus-circle me-1"></i>Tambah Pasien
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
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
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>