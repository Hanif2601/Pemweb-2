<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>web1</title>
</head>
<body>
    <h1>Selamat Belajar PHP</h1>
    <?php
        $_nama = "Nurul fikri Alamsyah";
        $_umur = 20;
        $_prodi = "Teknik Informatika";
        $_ipk = 3.5;
    ?>
    <p>Nama : <?php echo $_nama; ?></p>
    <p>Umur : <?=$_umur; ?></p>
    <p>prodi : <?=$_prodi; ?></p>
    <p>ipk: <?=$_ipk; ?></p>
    
    <hr>
    <?php


    ?>
</body>
</html>