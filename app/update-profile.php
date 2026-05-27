<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if ($_SESSION['role'] == "employee" && isset($_POST['full_name'])) {

        include "../DB_connection.php";
        include "Model/User.php";

        function validate_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $id = $_SESSION['id'];

        $full_name = validate_input($_POST['full_name']);
        $password = validate_input($_POST['password']);
        $new_password = validate_input($_POST['new_password']);
        $confirm_password = validate_input($_POST['confirm_password']);

        if (empty($full_name)) {
            $em = "Full name is required!";
            header("Location: ../edit_profile.php?error=$em");
            exit();
        }

        if (
            empty($password) &&
            empty($new_password) &&
            empty($confirm_password)
        ) {

            $sql = "UPDATE users SET full_name=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$full_name, $id]);

            $em = "Profile updated successfully!";
            header("Location: ../edit_profile.php?success=$em");
            exit();
        }

        if (
            empty($password) ||
            empty($new_password) ||
            empty($confirm_password)
        ) {
            $em = "Fill all password fields to change password!";
            header("Location: ../edit_profile.php?error=$em");
            exit();
        }

        if ($new_password != $confirm_password) {
            $em = "New passwords do not match!";
            header("Location: ../edit_profile.php?error=$em");
            exit();
        }

        $user = get_user_by_id($conn, $id);

        if (!$user) {
            $em = "User not found!";
            header("Location: ../edit_profile.php?error=$em");
            exit();
        }

        if (!password_verify($password, $user['password'])) {
            $em = "Incorrect old password!";
            header("Location: ../edit_profile.php?error=$em");
            exit();
        }

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET full_name=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$full_name, $hashed_password, $id]);

        $em = "Profile updated successfully!";
        header("Location: ../edit_profile.php?success=$em");
        exit();

    } else {
        $em = "Unknown error occurred!";
        header("Location: ../edit_profile.php?error=$em");
        exit();
    }

} else {
    $em = "First login";
    header("Location: ../login.php?error=$em");
    exit();
}
?>