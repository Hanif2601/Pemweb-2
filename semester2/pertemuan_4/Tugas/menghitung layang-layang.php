<?php
class LayangLayang {
    public $diagonal1;
    public $diagonal2;
    public $sisiA;
    public $sisiB;

    public function __construct($d1, $d2, $a, $b) {
        $this->diagonal1 = $d1;
        $this->diagonal2 = $d2;
        $this->sisiA = $a;
        $this->sisiB = $b;
    }

    // Menghitung luas layang-layang
    public function luas() {
        return ($this->diagonal1 * $this->diagonal2) / 2;
    }

    // Menghitung keliling layang-layang
    public function keliling() {
        return 2 * ($this->sisiA + $this->sisiB);
    }

    public function getHasil() {
        return [
            'diagonal1' => $this->diagonal1,
            'diagonal2' => $this->diagonal2,
            'sisiA' => $this->sisiA,
            'sisiB' => $this->sisiB,
            'luas' => number_format($this->luas(), 2),
            'keliling' => number_format($this->keliling(), 2)
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perhitungan Layang-Layang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen py-8 px-4 bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-blue-700 p-6">
            <h1 class="text-2xl font-bold text-white text-center">Perhitungan Layang-Layang</h1>
        </div>

        <div class="p-6">
            <form action="" method="post" class="space-y-6">
                <div class="space-y-4">
                    <div>
                        <label for="d1" class="block text-sm font-medium text-gray-700 mb-1">Diagonal 1 (m)</label>
                        <input type="number" name="d1" id="d1" step="0.01" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-3 border focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
                    </div>
                    
                    <div>
                        <label for="d2" class="block text-sm font-medium text-gray-700 mb-1">Diagonal 2 (m)</label>
                        <input type="number" name="d2" id="d2" step="0.01" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-3 border focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
                    </div>
                    
                    <div>
                        <label for="sisiA" class="block text-sm font-medium text-gray-700 mb-1">Sisi A (m)</label>
                        <input type="number" name="sisiA" id="sisiA" step="0.01" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-3 border focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
                    </div>
                    
                    <div>
                        <label for="sisiB" class="block text-sm font-medium text-gray-700 mb-1">Sisi B (m)</label>
                        <input type="number" name="sisiB" id="sisiB" step="0.01" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-3 border focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
                    </div>
                </div>

                <button type="submit" name="hitung"
                    class="w-full bg-gradient-to-r from-green-600 to-blue-700 text-white py-3 px-4 rounded-md hover:from-green-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200">
                    Hitung
                </button>
            </form>

            <?php if (isset($_POST["hitung"])): ?>
                <?php
                $d1 = floatval($_POST["d1"]);
                $d2 = floatval($_POST["d2"]);
                $a = floatval($_POST["sisiA"]);
                $b = floatval($_POST["sisiB"]);
                
                $layang = new LayangLayang($d1, $d2, $a, $b);
                $hasil = $layang->getHasil();
                ?>
                
                <div class="mt-6 bg-gray-50 rounded-md p-6 shadow-inner">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Hasil Perhitungan:</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Diagonal 1:</span>
                            <span class="text-gray-800 font-medium"><?= $hasil['diagonal1'] ?> m</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Diagonal 2:</span>
                            <span class="text-gray-800 font-medium"><?= $hasil['diagonal2'] ?> m</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sisi A:</span>
                            <span class="text-gray-800 font-medium"><?= $hasil['sisiA'] ?> m</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sisi B:</span>
                            <span class="text-gray-800 font-medium"><?= $hasil['sisiB'] ?> m</span>
                        </div>
                        
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-gray-600 font-semibold">Luas:</span>
                            <span class="text-green-700 font-bold"><?= $hasil['luas'] ?> m²</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-semibold">Keliling:</span>
                            <span class="text-green-700 font-bold"><?= $hasil['keliling'] ?> m</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>