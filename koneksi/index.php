<?php
$hostname = "localhost";
$username = "root";
$password = "root";
$dbname = "rtf";

$koneksi = mysqli_connect($hostname, $username, $password, $dbname);

$url = "http://localhost:1975/rtf-php/";

// if ($koneksi) {
//     echo "koneksi berhasil";
// } else {
//     echo "koneksi gagal";
// }
