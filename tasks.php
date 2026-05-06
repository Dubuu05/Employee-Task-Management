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

    // FIX: prevent foreach error
    $tasks = [];

    // =========================
    // FILTERS
    // =========================

    if (isset($_GET['view']) && $_GET['view'] == "idle") {

        $text = "Idle Employees";
        $view_idle = true;
        $users = get_all_users($conn);

    } elseif (isset($_GET['view']) && $_GET['view'] == "weekly_report") {

        $text = "Weekly Report";
        $weekly_report = true;

    } elseif (isset($_GET['view']) && $_GET['view'] == "monthly_report") {

        $text = "Monthly Report";
        $monthly_report = true;

    } elseif (isset($_GET['due_date']) && $_GET['due_date'] == "Due Today") {

        $text = "Due Today";
        $tasks = get_all_tasks_due_today($conn); 
        $num_task = count_tasks_due_today($conn);

    } elseif (isset($_GET['due_date']) && $_GET['due_date'] == "Overdue") {

        $text = "Overdue";
        $tasks = get_all_tasks_overdue($conn); 
        $num_task = count_tasks_overdue($conn);

    } elseif (isset($_GET['due_date']) && $_GET['due_date'] == "No Deadline") {

        $text = "No Deadline";
        $tasks = get_all_tasks_no_deadline($conn); 
        $num_task = count_tasks_no_deadline($conn);

    } else {

        $tasks = get_all_tasks($conn); 
        $num_task = count_tasks($conn);
    }

    $all_users = get_all_users($conn);

?>
<!DOCTYPE html>
<html>
<head>
    <title>All Tasks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
    @media print {
        body * { visibility: hidden; }
        .print-area, .print-area * { visibility: visible; }
        .print-area { position: absolute; left:0; top:0; width:100%; }
        .no-print { display:none; }
    }
    </style>

</head>
<body>

<input type="checkbox" id="checkbox">
<?php include "inc/header.php"; ?>

<div class="body">
<?php include "inc/nav.php"; ?>

<section class="section-1">

    <h4 class="title-2 no-print">
        <a href="create_task.php" class="btn">Create Task</a>
        <a href="tasks.php?due_date=Due Today">Due Today</a>
        <a href="tasks.php?due_date=Overdue">Overdue</a>
        <a href="tasks.php?due_date=No Deadline">No Deadline</a>
        <a href="tasks.php">All Tasks</a>
        <a href="tasks.php?view=idle">Employees No Active Tasks</a>
        <a href="tasks.php?view=weekly_report">Weekly Report</a>
        <a href="tasks.php?view=monthly_report">Monthly Report</a>
    </h4>

    <h4 class="title-2"><?= $text ?></h4>

    <?php if ($view_idle) { ?>

        <!-- IDLE -->
        <table class="main-table">
            <tr>
                <th>#</th>
                <th>Employee Name</th>
                <th>Status</th>
            </tr>

            <?php if (!empty($all_users) && is_array($all_users)) { ?>
                <?php $i = 0; foreach ($all_users as $user) { ?>

                    <?php
                        $sql = "SELECT COUNT(*) as total 
                                FROM tasks 
                                WHERE assigned_to = ? 
                                AND status != 'Completed'";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$user['id']]);
                        $res = $stmt->fetch();

                        if ($res['total'] == 0) {
                    ?>

                    <tr>
                        <td><?= ++$i ?></td>
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                        <td>Idle</td>
                    </tr>

                    <?php } ?>

                <?php } ?>
            <?php } ?>
        </table>

    <?php } elseif ($weekly_report || $monthly_report) { ?>

        <!-- REPORT -->
        <div class="print-area">

            <h4 class="title-2">
                <?= $weekly_report ? "Weekly Report" : "Monthly Report" ?>
            </h4>

            <?php
                if ($weekly_report) {
                    $start = date('Y-m-d', strtotime('-7 days'));
                    $end = date('Y-m-d');
                } else {
                    $start = date('Y-m-01');
                    $end = date('Y-m-t');
                }

                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM tasks WHERE due_date BETWEEN ? AND ?");
                $stmt->execute([$start, $end]);
                $total = $stmt->fetch()['total'];

                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM tasks WHERE status='Completed' AND due_date BETWEEN ? AND ?");
                $stmt->execute([$start, $end]);
                $completed = $stmt->fetch()['total'];

                $pending = $total - $completed;
            ?>

            <p>Total Tasks: <?= $total ?></p>
            <p>Completed: <?= $completed ?></p>
            <p>Pending: <?= $pending ?></p>

            <br>

            <table class="main-table">
                <tr>
                    <th>Employee</th>
                    <th>Total</th>
                    <th>Completed</th>
                    <th>Pending</th>
                </tr>

                <?php if (!empty($all_users) && is_array($all_users)) { ?>
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
                            $stmt->execute([$user['id'], $start, $end]);
                            $row = $stmt->fetch();

                            $t = $row['total'] ?? 0;
                            $c = $row['completed'] ?? 0;
                            $p = $t - $c;
                        ?>

                        <tr>
                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                            <td><?= $t ?></td>
                            <td><?= $c ?></td>
                            <td><?= $p ?></td>
                        </tr>

                    <?php } ?>
                <?php } ?>

            </table>

            <br>

            <button onclick="window.print()" class="edit-btn no-print">Print</button>

            <a href="download_report.php?type=<?= $weekly_report ? 'weekly' : 'monthly' ?>" class="edit-btn no-print">
                Download
            </a>

        </div>

    <?php } else { ?>

        <!-- TASKS -->
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
                <?php $i = 0; foreach ($tasks as $task) { ?>
                    <tr>
                        <td><?= ++$i ?></td>
                        <td><?= htmlspecialchars($task['title']) ?></td>
                        <td><?= htmlspecialchars($task['description']) ?></td>

                        <!-- ✅ RESTORED PRIORITY -->
                        <td>
                            <?php 
                                if ($task['priority'] == 'High') {
                                    echo "🔴 High";
                                } elseif ($task['priority'] == 'Medium') {
                                    echo "🟡 Medium";
                                } else {
                                    echo "🟢 Low";
                                }
                            ?>
                        </td>

                        <td>
                            <?php foreach($all_users as $user) {
                                if($user['id'] == $task['assigned_to']) {
                                    echo htmlspecialchars($user['full_name']);
                                }
                            } ?>
                        </td>

                        <td><?= empty($task['due_date']) ? "No Deadline" : $task['due_date'] ?></td>
                        <td><?= $task['status'] ?></td>

                        <td>
                            <?php if (!empty($task['file_path'])) { ?>
                                <a href="download.php?file=<?= urlencode(basename($task['file_path'])) ?>" class="edit-btn">Download</a>
                            <?php } else { ?>
                                <span style="color:#888;">No file</span>
                            <?php } ?>
                        </td>

                        <td>
                            <a href="edit-task.php?id=<?= $task['id'] ?>" class="edit-btn">Edit</a>
                            <a href="delete-task.php?id=<?= $task['id'] ?>" class="delete-btn">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>

        </table>

    <?php } ?>

</section>
</div>

</body>
</html>

<?php  
} else { 
    header("Location: login.php?error=First login");
    exit(); 
}
?>