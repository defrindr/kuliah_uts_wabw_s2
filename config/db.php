<?php
$servername = "localhost";
$username = "root";
$password = "defrindr";
$db = "semester2_web_uts";

try {
    $db = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    dd($e->getMessage());
}
