<?php
session_start();

if (!isset($_SESSION['role']) || !isset($_SESSION['id'])) {
    header("Location: ../add-user.php?error=First login!");
    exit();
}

if ($_SESSION['role'] !== "admin") {
    header("Location: ../add-user.php?error=Unauthorized access!");
    exit();
}

if (
    isset($_POST['user_name']) &&
    isset($_POST['password']) &&
    isset($_POST['full_name'])
) {

    include "../DB_connection.php";
    include "Model/User.php";

    function validate_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $user_name = validate_input($_POST['user_name']);
    $password  = validate_input($_POST['password']);
    $full_name = validate_input($_POST['full_name']);

    if (empty($user_name)) {
        header("Location: ../add-user.php?error=User name is required!");
        exit();
    }

    if (empty($password)) {
        header("Location: ../add-user.php?error=Password is required!");
        exit();
    }

    if (empty($full_name)) {
        header("Location: ../add-user.php?error=Full name is required!");
        exit();
    }

    if (account_exists($conn, $user_name)) {
        header("Location: ../add-user.php?error=Username already exists!");
        exit();
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    $data = [
        $full_name,
        $user_name,
        $password,
        "employee"
    ];

    try {
        $result = insert_user($conn, $data);

        if ($result) {
            header("Location: ../add-user.php?success=User added successfully!");
            exit();
        } else {
            header("Location: ../add-user.php?error=Failed to add user!");
            exit();
        }

    } catch (PDOException $e) {
        header("Location: ../add-user.php?error=Database error occurred!");
        exit();
    }

} else {
    header("Location: ../add-user.php?error=Invalid request!");
    exit();
}
?>