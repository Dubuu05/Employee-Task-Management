<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    include "../DB_connection.php";
    include "Model/Notification.php";

    $notifications = get_all_my_notifications($conn, $_SESSION['id']);

    echo '<ul>';

    if ($notifications == 0) {

        echo '
        <div class="notification-empty">
            You have zero notifications
        </div>
        ';

    } else {

        $current_group = "";

        foreach ($notifications as $notification) {

            $notif_date = date("Y-m-d", strtotime($notification['date']));
            $today = date("Y-m-d");
            $yesterday = date("Y-m-d", strtotime("-1 day"));

            if($notif_date == $today){
                $group = "Today";
            }
            elseif($notif_date == $yesterday){
                $group = "Yesterday";
            }
            else{
                $group = date("F d, Y", strtotime($notification['date']));
            }

            if($group != $current_group){

                echo '<h4 class="notif-group">'.$group.'</h4>';

                $current_group = $group;
            }
?>

<li class="notif-card <?=($notification['is_read'] == 0) ? 'notif-unread' : '';?>">

    <a href="app/notification-read.php?notification_id=<?=$notification['id']?>">

        <div class="notif-top">

            <div class="notif-type">

                <?php if($notification['is_read'] == 0){ ?>
                    <i class="fa fa-circle"></i>
                <?php } ?>

                <?=$notification['type']?>

            </div>

            <div class="notif-date">
                <?=$notification['date']?>
            </div>

        </div>

        <div class="notif-message">
            <?=$notification['message']?>
        </div>

    </a>

</li>

<?php
        }
    }

    echo '</ul>';
}
?>