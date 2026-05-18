<?php
session_start();

if (
    isset($_SESSION['role']) &&
    isset($_SESSION['id']) &&
    $_SESSION['role'] == 'employee'
) {

    include "DB_connection.php";
    include "app/Model/User.php";

    $user = get_user_by_id(
        $conn,
        $id = $_SESSION['id']
    );

?>

<!DOCTYPE html>
<html>

<head>

    <title>Profile</title>

    <link rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet"
    href="css/style.css">

</head>

<body>

<input type="checkbox" id="checkbox">

<?php include "inc/header.php" ?>

<div class="body">

<?php include "inc/nav.php" ?>

<section class="section-1 profile-section">

    <div class="profile-wrapper">

        <!-- PROFILE CARD -->

        <div class="table-container profile-card">

            <!-- TITLE -->

            <div class="profile-top">

                <div>

                    <h2 class="title-2">
                        My Profile
                    </h2>

                    <p class="page-subtitle">
                        View and manage your account information.
                    </p>

                </div>

            </div>

            <!-- TABLE -->

            <table class="main-table">

                <tr>

                    <th width="35%">
                        Information
                    </th>

                    <th>
                        Details
                    </th>

                </tr>

                <!-- FULL NAME -->

                <tr>

                    <td>
                        <strong>
                            Full Name
                        </strong>
                    </td>

                    <td>
                        <?= htmlspecialchars($user['full_name']) ?>
                    </td>

                </tr>

                <!-- USERNAME -->

                <tr>

                    <td>
                        <strong>
                            Username
                        </strong>
                    </td>

                    <td>
                        <?= htmlspecialchars($user['username']) ?>
                    </td>

                </tr>

                <!-- DATE JOINED -->

                <tr>

                    <td>
                        <strong>
                            Joined At
                        </strong>
                    </td>

                    <td>
                        <?= htmlspecialchars($user['created_at']) ?>
                    </td>

                </tr>

            </table>

            <!-- BUTTON -->

            <div class="profile-actions">

                <a href="edit_profile.php"
                class="edit-btn">

                    <i class="fa fa-pencil"></i>
                    Edit Profile

                </a>

            </div>

        </div>

    </div>

</section>

</div>

<script type="text/javascript">

var active =
document.querySelector("#navlist li:nth-child(3)");

if(active){
    active.classList.add("active");
}

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