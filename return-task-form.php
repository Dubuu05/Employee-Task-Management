<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == 'admin') {
    include "DB_connection.php";
    include "app/Model/Task.php";

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
    <title>Return Task</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .textarea-1 {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: inherit;
            resize: vertical;
        }
        .cancel-btn {
            background: #6c757d;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-left: 10px;
        }
        .cancel-btn:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <h4 class= "title">Return Task to Employee</h4>
            
            <form class="form-1" method="POST" action="app/return-task.php">
                
                <div class="input-holder">
                    <p><b>Task Title:</b> <?= htmlspecialchars($task['title']) ?></p>
                </div>
                
                <div class="input-holder">
                    <p><b>Submitted File:</b> 
                        <?php if (!empty($task['file_path'])) { ?>
                            <a href="<?= htmlspecialchars($task['file_path']) ?>" target="_blank" style="color: #17a2b8; text-decoration: underline;">Review File</a>
                        <?php } else { ?>
                            <i>No file attached</i>
                        <?php } ?>
                    </p>
                </div>

                <div class="input-holder">
                    <label><b>Reason for Return / Comments:</b></label>
                    <textarea name="comment" class="textarea-1" rows="5" placeholder="Explain what the employee needs to fix..." required></textarea>
                </div><br>
                
                <input type="hidden" name="id" value="<?= $task['id'] ?>">

                <button type="submit" class="delete-btn" style="padding: 10px 15px; border:none; cursor:pointer;">Return Task</button>
                <a href="tasks.php" class="cancel-btn">Cancel</a>
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