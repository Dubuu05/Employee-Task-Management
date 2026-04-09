<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == 'admin') {       
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";

    $text = "All Tasks";
    if(isset($_GET['due_date']) && $_GET['due_date'] == "Due Today") {
        $text = "Due Today";
    $tasks = get_all_tasks_due_today($conn); 
    $num_task = count_tasks_due_today($conn);

    } elseif (isset($_GET['due_date']) && $_GET['due_date'] == "Overdue") {
        $text = "Overdue";
    $tasks = get_all_tasks_overdue($conn); 
    $num_task = count_tasks_overdue($conn);

    } else if(isset($_GET['due_date']) && $_GET['due_date'] == "No Deadline") {
        $text = "No Deadline";
        $tasks = get_all_tasks_no_deadline($conn); 
        $num_task = count_tasks_no_deadline($conn);
    
    
    
        }else {
        $tasks = get_all_tasks($conn); 
    $num_task = count_tasks($conn);
    }

    $users = get_all_users($conn); 
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Tasks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php"; ?>
    <div class="body">
        <?php include "inc/nav.php"; ?>
        <section class="section-1">
            <h4 class="title-2">
            <a href="create_task.php" class="btn" >Create Task</a>
            <a href="tasks.php?due_date=Due Today">Due Today</a>
            <a href="tasks.php?due_date=Overdue">Overdue</a>
            <a href="tasks.php?due_date=No Deadline">No Deadline</a>
            <a href="tasks.php">All Tasks</a>
        </h4>
<h4 class="title-2"><?= $text ?> (<?= $num_task ?>) </h4>
            <!-- Success message -->
            <?php if (isset($_GET['success'])) { ?> 
                <div class="success" role="alert">
                    <?= stripslashes($_GET['success']); ?>
                </div>
            <?php } ?>

            <!-- Tasks table -->
            <?php if ($tasks != 0) { ?>
                <table class="main-table">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                    <?php $i = 0; foreach ($tasks as $task) { ?>
                        <tr>
                            <td><?= ++$i ?></td>
                            <td><?= htmlspecialchars($task['title']) ?></td>
                            <td><?= htmlspecialchars($task['description']) ?></td>
                            <td>
                                <?php 
                                foreach($users as $user) {
                                    if($user['id'] == $task['assigned_to']) {
                                        echo htmlspecialchars($user['full_name']);
                                    }
                                }
                                ?>
                            </td>
                            <td>
    <?php 
        if (empty($task['due_date'])) {
            echo "No Deadline";
        } else {
            echo htmlspecialchars($task['due_date']);
        }
    ?>
</td>
<td><?= htmlspecialchars($task['status']) ?></td>

                            <!-- ✅ FIXED FILE COLUMN -->
                            <td>
                                <?php if (!empty($task['file_path'])) { ?>
                                    <a href="download.php?file=<?= urlencode(basename($task['file_path'])) ?>" class="edit-btn">
                                        Download
                                    </a>
                                <?php } else { ?>
                                    <span style="font-style:italic; color:#888;">No file</span>
                                <?php } ?>
                            </td>

                            <td>
                                <a href="edit-task.php?id=<?= $task['id'] ?>" class="edit-btn">Edit</a>
                                <a href="delete-task.php?id=<?= $task['id'] ?>" class="delete-btn">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <h3>No tasks found</h3>
            <?php } ?>
        </section>
    </div>

    <script>
        var active = document.querySelector("#navlist li:nth-child(4)");
        if(active) active.classList.add("active");
    </script>
</body>
</html>

<?php  
} else { 
    $em = "First login";
    header("Location: login.php?error=$em");
    exit(); 
}
?>