<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] === 'admin') {

    if (isset($_POST['id']) && isset($_POST['comment'])) {

        include "../DB_connection.php";
        include_once "Model/Notification.php";

        $task_id = $_POST['id'];
        $comment = trim($_POST['comment']);

        try {

            $title_sql = "SELECT title FROM tasks WHERE id = ?";
            $title_stmt = $conn->prepare($title_sql);
            $title_stmt->execute([$task_id]);
            $task_title = $title_stmt->fetchColumn();

            $sql = "UPDATE tasks 
                    SET file_path = NULL, status = 'in_progress' 
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$task_id]);

            $emp_sql = "SELECT assigned_to FROM tasks WHERE id = ?";
            $emp_stmt = $conn->prepare($emp_sql);
            $emp_stmt->execute([$task_id]);
            $assigned_employee = $emp_stmt->fetchColumn();

            if ($assigned_employee) {

                $message = "Task: " . $task_title . " has been returned. Reason: " . $comment;
                $type = "Task Returned";

                insert_notification($conn, [
                    $message,
                    $assigned_employee,
                    $type
                ]);
            }

            $sm = "Task returned to employee with feedback!";
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