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
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $user_name = validate_input($_POST['user_name']);
        $password  = validate_input($_POST['password']);
        $full_name = validate_input($_POST['full_name']);
        $id        = validate_input($_POST['id']);

        // VALIDATIONS
        if (empty($user_name)) {

            $em = "User name is required!";
            header("Location: ../edit-user.php?error=$em&id=$id");
            exit();

        } else if (empty($full_name)) {

            $em = "Full name is required!";
            header("Location: ../edit-user.php?error=$em&id=$id");
            exit();

        } else {

            // =========================
            // IF PASSWORD IS FILLED
            // =========================
            if (!empty($password)) {

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $data = array(
                    $full_name,
                    $user_name,
                    $hashed_password,
                    "employee",
                    $id,
                    "employee"
                );

                update_user($conn, $data);

            } else {

                // =========================
                // UPDATE WITHOUT PASSWORD
                // =========================
                $data = array(
                    $full_name,
                    $user_name,
                    $id,
                    "employee"
                );

                update_user_without_password($conn, $data);
            }

            $sm = "User updated successfully!";
            header("Location: ../edit-user.php?success=$sm&id=$id");
            exit();
        }

    } else {

        $em = "Unknown error occurred!";
        header("Location: ../edit-user.php?error=$em");
        exit();
    }

} else {

    $em = "First login";
    header("Location: ../login.php?error=$em");
    exit();
}
?>