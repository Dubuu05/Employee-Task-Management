<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == 'admin') {

    include "DB_connection.php";
    include "app/Model/User.php";

    $users = get_all_users($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Manage Users</title>

    <link rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet"
    href="css/style.css">

</head>

<body>

<input type="checkbox" id="checkbox">

<?php include "inc/header.php"; ?>

<div class="body">

<?php include "inc/nav.php"; ?>

<section class="section-1">

    <!-- =========================================================
         PAGE HEADER
    ========================================================= -->

    <div class="page-header">

        <div>

            <h2 class="title-2">
                Manage Users
            </h2>

            <p class="page-subtitle">
                Manage employee accounts and permissions
            </p>

        </div>

        <a href="add-user.php" class="filter-btn">

            <i class="fa fa-plus"></i>

            Add User

        </a>

    </div>

    <!-- =========================================================
         SUCCESS MESSAGE
    ========================================================= -->

    <?php if (isset($_GET['success'])) { ?>

    <div class="success modern-alert">

        <i class="fa fa-check-circle"></i>

        <?= stripslashes($_GET['success']); ?>

    </div>

    <?php } ?>

    <!-- =========================================================
         USERS TABLE
    ========================================================= -->

    <?php if ($users != 0) { ?>

    <div class="table-container">

        <table class="main-table">

            <tr>

                <th>#</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Role</th>
                <th>Action</th>

            </tr>

            <?php $i = 0; ?>

            <?php foreach ($users as $user) { ?>

            <tr>

                <!-- NUMBER -->

                <td>
                    <?= ++$i ?>
                </td>

                <!-- FULL NAME -->

                <td>

                    <div class="user-info">

                        <div class="user-avatar">

                            <?= strtoupper(substr($user['full_name'], 0, 1)) ?>

                        </div>

                        <div>

                            <strong>
                                <?= htmlspecialchars($user['full_name']) ?>
                            </strong>

                        </div>

                    </div>

                </td>

                <!-- USERNAME -->

                <td>

                    @<?= htmlspecialchars($user['username']) ?>

                </td>

                <!-- ROLE -->

                <td>

                    <?php if ($user['role'] == 'admin') { ?>

                        <span class="status-progress">
                            Admin
                        </span>

                    <?php } else { ?>

                        <span class="status-completed">
                            Employee
                        </span>

                    <?php } ?>

                </td>

                <!-- ACTION -->

                <td>

                    <div class="action-buttons">

                        <a href="edit-user.php?id=<?= $user['id'] ?>"
                        class="edit-btn">

                            <i class="fa fa-pencil"></i>

                            Edit

                        </a>

                        <a href="delete-user.php?id=<?= $user['id'] ?>"
                        class="delete-btn">

                            <i class="fa fa-trash"></i>

                            Delete

                        </a>

                    </div>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

    <?php } else { ?>

    <!-- =========================================================
         EMPTY STATE
    ========================================================= -->

    <div class="empty-state">

        <i class="fa fa-users"></i>

        <h3>No users found</h3>

        <p>Create a new user to get started.</p>

    </div>

    <?php } ?>

</section>

</div>

<script>

var active = document.querySelector("#navlist li:nth-child(2)");

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