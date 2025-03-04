<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
<title>Form Nilai | Hanif Fatih Azzam</title>

</head>
<body>



<form method="post" action="nilai_siswa.php" class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>Form Nilai Mahasiswa</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Nama Lengkap</label>  
  <div class="col-md-4">
  <input name="nama" type="text" placeholder="Masukan Nama ..." class="form-control input-md">
  </div>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="selectbasic">Mata Kuliah</label>
  <div class="col-md-4">
    <select id="selectbasic" name="matkul" class="form-control">
      <option value="">-- Pilih Mata Kuliah --</option>
      <option value="DDP">Dasar Dasar Pemrograman</option>
      <option value="BDI">Basis Data I</option>
      <option value="WEBI">Pemrograman Web I</option>
    </select>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Nilai UTS</label>  
  <div class="col-md-2">
  <input id="text" name="nilai_uts" type="text" placeholder="Masukan Nilai UTS...." size="6" class="form-control input-md">
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Nilai UAS</label>  
  <div class="col-md-2">
  <input id="text" name="nilai_uas" type="text" placeholder="Masukan Nilai UAS...." size="10" class="form-control input-md-10" >
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Nilai Tugas/Praktikum</label>  
  <div class="col-md-2">
  <input id="text" name="nilai_tugas" type="text" placeholder="Masukan Nilai Tugas...." size="6" class="form-control input-md">
  </div>
</div>
    <!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton">Kirim Nilai Siswa</label>
  <div class="col-md-4">
    <button type="submit" class="btn btn-primary">Kirim</button>
  </div>
</div>
</div>



</fieldset>
</form>

</body>
</html>