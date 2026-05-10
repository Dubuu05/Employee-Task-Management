<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";

    if ($_SESSION['role'] == "admin") {

        $todaydue_task = count_tasks_due_today($conn);
        $overdue_task = count_tasks_overdue($conn);
        $no_deadline_task = count_tasks_no_deadline($conn);
        $num_task = count_tasks($conn);
        $num_users = count_users($conn);
        $pending = count_pending_tasks($conn);
        $in_progress = count_in_progress_tasks($conn);
        $completed = count_completed_tasks($conn);

    } else {

        $num_my_task = count_my_tasks($conn, $_SESSION['id']);
        $overdue_task = count_my_tasks_overdue($conn, $_SESSION['id']);
        $no_deadline_task = count_my_tasks_no_deadline($conn, $_SESSION['id']);
        $pending = count_my_pending_tasks($conn, $_SESSION['id']);
        $in_progress = count_my_in_progress_tasks($conn, $_SESSION['id']);
        $completed = count_my_completed_tasks($conn, $_SESSION['id']);
    }
?>

<!DOCTYPE html>
<html>
<head>

    <title>Dashboard</title>

    <link rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<input type="checkbox" id="checkbox">

<?php include "inc/header.php" ?>

<div class="body">

<?php include "inc/nav.php" ?>

<section class="section-1">

<!-- =========================
     USER DASHBOARD
========================= -->

<?php if ($_SESSION['role'] != "admin") { ?>

<div class="dashboard">

    <!-- MY TASKS -->

    <div class="dashboard-item card-blue">

        <i class="fa fa-tasks"></i>

        <h3>My Tasks</h3>

        <span><?=$num_my_task?></span>

        <div class="card-preview">
            • Finish dashboard UI <br>
            • Submit documentation <br>
            • Update database
        </div>

    </div>

    <!-- OVERDUE -->

    <div class="dashboard-item card-pink">

        <i class="fa fa-exclamation-circle"></i>

        <h3>Overdue</h3>

        <span><?=$overdue_task?></span>

        <div class="card-preview">

            <?php if($overdue_task > 0){ ?>

                • Science Project <br>
                • Research Paper

            <?php } else { ?>

                No overdue tasks 🎉

            <?php } ?>

        </div>

    </div>

    <!-- DEADLINES -->

    <div class="dashboard-item card-purple">

        <i class="fa fa-calendar"></i>

        <h3>Deadlines</h3>

        <span><?=$no_deadline_task?></span>

        <div class="card-preview">
            📅 May 10 - Database <br>
            📅 May 14 - Research
        </div>

    </div>

    <!-- PENDING -->

    <div class="dashboard-item card-orange">

        <i class="fa fa-square-o"></i>

        <h3>Pending</h3>

        <span><?=$pending?></span>

        <div class="card-preview">
            Assignments waiting for completion.
        </div>

    </div>

    <!-- IN PROGRESS -->

    <div class="dashboard-item card-cyan">

        <i class="fa fa-spinner"></i>

        <h3>In Progress</h3>

        <span><?=$in_progress?></span>

        <div class="card-preview">
            Productivity currently active.
        </div>

    </div>

    <!-- COMPLETED -->

    <div class="dashboard-item card-green">

        <i class="fa fa-check-square-o"></i>

        <h3>Completed</h3>

        <span><?=$completed?></span>

        <div class="card-preview">
            Great job completing your tasks!
        </div>

    </div>

</div>

<!-- =========================
     ADMIN DASHBOARD
========================= -->

<?php } else { ?>

<div class="dashboard">

    <div class="dashboard-item card-blue">

        <i class="fa fa-users"></i>

        <h3>Employees</h3>

        <span><?=$num_users?></span>

    </div>

    <div class="dashboard-item card-pink">

        <i class="fa fa-tasks"></i>

        <h3>All Tasks</h3>

        <span><?=$num_task?></span>

    </div>

    <div class="dashboard-item card-purple">

        <i class="fa fa-calendar"></i>

        <h3>Due Today</h3>

        <span><?=$todaydue_task?></span>

    </div>

    <div class="dashboard-item card-orange">

        <i class="fa fa-square-o"></i>

        <h3>Pending</h3>

        <span><?=$pending?></span>

    </div>

    <div class="dashboard-item card-cyan">

        <i class="fa fa-spinner"></i>

        <h3>In Progress</h3>

        <span><?=$in_progress?></span>

    </div>

    <div class="dashboard-item card-green">

        <i class="fa fa-check-square-o"></i>

        <h3>Completed</h3>

        <span><?=$completed?></span>

    </div>

</div>

<?php } ?>

</section>

</div>

<script type="text/javascript">

    var active =
    document.querySelector("#navlist li:nth-child(1)");

    active.classList.add("active");

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