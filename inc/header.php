<?php 
  include_once "app/Model/Notification.php";
  if (isset($_SESSION['id'])) {
      $header_notifications = get_all_my_notifications($conn, $_SESSION['id']);
  } else {
      $header_notifications = 0;
  }
?>

<header class="header">
    <h2 class="u-name">Bff <b>Ni Matt</b>
        <label for="checkbox">
            <i id="navbtn" class="fa fa-bars" aria-hidden="true"></i>
        </label>
    </h2>
    <span class="notification" id="notificationBtn">
        <i class="fa fa-bell" aria-hidden="true"></i>
        <span id="notificationNum"></span>
    </span>
</header>

<div class="notification-bar" id="notificationBar">
    <ul>
        <?php 
        if ($header_notifications != 0 && !empty($header_notifications)) { 
            foreach ($header_notifications as $notif) { 
        ?>
            <li>
                <a href="app/notifications-read.php?notification_id=<?= $notif['id'] ?>">
                    <?php if ($notif['is_read'] == 0) { ?>
                        <mark><?= htmlspecialchars($notif['type']) ?>:</mark> 
                    <?php } else { ?>
                        <span><?= htmlspecialchars($notif['type']) ?>:</span>
                    <?php } ?>
                    
                    <?= htmlspecialchars($notif['message']) ?>
                    &nbsp;&nbsp;<small><?= $notif['date'] ?></small>
                </a>
            </li>
        <?php 
            } 
        } else { 
        ?>
            <li><a href="#">No new notifications</a></li>
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
    if(notificationBtn) {
        notificationBtn.addEventListener("click", notification);
    }

    // AJAX count logic
    $(document).ready(function(){
        // I-load ang count agad
        $("#notificationNum").load("app/notification-count.php");
        
        // Refresh count every 3 seconds para makita agad ang pagbawas
        setInterval(function(){
            $("#notificationNum").load("app/notification-count.php");
        }, 3000);
    });
</script>