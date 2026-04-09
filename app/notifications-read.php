<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "../DB_connection.php";
    include "Model/Notification.php";

    if (isset($_GET['notification_id'])) {
        $notification_id = $_GET['notification_id'];
        
        // 1. I-update ang status sa database (magiging is_read = 1)
        notification_make_read($conn, $_SESSION['id'], $notification_id);

        // 2. I-redirect pabalik nang walang dalang 'success' message sa URL
        header("Location: ../notifications.php");
        exit();
        
    } else {
        header("Location: index.php");
        exit();
    }
} else {
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
}
?>