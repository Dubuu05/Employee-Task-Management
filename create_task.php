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

    <div class="create-task-container">

        <!-- HEADER -->
        <div class="create-task-header">

            <div>
                <h1>Create Task</h1>
                <p>Create and assign tasks to employees easily.</p>
            </div>

            <div class="header-icon">
                <i class="fa fa-tasks"></i>
            </div>

        </div>

        <!-- FORM CARD -->
        <div class="task-card">

            <form class="modern-form" method="POST" action="app/add-task.php">

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
                <div class="form-group">
                    <label>
                        <i class="fa fa-pencil"></i>
                        Task Title
                    </label>

                    <input 
                        type="text" 
                        name="title" 
                        class="modern-input" 
                        placeholder="Enter task title..."
                        required
                    >
                </div>

                <!-- DESCRIPTION -->
                <div class="form-group">
                    <label>
                        <i class="fa fa-align-left"></i>
                        Description
                    </label>

                    <textarea 
                        name="description" 
                        class="modern-input textarea" 
                        placeholder="Write task details..."
                        required
                    ></textarea>
                </div>

                <!-- GRID -->
                <div class="form-grid">

                    <!-- DUE DATE -->
                    <div class="form-group">
                        <label>
                            <i class="fa fa-calendar"></i>
                            Due Date
                        </label>

                        <input 
                            type="date" 
                            name="due_date" 
                            class="modern-input"
                            required
                        >
                    </div>

                    <!-- PRIORITY -->
                    <div class="form-group">
                        <label>
                            <i class="fa fa-flag"></i>
                            Priority
                        </label>

                        <select name="priority" class="modern-input" required>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>

                </div>

                <!-- ASSIGNED TO -->
                <div class="form-group">
                    <label>
                        <i class="fa fa-user"></i>
                        Assign Employee
                    </label>

                    <select name="assigned_to" class="modern-input" required>

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

                <!-- BUTTON -->
                <button class="create-task-btn">

                    <i class="fa fa-plus-circle"></i>

                    Create Task

                </button>

            </form>

        </div>

    </div>

</section>

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