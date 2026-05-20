<?php 

// 🔌 ALWAYS load DB connection first
include_once "DB_connection.php";
include_once "app/Model/Notification.php";

// 🧠 Safely get notifications
if (isset($_SESSION['id']) && isset($conn)) {
    $header_notifications = get_all_my_notifications($conn, $_SESSION['id']);
} else {
    $header_notifications = [];
}
?>

<header class="header">
   <h2 class="u-name logo-container">

    <!-- MENU BUTTON FIRST -->
    <label for="checkbox">
        <i id="navbtn" class="fa fa-bars" aria-hidden="true"></i>
    </label>

    <!-- LOGO -->
    <img src="img/TechNova_logo.png" 
         alt="TechNova Logo" 
         class="nav-logo">

    <!-- TEXT -->
    <span class="logo-text">
        Tech<b>Nova</b>
    </span>
    </h2>
    <span class="notification" id="notificationBtn">
        <i class="fa fa-bell" aria-hidden="true"></i>
        <span id="notificationNum"></span>
    </span>
</header>

<div class="notification-bar" id="notificationBar">
    <ul>

        <?php if (!empty($header_notifications)) { ?>
            
            <?php foreach ($header_notifications as $notif) { ?>

                <li class="notif-card <?= $notif['is_read'] == 0 ? 'notif-unread' : '' ?>">

                    <a href="app/notifications-read.php?notification_id=<?= $notif['id'] ?>">

                        <div class="notif-top">

                            <div class="notif-type">

                                <i class="fa fa-circle"></i>

                                <?= htmlspecialchars($notif['type']) ?>

                            </div>

                            <div class="notif-date">
                                <?= $notif['date'] ?>
                            </div>

                        </div>

                        <div class="notif-message">
                            <?= htmlspecialchars($notif['message']) ?>
                        </div>

                    </a>

                </li>

            <?php } ?>

        <?php } else { ?>

            <li class="notification-empty">
                No new notifications
            </li>

        <?php } ?>

    </ul>
</div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script type="text/javascript">
    // Dropdown toggle logic
    let openNotification = false;

    const notification = () => {
        let notificationBar = document.querySelector("#notificationBar");

        if (openNotification) {
            notificationBar.classList.remove('open-notification');
            openNotification = false;
        } else {
            notificationBar.classList.add('open-notification');
            openNotification = true;
        }
    }

    let notificationBtn = document.querySelector("#notificationBtn");
    if (notificationBtn) {
        notificationBtn.addEventListener("click", notification);
    }

    // AJAX notification count
    $(document).ready(function () {
        $("#notificationNum").load("app/notification-count.php");

        setInterval(function () {
            $("#notificationNum").load("app/notification-count.php");
        }, 3000);
    });
</script>