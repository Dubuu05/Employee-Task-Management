<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if (
        isset($_POST['title']) &&
        isset($_POST['description']) &&
        isset($_POST['assigned_to']) &&
        isset($_POST['due_date']) &&
        isset($_POST['priority']) && 
        $_SESSION['role'] == "admin"
    ) {

        include "../DB_connection.php";

        // 🔴 HIGH PRIORITY COUNT
        function count_high_priority($conn, $user_id) {
            $sql = "SELECT COUNT(*) as total 
                    FROM tasks 
                    WHERE assigned_to = ? 
                    AND priority = 'High' 
                    AND status != 'Completed'";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$user_id]);
            $result = $stmt->fetch();

            return $result['total'];
        }

        // 🟠 MEDIUM PRIORITY COUNT
        function count_medium_priority($conn, $user_id) {
            $sql = "SELECT COUNT(*) as total 
                    FROM tasks 
                    WHERE assigned_to = ? 
                    AND priority = 'Medium' 
                    AND status != 'Completed'";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$user_id]);
            $result = $stmt->fetch();

            return $result['total'];
        }

        // CLEAN INPUT
        function validate_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $title = validate_input($_POST['title']);
        $description = validate_input($_POST['description']);
        $assigned_to = validate_input($_POST['assigned_to']);
        $due_date = validate_input($_POST['due_date']);
        $priority = validate_input($_POST['priority']);

        // VALIDATION
        if (empty($title)) {

            $em = "Title is required!";
            header("Location: ../create_task.php?error=$em");
            exit();

        } else if (empty($description)) {

            $em = "Description is required!";
            header("Location: ../create_task.php?error=$em");
            exit();

        } else if ($assigned_to == 0) {

            $em = "Select User required!";
            header("Location: ../create_task.php?error=$em");
            exit();

        } else {

            // 🔴 HIGH PRIORITY LIMIT (MAX 2)
            if ($priority == "High") {

                $count = count_high_priority($conn, $assigned_to);

                if ($count >= 2) {
                    $em = "This employee already has 2 High priority tasks.";
                    header("Location: ../create_task.php?error=$em");
                    exit();
                }
            }

            // 🟠 MEDIUM PRIORITY LIMIT (MAX 8)
            if ($priority == "Medium") {

                $count = count_medium_priority($conn, $assigned_to);

                if ($count >= 8) {
                    $em = "This employee already has 8 Medium priority tasks.";
                    header("Location: ../create_task.php?error=$em");
                    exit();
                }
            }

            include "Model/Task.php";
            include "Model/Notification.php";

            // INSERT TASK
            $data = array($title, $description, $assigned_to, $due_date, $priority);
            insert_task($conn, $data);

            // NOTIFICATION
            $notif_data = array(
                "'$title' has been assigned to you. Please review and start working on it",
                $assigned_to,
                'New Task Assigned'
            );

            insert_notification($conn, $notif_data);

            $em = "Task added successfully!";
            header("Location: ../create_task.php?success=$em");
            exit();
        }

    } else { 
        $em = "Unknown error occurred!";
        header("Location: ../create_task.php?error=$em");
        exit(); 
    }

} else { 
    $em = "First login";
    header("Location: ../create_task.php?error=$em");
    exit(); 
}
?>