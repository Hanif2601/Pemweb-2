<?php
$roko = ["Samsu", "Esse", "Mahayana", "Marlong"];

//Menghapus elemen pertama 
$awal = array_shift($roko);

//Hasil
echo "Roko yang di hapus : $awal";
echo " Array setelah shift <br>";
foreach ($roko as $r) {
    echo "$r <br>"; 
}

?>