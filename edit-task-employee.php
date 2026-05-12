<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == 'employee') {

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
                <a href="my_task.php">Tasks</a>
            </h4>

            <form class="form-1" method="POST" action="app/update-task-employee.php" enctype="multipart/form-data">

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
                    <p><b>Title:</b> <?=$task['title']?></p>
                </div>

                <!-- DESCRIPTION -->
                <div class="input-holder">
                    <p><b>Description:</b> <?=$task['description']?></p>
                </div><br>

                <!-- FILE REMOVE FLAG -->
                <input type="hidden" name="remove_file_flag" id="remove_file_flag" value="0">

                <!-- CURRENT FILE -->
                <div class="input-holder">
                    <label><b>Current Attached File:</b></label>

                    <?php if (!empty($task['file_path'])) { ?>
                        <p id="current-file-display" style="margin-top:5px;margin-bottom:15px;">
                            <a href="<?= htmlspecialchars($task['file_path']) ?>" target="_blank" style="color:#17a2b8;font-weight:bold;">
                                <i class="fa fa-file"></i> View/Download File
                            </a>
                        </p>
                    <?php } else { ?>
                        <p style="font-style:italic;color:#888;">No file attached yet.</p>
                    <?php } ?>
                </div>

                <!-- UPLOAD FILE -->
                <div class="input-holder">
                    <label><b>Upload New File</b></label><br>

                    <input type="file" name="task_file" id="task_file" style="display:none;">

                    <label for="task_file" class="edit-btn" style="cursor:pointer;">
                        Choose File
                    </label>

                    <button type="button" id="remove-file" class="edit-btn" style="background:#dc3545;">
                        Remove File
                    </button>

                    <span id="file-name" style="margin-left:10px;font-style:italic;">No file chosen</span>
                </div><br>

                <!-- STATUS BUTTONS -->
                <div class="input-holder">
                    <label><b>Choose Status for this Task</b></label>

                    <div class="status-buttons">

                        <button type="button" data-value="pending"
                            class="<?= ($task['status'] == 'pending') ? 'active' : '' ?>">
                            Pending
                        </button>

                        <button type="button" data-value="in_progress"
                            class="<?= ($task['status'] == 'in_progress') ? 'active' : '' ?>">
                            In Progress
                        </button>

                        <button type="button" data-value="completed"
                            class="<?= ($task['status'] == 'completed') ? 'active' : '' ?>">
                            Completed
                        </button>

                    </div>

                    <input type="hidden" name="status" id="statusInput" value="<?= $task['status'] ?>">
                </div><br>

                <!-- TASK ID -->
                <input type="hidden" name="id" value="<?= $task['id'] ?>">

                <button class="edit-btn" type="submit">Update</button>

            </form>
        </section>
    </div>

    <script>
        // STATUS BUTTON LOGIC
        const buttons = document.querySelectorAll(".status-buttons button");
        const statusInput = document.getElementById("statusInput");

        buttons.forEach(btn => {
            btn.addEventListener("click", function () {

                buttons.forEach(b => b.classList.remove("active"));

                this.classList.add("active");

                statusInput.value = this.getAttribute("data-value");
            });
        });

        // FILE INPUT DISPLAY
        document.getElementById('task_file').addEventListener('change', function() {
            document.getElementById('file-name').textContent =
                this.files[0] ? this.files[0].name : "No file chosen";

            document.getElementById('remove_file_flag').value = "0";
        });

        // REMOVE FILE
        document.getElementById('remove-file').addEventListener('click', function() {

            document.getElementById('task_file').value = "";
            document.getElementById('file-name').textContent = "No file chosen";
            document.getElementById('remove_file_flag').value = "1";

            let current = document.getElementById('current-file-display');
            if (current) current.style.display = "none";
        });
    </script>

    <script>
        var active = document.querySelector("#navlist li:nth-child(2)");
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