<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if (
        isset($_POST['title']) &&
        isset($_POST['description']) &&
        isset($_POST['assigned_to']) &&
        isset($_POST['due_date']) &&
        isset($_POST['priority']) &&
        $_SESSION['role'] == 'admin'
    ) {

        include "../DB_connection.php";

        function count_priority($conn, $user_id, $priority) {
            $sql = "SELECT COUNT(*) as total
                    FROM tasks
                    WHERE assigned_to = ?
                    AND priority = ?
                    AND status != 'Completed'";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$user_id, $priority]);
            return $stmt->fetch()['total'];
        }

        function validate_input($data) {
            return htmlspecialchars(stripslashes(trim($data)));
        }

        $title = validate_input($_POST['title']);
        $description = validate_input($_POST['description']);
        $assigned_to = validate_input($_POST['assigned_to']);
        $due_date = validate_input($_POST['due_date']);
        $priority = validate_input($_POST['priority']);

        if (empty($title) || empty($description) || $assigned_to == 0) {
            header("Location: ../create_task.php?error=Please complete all fields!");
            exit();
        }

        $highCount = count_priority($conn, $assigned_to, "High");
        $mediumCount = count_priority($conn, $assigned_to, "Medium");
        $lowCount = count_priority($conn, $assigned_to, "Low");

 
        if ($lowCount >= 5) {

            if ($priority == "High" || $priority == "Medium") {
                header("Location: ../create_task.php?error=Blocked: 5 Low priority limit reached.");
                exit();
            }
        }

        if ($highCount >= 2) {

            if ($priority == "High") {
                header("Location: ../create_task.php?error=Max 2 High priority reached.");
                exit();
            }

            if ($priority == "Medium" && $mediumCount >= 1) {
                header("Location: ../create_task.php?error=Only 1 Medium allowed when 2 High exist.");
                exit();
            }

            if ($priority == "Low" && $lowCount >= 1) {
                header("Location: ../create_task.php?error=Only 1 Low allowed when 2 High exist.");
                exit();
            }
        }

        if ($mediumCount >= 2) {

            if ($priority == "High" || $priority == "Medium") {
                header("Location: ../create_task.php?error=Blocked: Medium limit reached (only Low allowed).");
                exit();
            }

            if ($priority == "Low" && $lowCount >= 2) {
                header("Location: ../create_task.php?error=Max 2 Low allowed when 2 Medium exist.");
                exit();
            }
        }

        $today = strtotime(date('Y-m-d'));
        $due = strtotime($due_date);

        if ($priority == "Medium") {
            $min = strtotime("+1 day", $today);
            if ($due < $min) {
                header("Location: ../create_task.php?error=Medium requires at least 1 day lead time.");
                exit();
            }
        }

        if ($priority == "Low") {
            $min = strtotime("+2 days", $today);
            if ($due < $min) {
                header("Location: ../create_task.php?error=Low requires at least 2 days lead time.");
                exit();
            }
        }


        include "Model/Task.php";
        include "Model/Notification.php";

        insert_task($conn, array(
            $title,
            $description,
            $assigned_to,
            $due_date,
            $priority
        ));

        insert_notification($conn, array(
            "$title has been assigned to you. Please review and start working on it",
            $assigned_to,
            'New Task Assigned'
        ));

        header("Location: ../create_task.php?success=Task added successfully!");
        exit();

    } else {
        header("Location: ../create_task.php?error=Invalid request!");
        exit();
    }

} else {
    header("Location: ../create_task.php?error=First login");
    exit();
}
?>