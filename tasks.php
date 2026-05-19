<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == 'admin') {

    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";

    $text = "All Tasks";

    $view_idle = false;
    $weekly_report = false;
    $monthly_report = false;

    $tasks = [];

    /* =========================================================
       FILTERS
    ========================================================= */

    if (isset($_GET['view']) && $_GET['view'] == "idle") {

        $text = "Idle Employees";
        $view_idle = true;

    } elseif (isset($_GET['view']) && $_GET['view'] == "weekly_report") {

        $text = "Weekly Report";
        $weekly_report = true;

    } elseif (isset($_GET['view']) && $_GET['view'] == "monthly_report") {

        $text = "Monthly Report";
        $monthly_report = true;

    } elseif (isset($_GET['due_date']) && $_GET['due_date'] == "Due Today") {

        $text = "Due Today";
        $tasks = get_all_tasks_due_today($conn);

    } elseif (isset($_GET['due_date']) && $_GET['due_date'] == "Overdue") {

        $text = "Overdue";
        $tasks = get_all_tasks_overdue($conn);

    } elseif (isset($_GET['due_date']) && $_GET['due_date'] == "No Deadline") {

        $text = "No Deadline";
        $tasks = get_all_tasks_no_deadline($conn);

    } else {

        $tasks = get_all_tasks($conn);
    }

    $all_users = get_all_users($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Futuristic Task Dashboard</title>

    <link rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet"
    href="css/style.css">

    <style>

    @media print {

        body * {
            visibility: hidden;
        }

        .print-area,
        .print-area * {
            visibility: visible;
        }

        .print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .no-print {
            display: none;
        }
    }

    </style>

</head>

<body>

<input type="checkbox" id="checkbox">

<?php include "inc/header.php"; ?>

<div class="body">

<?php include "inc/nav.php"; ?>

<section class="section-1">

    <!-- =========================================================
         FILTER BAR
    ========================================================= -->

    <div class="filter-bar no-print">

        <a href="create_task.php" class="filter-btn">
            <i class="fa fa-plus"></i>
            Create Task
        </a>

        <a href="tasks.php?due_date=Due Today" class="filter-btn">
            <i class="fa fa-clock-o"></i>
            Due Today
        </a>

        <a href="tasks.php?due_date=Overdue" class="filter-btn">
            <i class="fa fa-warning"></i>
            Overdue
        </a>

        <a href="tasks.php?due_date=No Deadline" class="filter-btn">
            <i class="fa fa-calendar-times-o"></i>
            No Deadline
        </a>

        <a href="tasks.php" class="filter-btn">
            <i class="fa fa-tasks"></i>
            All Tasks
        </a>

        <a href="tasks.php?view=idle" class="filter-btn">
            <i class="fa fa-user-times"></i>
            Idle Employees
        </a>

        <a href="tasks.php?view=weekly_report" class="filter-btn">
            <i class="fa fa-line-chart"></i>
            Weekly Report
        </a>

        <a href="tasks.php?view=monthly_report" class="filter-btn">
            <i class="fa fa-bar-chart"></i>
            Monthly Report
        </a>

    </div>

    <!-- =========================================================
         PAGE TITLE
    ========================================================= -->

    <h2 class="title-2">
        <?= $text ?>
    </h2>

    <?php if ($view_idle) { ?>

    <!-- =========================================================
         IDLE EMPLOYEES
    ========================================================= -->

    <div class="table-container">

        <table class="main-table">

            <tr>

                <th>#</th>
                <th>Employee Name</th>
                <th>Status</th>

            </tr>

            <?php if (!empty($all_users) && is_array($all_users)) { ?>

            <?php $i = 0; ?>

            <?php foreach ($all_users as $user) { ?>

            <?php

            $sql = "
                SELECT COUNT(*) as total
                FROM tasks
                WHERE assigned_to = ?
                AND status != 'Completed'
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$user['id']]);

            $res = $stmt->fetch();

            if ($res['total'] == 0) {

            ?>

            <tr>

                <td><?= ++$i ?></td>

                <td>
                    <?= htmlspecialchars($user['full_name']) ?>
                </td>

                <td>
                    <span class="status-completed">
                        Idle
                    </span>
                </td>

            </tr>

            <?php } ?>

            <?php } ?>

            <?php } ?>

        </table>

    </div>

    <?php } elseif ($weekly_report || $monthly_report) { ?>

    <!-- =========================================================
         REPORT SECTION
    ========================================================= -->

    <div class="print-area">

        <div class="table-container report-card"
        style="padding:25px;">

            <?php

            if ($weekly_report) {

                $start = date('Y-m-d', strtotime('-7 days'));
                $end = date('Y-m-d');

            } else {

                $start = date('Y-m-01');
                $end = date('Y-m-t');
            }

            $stmt = $conn->prepare("
                SELECT COUNT(*) as total
                FROM tasks
                WHERE due_date BETWEEN ? AND ?
            ");

            $stmt->execute([$start, $end]);

            $total = $stmt->fetch()['total'];

            $stmt = $conn->prepare("
                SELECT COUNT(*) as total
                FROM tasks
                WHERE status='Completed'
                AND due_date BETWEEN ? AND ?
            ");

            $stmt->execute([$start, $end]);

            $completed = $stmt->fetch()['total'];

            $pending = $total - $completed;

            ?>

            <!-- =========================================================
                 REPORT STATS
            ========================================================= -->

            <div class="report-stats">

                <div class="stat-box">

                    <span>Total Tasks</span>

                    <h3><?= $total ?></h3>

                </div>

                <div class="stat-box">

                    <span>Completed</span>

                    <h3><?= $completed ?></h3>

                </div>

                <div class="stat-box">

                    <span>Pending</span>

                    <h3><?= $pending ?></h3>

                </div>

            </div>

            <!-- =========================================================
                 REPORT TABLE
            ========================================================= -->

            <table class="main-table">

                <tr>

                    <th>Employee</th>
                    <th>Total</th>
                    <th>Completed</th>
                    <th>Pending</th>

                </tr>

                <?php foreach ($all_users as $user) { ?>

                <?php

                $stmt = $conn->prepare("
                    SELECT
                        COUNT(*) as total,
                        SUM(status='Completed') as completed
                    FROM tasks
                    WHERE assigned_to=?
                    AND due_date BETWEEN ? AND ?
                ");

                $stmt->execute([
                    $user['id'],
                    $start,
                    $end
                ]);

                $row = $stmt->fetch();

                $t = $row['total'] ?? 0;
                $c = $row['completed'] ?? 0;
                $p = $t - $c;

                ?>

                <tr>

                    <td>
                        <?= htmlspecialchars($user['full_name']) ?>
                    </td>

                    <td><?= $t ?></td>

                    <td><?= $c ?></td>

                    <td><?= $p ?></td>

                </tr>

                <?php } ?>

            </table>

            <br>

            <button onclick="window.print()"
            class="edit-btn no-print">

                <i class="fa fa-print"></i>
                Print

            </button>

            <a href="download_report.php?type=<?= $weekly_report ? 'weekly' : 'monthly' ?>"
            class="edit-btn no-print">

                <i class="fa fa-download"></i>
                Download

            </a>

        </div>

    </div>

    <?php } else { ?>

    <!-- =========================================================
         TASK TABLE
    ========================================================= -->

    <div class="table-container">

        <table class="main-table">

            <tr>

                <th>#</th>
                <th>Title</th>
                <th>Description</th>
                <th>Priority</th>
                <th>Assigned To</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>File</th>
                <th>Action</th>

            </tr>

            <?php if (!empty($tasks) && is_array($tasks)) { ?>

            <?php $i = 0; ?>

            <?php foreach ($tasks as $task) { ?>

            <tr>

                <td><?= ++$i ?></td>

                <!-- TITLE -->

                <td>
                    <?= htmlspecialchars($task['title']) ?>
                </td>

                <!-- DESCRIPTION -->

                <td>
                    <?= htmlspecialchars($task['description']) ?>
                </td>

                <!-- PRIORITY -->

                <td>

                    <?php

                    if($task['priority'] == 'High'){

                        echo "<span class='priority-high'>High</span>";

                    }

                    elseif($task['priority'] == 'Medium'){

                        echo "<span class='priority-medium'>Medium</span>";

                    }

                    else{

                        echo "<span class='priority-low'>Low</span>";

                    }

                    ?>

                </td>

                <!-- ASSIGNED TO -->

                <td>

                    <?php

                    foreach($all_users as $user){

                        if($user['id'] == $task['assigned_to']){

                            echo htmlspecialchars($user['full_name']);
                        }
                    }

                    ?>

                </td>

                <!-- DUE DATE -->

                <td>

                    <?= empty($task['due_date'])
                    ? "No Deadline"
                    : $task['due_date']; ?>

                </td>

                <!-- STATUS -->

                <td>

                    <?php

                    if(strtolower($task['status']) == 'completed'){

                        echo "<span class='status-completed'>Completed</span>";

                    }

                    elseif(strtolower($task['status']) == 'pending'){

                        echo "<span class='status-pending'>Pending</span>";

                    }

                    else{

                        echo "<span class='status-progress'>In Progress</span>";

                    }

                    ?>

                </td>

                <!-- FILE -->

                <td>

                    <?php if (!empty($task['file_path'])) { ?>

                    <a href="download.php?file=<?= urlencode(basename($task['file_path'])) ?>"
                    class="edit-btn">

                        <i class="fa fa-download"></i>
                        Download

                    </a>

                    <?php } else { ?>

                    <span class="no-file">
                        No File
                    </span>

                    <?php } ?>

                </td>

                <!-- ACTION -->

                <td>

                    <a href="edit-task.php?id=<?= $task['id'] ?>"
                    class="edit-btn">

                        <i class="fa fa-pencil"></i>
                        Edit

                    </a>

                    <a href="delete-task.php?id=<?= $task['id'] ?>"
                    class="delete-btn">

                        <i class="fa fa-trash"></i>
                        Delete

                    </a>

                </td>

            </tr>

            <?php } ?>

            <?php } ?>

        </table>

    </div>

    <?php } ?>

</section>

</div>

</body>
<script type="text/javascript">
        var active = document.querySelector("#navlist li:nth-child(4)");
        if(active) active.classList.add("active");
    </script>
</html>

<?php

} else {

    header("Location: login.php?error=First login");
    exit();
}

?>