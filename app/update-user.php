<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if (
        isset($_POST['user_name']) &&
        isset($_POST['full_name']) &&
        isset($_POST['id']) &&
        $_SESSION['role'] == "admin"
    ) {

        include "../DB_connection.php";
        include "Model/User.php";

        function validate_input($data) {
            return htmlspecialchars(stripslashes(trim($data)));
        }

        $user_name = validate_input($_POST['user_name']);
        $password  = isset($_POST['password']) ? validate_input($_POST['password']) : "";
        $full_name = validate_input($_POST['full_name']);
        $id        = validate_input($_POST['id']);

        if (empty($user_name)) {
            $em = "User name is required!";
            header("Location: ../edit-user.php?error=$em&id=$id");
            exit();

        } else if (empty($full_name)) {
            $em = "Full name is required!";
            header("Location: ../edit-user.php?error=$em&id=$id");
            exit();
        }

        if (!empty($password)) {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        } else {

            $hashed_password = null;
        }

        $data = array(
            $full_name,
            $user_name,
            $hashed_password,
            "employee",
            $id,
            "employee"
        );

        update_user($conn, $data);

        $sm = "User updated successfully!";
        header("Location: ../edit-user.php?success=$sm&id=$id");
        exit();

    } else {
        header("Location: ../edit-user.php?error=Unknown error");
        exit();
    }

} else {
    header("Location: ../login.php?error=First login");
    exit();
}
?>