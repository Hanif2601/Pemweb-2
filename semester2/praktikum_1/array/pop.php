<?php
$siswa = ["Tian", "Asep", "Ahong", "Clipe"];

//menampilkan array awal
echo "<br>Array awal : <br>";
print_r($siswa);

//menghapus elemen terakhir dari array
$orang_terakhir = array_pop($siswa);

//menampilkan elemen yang di hapus
echo "<br> Elemen yang akan di hapus" .$orang_terakhir."<br" ;

//menampilkan array setelah penghapusan elemen terakhir
echo "<br> Array setelah penghapusan : <br>";
print_r($siswa);
?>