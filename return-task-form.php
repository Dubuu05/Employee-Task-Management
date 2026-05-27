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

    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

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
            <h1>Return Task</h1>
            <p>Send task back to employee for revision.</p>
        </div>

        <div class="header-icon">
            <i class="fa fa-reply"></i>
        </div>

    </div>

    <!-- CARD -->
    <div class="task-card">

        <form class="modern-form"
              method="POST"
              action="app/return-task.php">

            <?php if (isset($_GET['error'])) { ?>
                <div class="danger">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php } ?>

            <?php if (isset($_GET['success'])) { ?>
                <div class="success">
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php } ?>

        
            <div class="form-group">
                <label>
                    <i class="fa fa-tasks"></i>
                    Task Title
                </label>

                <input type="text"
                       class="modern-input"
                       value="<?= htmlspecialchars($task['title']) ?>"
                       readonly>
            </div>

     
            <div class="form-group">
                <label>
                    <i class="fa fa-file"></i>
                    Submitted File
                </label>

                <?php if (!empty($task['file_path'])) { ?>

                    <a href="<?= htmlspecialchars($task['file_path']) ?>"
                       target="_blank"
                       class="file-link">
                        <i class="fa fa-eye"></i>
                        Review File
                    </a>

                <?php } else { ?>

                    <input type="text"
                           class="modern-input"
                           value="No file attached"
                           readonly>

                <?php } ?>
            </div>

         
            <div class="form-group">
                <label>
                    <i class="fa fa-comment"></i>
                    Return Comment
                </label>

                <textarea
                    name="comment"
                    class="modern-input textarea"
                    rows="6"
                    placeholder="Explain what needs to be revised..."
                    required></textarea>
            </div>

            <input type="hidden"
                   name="id"
                   value="<?= $task['id'] ?>">

        
            <div style="display:flex; gap:12px; margin-top:20px;">

                <button type="submit" class="create-task-btn">
                    <i class="fa fa-reply"></i>
                    Return Task
                </button>

            </div>

        </form>

    </div>

</div>

</section>

</div>

<script>
var active = document.querySelector("#navlist li:nth-child(4)");
if (active) {
    active.classList.add("active");
}
</script>

</body>
</html>

<?php
} else {
    header("Location: login.php?error=First login");
    exit();
}
?>