<?php
$host = "localhost";
$username = "root";
$password = null; 
$dbname = "expenses";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connection done";
} catch (PDOException $err) {
    echo "Connection failed: " . $err->getMessage();
}
?>
