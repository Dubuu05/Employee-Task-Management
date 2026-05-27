<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {       

    include "DB_connection.php";
    include "app/Model/Notification.php";

    $notifications = get_all_my_notifications($conn, $_SESSION['id']); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<input type="checkbox" id="checkbox">

<?php include "inc/header.php"; ?>
<div class="body">
    <?php include "inc/nav.php"; ?>

    <section class="section-1 notifications-page">
        <h4 class="title">All Notifications</h4>

        <?php if (isset($_GET['success'])) { ?> 
            <div class="success">
                <?= stripslashes($_GET['success']); ?>
            </div>
        <?php } ?>

        <?php if (!empty($notifications) && $notifications != 0) { ?>

            <table class="main-table">
                <tr>
                    <th>#</th>
                    <th>Message</th>
                    <th>Priority</th>

                    <?php if ($_SESSION['role'] === 'employee') { ?>
                        <th>Type</th>
                    <?php } ?>

                    <th>Date</th>
                </tr>

                <?php 
                $i = 1; 
                foreach ($notifications as $notif) { 
                ?>
                <tr>
                    <td><?= $i ?></td>

                   
                    <td><?= htmlspecialchars($notif['message'] ?? '') ?></td>

                  
                   <td>
<?php
$priority = $notif['priority'] ?? 'Low';

if ($priority === 'High') {
    echo "<span class='priority-high'>🔴 High</span>";
}
elseif ($priority === 'Medium') {
    echo "<span class='priority-medium'>🟡 Medium</span>";
}
else {
    echo "<span class='priority-low'>🟢 Low</span>";
}
?>
</td>

                    <?php if ($_SESSION['role'] === 'employee') { ?>
                        <td><?= htmlspecialchars($notif['type'] ?? '') ?></td>
                    <?php } ?>

              
                    <td>
                        <?php
                        if (!empty($notif['created_at'])) {
                            echo date("F d, Y h:i A", strtotime($notif['created_at']));
                        } else {
                            echo "No date";
                        }
                        ?>
                    </td>

                </tr>
                <?php $i++; } ?>

            </table>

        <?php } else { ?>
            <div class="input-holder">
                <h3>Empty Notifications</h3>
            </div>
        <?php } ?>

    </section>
</div>

<script type="text/javascript">
<?php if ($_SESSION['role'] === 'admin') { ?>
    var active = document.querySelector("#navlist li:nth-child(5)");
<?php } else { ?>
    var active = document.querySelector("#navlist li:nth-child(4)");
<?php } ?>

if (active) {
    active.classList.add("active");
}
</script>

</body>
</html>

<?php  
} else { 
    $em = "First login";
    header("Location: login.php?error=" . urlencode($em));
    exit(); 
}
?>