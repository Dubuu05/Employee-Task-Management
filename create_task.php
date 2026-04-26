<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == 'admin') {       
    include "DB_connection.php";
    include "app/Model/User.php";

    $users = get_all_users($conn); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Task</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php"; ?>
    <div class="body">
        <?php include "inc/nav.php"; ?>

        <section class="section-1">
            <h4 class="title">Create Task</h4>

            <form class="form-1" method="POST" action="app/add-task.php">

                <?php if (isset($_GET['error'])) { ?> 
                    <div class="danger">
                        <?php echo stripslashes($_GET['error']); ?>
                    </div>
                <?php } ?>

                <?php if (isset($_GET['success'])) { ?> 
                    <div class="success">
                        <?php echo stripslashes($_GET['success']); ?>
                    </div>
                <?php } ?>

                <!-- TITLE -->
                <div class="input-holder">
                    <label>Title</label>
                    <input type="text" name="title" class="input-1" placeholder="Title" required>
                </div>

                <!-- DESCRIPTION -->
                <div class="input-holder">
                    <label>Description</label>
                    <textarea name="description" class="input-1" placeholder="Description" required></textarea>
                </div>

                <!-- DUE DATE -->
                <div class="input-holder">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="input-1" required>
                </div>

                <!-- PRIORITY (NEW) -->
                <div class="input-holder">
                    <label>Priority</label>
                    <select name="priority" class="input-1" required>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>

                <!-- ASSIGNED TO -->
                <div class="input-holder">
                    <label>Assigned To</label>
                    <select name="assigned_to" class="input-1" required>
                        <option value="0">Select Employee</option>

                        <?php if ($users != 0) { 
                            foreach ($users as $user) { 
                        ?>
                            <option value="<?= $user['id'] ?>">
                                <?= $user['full_name'] ?>
                            </option>
                        <?php 
                            } 
                        } 
                        ?>

                    </select>
                </div>  

                <button class="edit-btn">Create Task</button>

            </form>

        </section>
    </div>

    <script type="text/javascript">
        var active = document.querySelector("#navlist li:nth-child(3)");
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