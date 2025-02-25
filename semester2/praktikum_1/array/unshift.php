<?php 


$laptop = ["Asus", "Lenovo", "Dell", "Axioo"];

//menbambahkan element di awal 
array_unshift($laptop, "HP", "acer");

//hasil
echo "Hasil";
foreach ($laptop as $p) {
    echo "<br>".$p;
}
?>