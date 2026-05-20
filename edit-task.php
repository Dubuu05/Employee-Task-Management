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

<div class="create-task-container">

    <!-- HEADER -->
    <div class="create-task-header">

        <div>
            <h1>Edit Task</h1>
            <p>Update task details and assignments</p>
        </div>

        <a href="tasks.php" class="filter-btn">
            <i class="fa fa-arrow-left"></i>
            Back to Tasks
        </a>
        
        <div class="header-icon">
            <i class="fa fa-pencil"></i>
        </div>
        
        

    </div>

    <!-- CARD -->
    <div class="task-card">

        <form class="modern-form" method="POST" action="app/update-task.php">

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

            <div class="form-grid">

                <!-- TITLE -->
                <div class="form-group">
                    <label>
                        <i class="fa fa-header"></i>
                        Title
                    </label>

                    <input type="text"
                           name="title"
                           class="modern-input"
                           value="<?= htmlspecialchars($task['title']) ?>">
                </div>

                <!-- DUE DATE -->
                <div class="form-group">
                    <label>
                        <i class="fa fa-calendar"></i>
                        Due Date
                    </label>

                    <input type="date"
                           name="due_date"
                           class="modern-input"
                           value="<?= htmlspecialchars($task['due_date']) ?>">
                </div>

            </div>

            <!-- DESCRIPTION -->
            <div class="form-group">

                <label>
                    <i class="fa fa-align-left"></i>
                    Description
                </label>

                <textarea name="description"
                          rows="6"
                          class="modern-input textarea"><?= htmlspecialchars($task['description']) ?></textarea>

            </div>

            <div class="form-grid">

                <!-- PRIORITY -->
                <div class="form-group">

                    <label>
                        <i class="fa fa-flag"></i>
                        Priority
                    </label>

                    <select name="priority" class="modern-input">

                        <option value="High" <?= ($task['priority'] == 'High') ? 'selected' : '' ?>>
                            High
                        </option>

                        <option value="Medium" <?= ($task['priority'] == 'Medium') ? 'selected' : '' ?>>
                            Medium
                        </option>

                        <option value="Low" <?= ($task['priority'] == 'Low') ? 'selected' : '' ?>>
                            Low
                        </option>

                    </select>

                </div>

                <!-- ASSIGNED TO -->
                <div class="form-group">

                    <label>
                        <i class="fa fa-user"></i>
                        Assigned To
                    </label>

                    <select name="assigned_to" class="modern-input">

                        <option value="0">Select Employee</option>

                        <?php if ($users != 0) { 
                            foreach ($users as $user) { 
                                $selected = ($task['assigned_to'] == $user['id']) ? 'selected' : '';
                        ?>

                        <option value="<?= $user['id'] ?>" <?= $selected ?>>
                            <?= htmlspecialchars($user['full_name']) ?>
                        </option>

                        <?php } } ?>

                    </select>

                </div>

            </div>

            <!-- HIDDEN ID -->
            <input type="hidden" name="id" value="<?= $task['id'] ?>">

            <!-- BUTTON -->
            <button class="create-task-btn" type="submit">

                <i class="fa fa-save"></i>

                Update Task

            </button>

        </form>

    </div>

</div>

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