<?php
session_start();


if (!isset($_SESSION['role']) || !isset($_SESSION['id'])) {
    header("Location: login.php?error=First login");
    exit();
}


include_once "DB_connection.php";
include_once "app/Model/Task.php";
include_once "app/Model/User.php";


$num_my_task = 0;
$overdue_task = 0;
$no_deadline_task = 0;
$pending = 0;
$in_progress = 0;
$completed = 0;

$num_task = 0;
$num_users = 0;
$todaydue_task = 0;


function safe_call($fn, $conn, $id = null) {
    if (function_exists($fn)) {
        return ($id !== null) ? $fn($conn, $id) : $fn($conn);
    }
    return 0;
}


if ($_SESSION['role'] === "admin") {

    $todaydue_task   = safe_call("count_tasks_due_today", $conn);
    $overdue_task    = safe_call("count_tasks_overdue", $conn);
    $no_deadline_task= safe_call("count_tasks_no_deadline", $conn);
    $num_task        = safe_call("count_tasks", $conn);
    $num_users       = safe_call("count_users", $conn);
    $pending         = safe_call("count_pending_tasks", $conn);
    $in_progress     = safe_call("count_in_progress_tasks", $conn);
    $completed       = safe_call("count_completed_tasks", $conn);

} else {

    $id = $_SESSION['id'];

    $num_my_task      = safe_call("count_my_tasks", $conn, $id);
    $overdue_task     = safe_call("count_my_tasks_overdue", $conn, $id);
    $no_deadline_task = safe_call("count_my_tasks_no_deadline", $conn, $id);
    $pending          = safe_call("count_my_pending_tasks", $conn, $id);
    $in_progress      = safe_call("count_my_in_progress_tasks", $conn, $id);
    $completed        = safe_call("count_my_completed_tasks", $conn, $id);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<input type="checkbox" id="checkbox">

<?php include "inc/header.php"; ?>

<div class="body">

<?php include "inc/nav.php"; ?>

<section class="section-1">

<?php if ($_SESSION['role'] !== "admin") { ?>

<!-- USER DASHBOARD -->
<div class="dashboard">

    <div class="dashboard-item card-blue">
        <i class="fa fa-tasks"></i>
        <h3>My Tasks</h3>
        <span><?= $num_my_task ?></span>
    </div>

    <div class="dashboard-item card-pink">
        <i class="fa fa-exclamation-circle"></i>
        <h3>Overdue</h3>
        <span><?= $overdue_task ?></span>
    </div>

    <div class="dashboard-item card-purple">
        <i class="fa fa-calendar"></i>
        <h3>No Deadline</h3>
        <span><?= $no_deadline_task ?></span>
    </div>

    <div class="dashboard-item card-orange">
        <h3>Pending</h3>
        <span><?= $pending ?></span>
    </div>

    <div class="dashboard-item card-cyan">
        <h3>In Progress</h3>
        <span><?= $in_progress ?></span>
    </div>

    <div class="dashboard-item card-green">
        <h3>Completed</h3>
        <span><?= $completed ?></span>
    </div>

</div>

<?php } else { ?>

<!-- ADMIN DASHBOARD -->
<div class="dashboard">

    <div class="dashboard-item card-blue">
        <h3>Employees</h3>
        <span><?= $num_users ?></span>
    </div>

    <div class="dashboard-item card-pink">
        <h3>All Tasks</h3>
        <span><?= $num_task ?></span>
    </div>

    <div class="dashboard-item card-purple">
        <h3>Due Today</h3>
        <span><?= $todaydue_task ?></span>
    </div>

    <div class="dashboard-item card-orange">
        <h3>Pending</h3>
        <span><?= $pending ?></span>
    </div>

    <div class="dashboard-item card-cyan">
        <h3>In Progress</h3>
        <span><?= $in_progress ?></span>
    </div>

    <div class="dashboard-item card-green">
        <h3>Completed</h3>
        <span><?= $completed ?></span>
    </div>

</div>

<?php } ?>

</section>
</div>

<script>
let active = document.querySelector("#navlist li:nth-child(1)");
if (active) active.classList.add("active");
</script>

</body>
</html>