<?php
session_start();

// Ensure only the Admin can do this
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] === 'admin') {
    
    if (isset($_GET['id'])) {
        include "../DB_connection.php";
        
        $task_id = $_GET['id'];

        try {
            // 1. Delete the file and revert status to "in_progress"
            $sql = "UPDATE tasks SET file_path = NULL, status = 'in_progress' WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$task_id]);

            // ==========================================
            // OPTIONAL: Notify the Employee!
            // ==========================================
            include_once "Model/Notification.php";
            
            // Get the employee ID assigned to this task
            $emp_sql = "SELECT assigned_to FROM tasks WHERE id = ?";
            $emp_stmt = $conn->prepare($emp_sql);
            $emp_stmt->execute([$task_id]);
            $assigned_employee = $emp_stmt->fetchColumn();

            if ($assigned_employee) {
                $message = "Admin has rejected your file and returned Task #" . $task_id . " to In Progress.";
                $type = "Task Returned";
                insert_notification($conn, [$message, $assigned_employee, $type]);
            }
            // ==========================================

            $sm = "Task returned and file deleted successfully!";
            header("Location: ../tasks.php?success=" . urlencode($sm));
            exit();

        } catch (PDOException $e) {
            $em = "Database error: " . $e->getMessage();
            header("Location: ../tasks.php?error=" . urlencode($em));
            exit();
        }
    } else {
        header("Location: ../tasks.php");
        exit();
    }

} else {
    $em = "Unauthorized Access";
    header("Location: ../login.php?error=" . urlencode($em));
    exit();
}
?>