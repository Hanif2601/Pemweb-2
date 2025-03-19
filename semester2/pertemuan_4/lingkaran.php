<?php
class Lingkaran{
    public $jari;
    public const PHI = 3.14;

    public function __construc($jari) {
        $this->jari = $jari;
    }

    public function getLuas(){
        $luas = self::PHI * $this->jari * $this->jari;
        return $luas;
    }

    public function getKeliling(){
        $keliling = 2.0 * self::PHI * $this->jari;
        return $keliling;
    }

    public function cetak(){
        echo "Lingkaran dengan jari-jari ". $this->jari;
        echo "<br>Luas = ". $this->getLuas();
        echo "<br>Keliling =". $this->getKeliling();
    }
}
?>