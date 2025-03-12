<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    function salam($nama="Nurul Fikri"){
        echo "Selamat Datang". $nama;
    }
    ?>
    <h1>Belajar Fungsi</h1>
    <?php
    salam("Doni Setiawan");
    echo "<br>";
    salam($nama);
    ?>
</body>
</html>