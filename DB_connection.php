<?php 
$server = "localhost";
$database = "EmployeeTaskDB";

try {
    $conn = new PDO("sqlsrv:Server=$server;Database=$database");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>