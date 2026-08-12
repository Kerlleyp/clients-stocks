<?php
$host = "sql113.infinityfree.com";
$dbname = "if0_42633501_clientstock";
$user = "if0_42633501";
$pass = "IntTK2FrpY6h";

$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>