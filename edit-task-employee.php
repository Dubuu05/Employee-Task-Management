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

        <section class="section-1 edit-task-page">

           <div class="edit-task-container">


    <div class="edit-task-header">

        <div>
            <h1>Edit Task</h1>
            <p>Update task progress, upload files, and manage status.</p>
        </div>

        <a href="my_task.php" class="back-task-btn">
            <i class="fa fa-arrow-left"></i>
            Back to Tasks
        </a>

    </div>


    <div class="edit-task-card">

        <form class="modern-edit-form"
              method="POST"
              action="app/update-task-employee.php"
              enctype="multipart/form-data">

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


            <div class="task-preview-box">

                <div class="task-preview-item">
                    <label>Task Title</label>
                    <div class="preview-content">
                        <?= htmlspecialchars($task['title']) ?>
                    </div>
                </div>

                <div class="task-preview-item">
                    <label>Description</label>
                    <div class="preview-content description-box">
                        <?= htmlspecialchars($task['description']) ?>
                    </div>
                </div>

            </div>


            <input type="hidden"
                   name="remove_file_flag"
                   id="remove_file_flag"
                   value="0">


            <div class="upload-card">

                <div class="upload-top">
                    <h3>Attached File</h3>
                </div>

                <?php if (!empty($task['file_path'])) { ?>

                    <div id="current-file-display" class="current-file-box">

                        <a href="<?= htmlspecialchars($task['file_path']) ?>"
                           target="_blank">

                            <i class="fa fa-file"></i>

                            View / Download Current File

                        </a>

                    </div>

                <?php } else { ?>

                    <div class="empty-file">
                        No file attached yet.
                    </div>

                <?php } ?>


                <input type="file"
                       name="task_file"
                       id="task_file"
                       style="display:none;">


                <div class="upload-actions">

                    <label for="task_file" class="upload-btn">

                        <i class="fa fa-upload"></i>

                        Choose File

                    </label>

                    <button type="button"
                            id="remove-file"
                            class="remove-btn">

                        <i class="fa fa-trash"></i>

                        Remove File

                    </button>

                </div>

                <div id="file-name" class="selected-file">
                    No file chosen
                </div>

            </div>

    

            <div class="status-card">

                <label class="status-label">
                    Task Status
                </label>

                <div class="status-buttons">

                    <button type="button"
                        data-value="pending"
                        class="<?= ($task['status'] == 'pending') ? 'active' : '' ?>">

                        Pending

                    </button>

                    <button type="button"
                        data-value="in_progress"
                        class="<?= ($task['status'] == 'in_progress') ? 'active' : '' ?>">

                        In Progress

                    </button>

                    <button type="button"
                        data-value="completed"
                        class="<?= ($task['status'] == 'completed') ? 'active' : '' ?>">

                        Completed

                    </button>

                </div>

                <input type="hidden"
                       name="status"
                       id="statusInput"
                       value="<?= $task['status'] ?>">

            </div>


            <input type="hidden"
                   name="id"
                   value="<?= $task['id'] ?>">



            <button class="update-task-btn" type="submit">

                <i class="fa fa-save"></i>

                Update Task

            </button>

        </form>

    </div>

</div>
        </section>
    </div>

    <script>
        const buttons = document.querySelectorAll(".status-buttons button");
        const statusInput = document.getElementById("statusInput");

        buttons.forEach(btn => {
            btn.addEventListener("click", function () {

                buttons.forEach(b => b.classList.remove("active"));

                this.classList.add("active");

                statusInput.value = this.getAttribute("data-value");
            });
        });

        document.getElementById('task_file').addEventListener('change', function() {
            document.getElementById('file-name').textContent =
                this.files[0] ? this.files[0].name : "No file chosen";

            document.getElementById('remove_file_flag').value = "0";
        });

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