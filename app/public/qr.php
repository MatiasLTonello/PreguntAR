<?php
require "../vendor/phpqrcode/qrlib.php";


$level = "Q";
$tamaño = 8;
$framSize = 2;
$ruta="https//:localhost/profile&id=".$_GET['id'];
QRcode::png($ruta, null, $level, $tamaño, $framSize);
?>