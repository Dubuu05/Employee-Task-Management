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
        include_once "Model/Notification.php"; // Added to access notification functions

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

        // ==========================================
        // FINAL NOTIFICATION TRIGGER LOGIC
        // ==========================================
        $check_status = strtolower(trim($status));
        
        // Trigger if the status is "completed" OR "in_progress"
        if ($check_status === "completed" || $check_status === "in_progress") {
            
            // Force database errors to show just in case the notifications table is strict
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            try {
                // 1. Fetch admins
                $admin_sql = "SELECT id FROM users WHERE LOWER(role) = 'admin'";
                $admin_stmt = $conn->prepare($admin_sql);
                $admin_stmt->execute();
                $admins = $admin_stmt->fetchAll();

                // 2. Build the message
                $emp_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "An employee";
                
                // This turns "in_progress" into "In Progress" so it looks nice in the notification menu
                $display_status = ucwords(str_replace('_', ' ', $check_status));
                
                // Dynamic message that tells the admin what the new status is
                $message = $emp_name . " updated Task #" . $id . " to: " . $display_status;
                $type = "Task Update";

                // 3. Insert notification for each admin
                foreach ($admins as $admin) {
                    $data = [$message, $admin['id'], $type];
                    insert_notification($conn, $data);
                }
                
            } catch (PDOException $e) {
                // If the database rejects the insert, it will print the exact reason here
                exit("DATABASE ERROR: " . $e->getMessage());
            }
        }
        // ==========================================

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