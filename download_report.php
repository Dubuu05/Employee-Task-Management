<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Unauthorized");
}

include "DB_connection.php";

$type = $_GET['type'] ?? '';

if ($type == "weekly") {
    $start = date('Y-m-d', strtotime('-7 days'));
    $end = date('Y-m-d');
    $title = "Weekly Report";
} else {
    $start = date('Y-m-01');
    $end = date('Y-m-t');
    $title = "Monthly Report";
}

/* FORCE DOWNLOAD AS EXCEL */
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename={$title}.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table border="1">
    <tr>
        <th colspan="4"><?= $title ?></th>
    </tr>
    <tr>
        <th>Total Tasks</th>
        <th>Completed</th>
        <th>Pending</th>
    </tr>

<?php
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

<tr>
    <td><?= $total ?></td>
    <td><?= $completed ?></td>
    <td><?= $pending ?></td>
</tr>

</table>