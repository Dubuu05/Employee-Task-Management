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
            <h4 class= "title">Edit Task <a href="my_task.php">Tasks</a></h4>
            
            <form class="form-1" method="POST" action="app/update-task-employee.php" enctype="multipart/form-data">
                
                <?php if (isset($_GET['error'])) { ?> 
                    <div class="danger" role="alert">
                        <?php echo stripslashes($_GET['error']); ?>
                    </div>
                <?php } ?>
                
                <?php if (isset($_GET['success'])) { ?> 
                    <div class="success" role="alert">
                        <?php echo stripslashes($_GET['success']); ?>
                    </div>
                <?php } ?>
                
                <div class="input-holder">
                    <p><b>Title:</b> <?=$task['title']?></p>
                </div>
                
                <div class="input-holder">
                    <p><b>Description:</b> <?=$task['description']?></p>
                </div><br>

                <input type="hidden" name="remove_file_flag" id="remove_file_flag" value="0">

                <div class="input-holder">
                    <label><b>Current Attached File:</b></label>
                    <?php if (!empty($task['file_path'])) { ?>
                        <p id="current-file-display" style="margin-top: 5px; margin-bottom: 15px;">
                            <a href="<?= htmlspecialchars($task['file_path']) ?>" target="_blank" style="color: #17a2b8; text-decoration: underline; font-weight: bold;">
                                <i class="fa fa-file"></i> View/Download Current File
                            </a>
                        </p>
                    <?php } else { ?>
                        <p style="margin-top: 5px; margin-bottom: 15px; font-style: italic; color: #888;">
                            No file attached yet.
                        </p>
                    <?php } ?>
                </div>

                <div class="input-holder">
                    <label><b>Upload New File</b> (Leaves current file if empty)</label><br>
                    <input type="file" name="task_file" id="task_file" style="display:none;">
                    
                    <label for="task_file" class="edit-btn" style="cursor:pointer; display:inline-block; margin-top:5px;">
                        Choose New File
                    </label>

                    <button type="button" id="remove-file" class="edit-btn" style="margin-left:10px; background:#dc3545;">
                        Remove File
                    </button>

                    <span id="file-name" style="margin-left:10px; font-style:italic;">No file chosen</span>
                </div><br>
                <div class="input-holder">
                    <label><b>Status</b></label>
                    <select name="status" class="input-1">
                        <option value="pending" <?php if ($task['status'] == 'pending') echo 'selected'; ?>>pending</option>
                        <option value="in_progress" <?php if ($task['status'] == 'in_progress') echo 'selected'; ?>>in_progress</option>
                        <option value="completed" <?php if ($task['status'] == 'completed') echo 'selected'; ?>>completed</option>
                    </select>
                </div><br>  
                
                <input type="text" name="id" value="<?=$task['id']?>" hidden>

                <button class="edit-btn">Update</button>
           </form>
            
        </section>
    </div>
    
    <script type="text/javascript">
        var active = document.querySelector("#navlist li:nth-child(2)");
        if(active) active.classList.add("active");
    </script>
    
    <script>
        // When they choose a new file
        document.getElementById('task_file').addEventListener('change', function() {
            var fileName = this.files[0] ? this.files[0].name : "No file chosen";
            document.getElementById('file-name').textContent = fileName;
            
            // If they pick a new file, cancel the "remove old file" command
            document.getElementById('remove_file_flag').value = "0"; 
        });

        // When they click "Remove File" (Undo attachment)
        document.getElementById('remove-file').addEventListener('click', function() {
            var fileInput = document.getElementById('task_file');
            fileInput.value = "";
            document.getElementById('file-name').textContent = "No file chosen";
            
            // 1. Tell the backend to delete the existing file from the database
            document.getElementById('remove_file_flag').value = "1";
            
            // 2. Visually hide the "Current Attached File" link so they know it worked
            var currentFileDiv = document.getElementById('current-file-display');
            if(currentFileDiv) {
                currentFileDiv.style.display = 'none';
            }
        });
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