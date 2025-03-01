<?php

$database_host = "localhost";
$database_username = "root";
$database_password = "";
$database_name = "absensi";
$conn = mysqli_connect($database_host, $database_username, $database_password, $database_name) or die ("Tidak terhubung kedatabase");

function base_url($url =null) {
    
    $base_url = 'http://localhost/geolocation/';
    if($url != null){
        return $base_url . '/' . $url;
    } else {
        return $base_url;
    }
}
?>