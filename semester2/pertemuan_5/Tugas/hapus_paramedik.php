<?php
include 'config.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM paramedik WHERE id=$id");
header("Location: index.php");
?>
