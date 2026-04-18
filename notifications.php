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
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php"; ?>
    <div class="body">
        <?php include "inc/nav.php"; ?>
        <section class="section-1">
            <h4 class="title">All Notifications</h4>

            <?php if (isset($_GET['success'])) { ?> 
                <div class="success" role="alert">
                    <?= stripslashes($_GET['success']); ?>
                </div>
            <?php } ?>

            <?php if ($notifications != 0 && !empty($notifications)) { ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Message</th>
                            <?php if ($_SESSION['role'] === 'employee') { ?>
                                <th>Type</th>
                            <?php } ?>
                            <th style="width: 150px;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1; 
                        foreach ($notifications as $notif) { 
                        ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= htmlspecialchars($notif['message']) ?></td>
                            <?php if ($_SESSION['role'] === 'employee') { ?>
                                <td><?= htmlspecialchars($notif['type']) ?></td>
                            <?php } ?>
                            <td><?= $notif['date'] ?></td>
                        </tr>
                        <?php 
                            $i++;
                        } 
                        ?>
                    </tbody>
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