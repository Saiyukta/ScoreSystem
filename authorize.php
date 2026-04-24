<?php
$username = "admin";
$password = "password123";

if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || ($_SERVER['PHP_AUTH_USER'] !== $username) || ($_SERVER['PHP_AUTH_PW'] !== $password)) {
    header('WWW-Authenticate: Basic realm="GuitarWars Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Unauthorized';
    exit;
}

?>