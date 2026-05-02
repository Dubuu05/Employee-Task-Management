<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == 'admin') {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";

    $users = get_all_users($conn); 

    if (!isset($_GET['id'])) {
        header("Location: tasks.php");
        exit();
    }

    $id = $_GET['id'];
    $task = get_task_by_id($conn, $id);

    if ($task == 0) {
        header("Location: tasks.php");
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <input type="checkbox" id="checkbox">

    <?php include "inc/header.php" ?>

    <div class="body">
        <?php include "inc/nav.php" ?>

        <section class="section-1">
            <h4 class="title">
                Edit Task 
                <a href="tasks.php">Tasks</a>
            </h4>

            <form class="form-1" method="POST" action="app/update-task.php">

                <?php if (isset($_GET['error'])) { ?> 
                    <div class="danger">
                        <?= stripslashes($_GET['error']); ?>
                    </div>
                <?php } ?>

                <?php if (isset($_GET['success'])) { ?> 
                    <div class="success">
                        <?= stripslashes($_GET['success']); ?>
                    </div>
                <?php } ?>

                <!-- TITLE -->
                <div class="input-holder">
                    <label>Title</label>
                    <input type="text" name="title" class="input-1" 
                           value="<?= htmlspecialchars($task['title']) ?>">
                </div>

                <!-- DESCRIPTION -->
                <div class="input-holder">
                    <label>Description</label>
                    <textarea name="description" rows="5" class="input-1"><?= htmlspecialchars($task['description']) ?></textarea>
                </div>

                <!-- DUE DATE -->
                <div class="input-holder">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="input-1" 
                           value="<?= htmlspecialchars($task['due_date']) ?>">
                </div>

                <!-- PRIORITY (NEW) -->
                <div class="input-holder">
                    <label>Priority</label>
                    <select name="priority" class="input-1">
                        <option value="High" <?= ($task['priority'] == 'High') ? 'selected' : '' ?>>High</option>
                        <option value="Medium" <?= ($task['priority'] == 'Medium') ? 'selected' : '' ?>>Medium</option>
                        <option value="Low" <?= ($task['priority'] == 'Low') ? 'selected' : '' ?>>Low</option>
                    </select>
                </div>

                <!-- ASSIGNED TO -->
                <div class="input-holder">
                    <label>Assigned To</label>
                    <select name="assigned_to" class="input-1">
                        <option value="0">Select Employee</option>

                        <?php if ($users != 0) { 
                            foreach ($users as $user) { 
                                $selected = ($task['assigned_to'] == $user['id']) ? 'selected' : '';
                        ?>
                            <option value="<?= $user['id'] ?>" <?= $selected ?>>
                                <?= htmlspecialchars($user['full_name']) ?>
                            </option>
                        <?php 
                            } 
                        } ?>
                    </select>
                </div>  

                <!-- HIDDEN ID -->
                <input type="hidden" name="id" value="<?= $task['id'] ?>">

                <button class="edit-btn">Update</button>
            </form>

        </section>
    </div>

    <script type="text/javascript">
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