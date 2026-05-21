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
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .form-card {
            width: 100%;
            max-width: 600px;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .textarea-1 {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-family: inherit;
            resize: vertical;
        }

        .file-link {
            color: #17a2b8;
            text-decoration: underline;
        }

        .file-link:hover {
            opacity: 0.8;
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .title {
            margin-bottom: 20px;
        }

        .input-holder p {
            margin: 5px 0 0;
        }
    </style>
</head>

<body>
<input type="checkbox" id="checkbox">

<?php include "inc/header.php" ?>

<div class="body">
    <?php include "inc/nav.php" ?>

    <section class="section-1">

        <div class="form-container">

            <div class="form-card">

                <h4 class="title">
                    <i class="fa fa-undo"></i> Return Task to Employee
                </h4>

                <form class="form-1" method="POST" action="app/return-task.php">

                    <div class="input-holder">
                        <label>Task Title</label>
                        <p><?= htmlspecialchars($task['title']) ?></p>
                    </div>

                    <div class="input-holder">
                        <label>Submitted File</label>
                        <p>
                            <?php if (!empty($task['file_path'])) { ?>
                                <a href="<?= htmlspecialchars($task['file_path']) ?>" target="_blank" class="file-link">
                                    Review File
                                </a>
                            <?php } else { ?>
                                <i>No file attached</i>
                            <?php } ?>
                        </p>
                    </div>

                    <div class="input-holder">
                        <label>Reason for Return / Comments</label>
                        <textarea name="comment" class="textarea-1" rows="6"
                            placeholder="Explain what the employee needs to fix..." required></textarea>
                    </div>

                    <input type="hidden" name="id" value="<?= $task['id'] ?>">

                    <button type="submit" class="edit-btn">
                        <i class="fa fa-reply"></i> Return Task
                    </button>

                    <a href="tasks.php" class="btn-secondary">Cancel</a>

                </form>

            </div>

        </div>

    </section>
</div>

<script>
    var active = document.querySelector("#navlist li:nth-child(4)");
    if (active) active.classList.add("active");
</script>

</body>
</html>

<?php  
} else { 
    header("Location: login.php?error=First login");
    exit(); 
}
?>