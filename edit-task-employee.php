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
        <?php } 
            ?>
        <?php if (isset($_GET['success'])) { ?> 
        <div class="success" role="alert">
            <?php echo stripslashes($_GET['success']); ?>
        </div>
        <?php }  ?>
                <div class="input-holder">
                    <lable></lable>
                    <p><b>Title:</b> <?=$task['title']?></p>
                </div>
                <div class="input-holder">
                    <lable></lable>
                    <p><b>Description:</b> <?=$task['description']?></p>
                </div><br>
                <div class="input-holder">
    <label>Upload File</label><br>
    <!-- Hidden file input -->
    <input type="file" name="task_file" id="task_file" style="display:none;">
    <!-- Styled button label -->
    <label for="task_file" class="edit-btn" style="cursor:pointer; display:inline-block; margin-top:5px;">
        Choose File
    </label>

<button type="button" id="remove-file" class="edit-btn" 
        style="margin-left:10px; background:#dc3545;">
    Remove File
</button>


    <span id="file-name" style="margin-left:10px; font-style:italic;">No file chosen</span>
</div><br>
                    <div class="input-holder">
                    <label>Status</label>
                    <select name="status" class="input-1">
                        <option <?php if ($task['status'] == 'pending') echo 'selected'; ?>>pending</option>
                        <option <?php if ($task['status'] == 'in_progress') echo 'selected'; ?>>in_progress</option>
                        <option <?php if ($task['status'] == 'completed') echo 'selected'; ?>>completed</option>
                    </select><br>
                </div>  
                <input type="text" name="id" value="<?=$task['id']?>" hidden>

                <button class="edit-btn">Update</button>
       </form>
            
        </section>
    </div>
    <script type="text/javascript">
        var active = document.querySelector("#navlist li:nth-child(2)");
        active.classList.add("active");
    </script>
    <script>
document.getElementById('task_file').addEventListener('change', function() {
    var fileName = this.files[0] ? this.files[0].name : "No file chosen";
    document.getElementById('file-name').textContent = fileName;
});
</script>

<script>
document.getElementById('remove-file').addEventListener('click', function() {
    var fileInput = document.getElementById('task_file');
    
    fileInput.value = "";
    document.getElementById('file-name').textContent = "No file chosen";
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