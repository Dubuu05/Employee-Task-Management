<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if (
        isset($_POST['id']) &&
        isset($_POST['title']) &&
        isset($_POST['description']) &&
        isset($_POST['assigned_to']) &&
        isset($_POST['due_date']) &&
        isset($_POST['priority']) && 
        $_SESSION['role'] == "admin"
    ) {

        include "../DB_connection.php";

        function validate_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // INPUTS
        $id = validate_input($_POST['id']);
        $title = validate_input($_POST['title']);
        $description = validate_input($_POST['description']);
        $assigned_to = validate_input($_POST['assigned_to']);
        $due_date = validate_input($_POST['due_date']);
        $priority = validate_input($_POST['priority']);

        // VALIDATION
        if (empty($title)) {
            $em = "Title is required!";
            header("Location: ../edit-task.php?error=$em&id=$id");
            exit();

        } else if (empty($description)) {
            $em = "Description is required!";
            header("Location: ../edit-task.php?error=$em&id=$id");
            exit();

        } else if ($assigned_to == 0) {
            $em = "Select User required!";
            header("Location: ../edit-task.php?error=$em&id=$id");
            exit();

        } else {

            include "Model/Task.php";
            include "Model/Notification.php"; 

            $data = array(
                $title,
                $description,
                $assigned_to,
                $due_date,
                $priority,
                $id
            );

            update_task($conn, $data);


            $notif_data = array(
                "Task '$title' was updated. Priority was updated to: $priority",
                $assigned_to,
                "Task Updated"
            );

            insert_notification($conn, $notif_data);

            $em = "Task updated successfully!";
            header("Location: ../edit-task.php?success=$em&id=$id");
            exit();
        }

    } else { 
        $em = "Unknown error occurred!";
        header("Location: ../edit-task.php?error=$em");
        exit(); 
    }

} else { 
    $em = "First login";
    header("Location: ../login.php?error=$em");
    exit(); 
}
?>