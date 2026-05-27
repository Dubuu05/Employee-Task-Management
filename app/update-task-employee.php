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
        include_once "Model/Notification.php";

        function validate_input($data) {
            return htmlspecialchars(stripslashes(trim($data)));
        }

        $status = validate_input($_POST['status']);
        $id = validate_input($_POST['id']);

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

        $file_path = null;

        if (isset($_FILES['task_file']) && $_FILES['task_file']['error'] === 0) {

            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_name = time() . '_' . basename($_FILES['task_file']['name']);
            $target_file = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['task_file']['tmp_name'], $target_file)) {
                $file_path = 'uploads/' . $file_name;
            } else {
                $em = "File upload failed!";
                header("Location: ../edit-task-employee.php?error=" . urlencode($em) . "&id=" . $id);
                exit();
            }
        }


        if ($file_path) {
            update_task_status_and_file($conn, $id, $status, $file_path);
        } else {
            update_task_status($conn, [$status, $id]);
        }


        $check_status = strtolower(trim($status));

        if ($check_status === "completed" || $check_status === "in_progress") {

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {

                $admin_sql = "SELECT id FROM users WHERE LOWER(role) = 'admin'";
                $admin_stmt = $conn->prepare($admin_sql);
                $admin_stmt->execute();
                $admins = $admin_stmt->fetchAll();

                $emp_name = $_SESSION['full_name'] 
                    ?? $_SESSION['username'] 
                    ?? "Employee";

                $task_sql = "SELECT title FROM tasks WHERE id = ?";
                $task_stmt = $conn->prepare($task_sql);
                $task_stmt->execute([$id]);
                $task = $task_stmt->fetch();

                $task_title = $task['title'] ?? "Unknown Task";

                $display_status = ucwords(str_replace('_', ' ', $check_status));

                $message = "$emp_name Updated the Task '$task_title' to: $display_status";
                $type = "Task Update";

                foreach ($admins as $admin) {
                    $data = [$message, $admin['id'], $type];
                    insert_notification($conn, $data);
                }

            } catch (PDOException $e) {
                exit("DATABASE ERROR: " . $e->getMessage());
            }
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