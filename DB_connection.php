<?php 
$sNAme = "localhost";
$uNAme = "root";          
$password = "";
$db_name = "task_management_db";

try {
    $conn = new PDO("mysql:host=$sNAme;dbname=$db_name", $uNAme, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}