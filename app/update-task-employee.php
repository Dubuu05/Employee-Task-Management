<?php
session_start();

if (!empty($_SESSION['role']) && !empty($_SESSION['id'])) {

    if (
        $_SESSION['role'] === 'employee' &&
        isset($_POST['id']) &&
        isset($_POST['status'])
    ) {

        include "../DB_connection.php";
        include "Model/Task.php";

        // Sanitize input
        function validate_input($data) {
            return htmlspecialchars(stripslashes(trim($data)));
        }

        $status = validate_input($_POST['status']);
        $id = validate_input($_POST['id']);

        // Validate inputs
        if (empty($status)) {
            $em = "Status is required!";
            header("Location: ../edit-task-employee.php?error=" . urlencode($em) . "&id=" . $id);
            exit();
        }

        if (!is_numeric($id)) {
            $em = "Invalid Task ID!";
            header("Location: ../edit-task-employee.php?error=" . urlencode($em));
            exit();
        }

        // Handle file upload if a file was selected
        $file_path = null;
        if (isset($_FILES['task_file']) && $_FILES['task_file']['error'] === 0) {
            $upload_dir = '../uploads/'; // make sure this folder exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_name = time() . '_' . basename($_FILES['task_file']['name']);
            $target_file = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['task_file']['tmp_name'], $target_file)) {
                $file_path = 'uploads/' . $file_name; // relative path for DB
            } else {
                $em = "File upload failed!";
                header("Location: ../edit-task-employee.php?error=" . urlencode($em) . "&id=" . $id);
                exit();
            }
        }

        // Update task in DB
        if ($file_path) {
            // If file uploaded, update status AND file_path
            update_task_status_and_file($conn, $id, $status, $file_path);
        } else {
            // Only update status
            update_task_status($conn, [$status, $id]);
        }

        $sm = "Task updated successfully!";
        header("Location: ../edit-task-employee.php?success=" . urlencode($sm) . "&id=" . $id);
        exit();

    } else {
        $em = "Unauthorized access!";
        header("Location: ../edit-task-employee.php?error=" . urlencode($em));
        exit();
    }

} else {
    $em = "First login";
    header("Location: ../login.php?error=" . urlencode($em));
    exit();
}
?>