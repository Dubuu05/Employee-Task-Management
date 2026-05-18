<?php
session_start();

if (
    isset($_SESSION['role']) &&
    isset($_SESSION['id']) &&
    $_SESSION['role'] == 'employee'
) {

include "DB_connection.php";
include "app/Model/User.php";

$user = get_user_by_id($conn, $_SESSION['id']);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>

    <link rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<input type="checkbox" id="checkbox">

<?php include "inc/header.php" ?>

<div class="body">

<?php include "inc/nav.php" ?>

<!-- EDIT PROFILE SECTION -->
<section class="section-1 profile-section">

    <div class="profile-wrapper">

        <div class="table-container profile-card">

            <!-- HEADER -->
            <div class="profile-top">
                <h2 class="title-2">Edit Profile</h2>
                <p class="page-subtitle">Update your account information.</p>
            </div>

            <!-- ALERTS -->
            <?php if (isset($_GET['error'])) { ?> 
                <div class="danger">
                    <?= stripslashes($_GET['error']); ?>
                </div>
            <?php } ?>

            <?php if (isset($_GET['success'])) { ?> 
                <div class="success">
                    <?= stripslashes($_GET['success']); ?>
                </div>
            <?php } ?>

            <!-- FORM -->
            <form class="form-1"
                  method="POST"
                  action="app/update-profile.php">

                <div class="input-holder">
                    <label>Full Name</label>
                    <input type="text"
                           name="full_name"
                           class="input-1"
                           value="<?= $user['full_name'] ?>">
                </div>

                <div class="input-holder">
                    <label>Old Password</label>
                    <input type="password"
                           name="password"
                           class="input-1"
                           placeholder="Old Password">
                </div>

                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                <div class="input-holder">
                    <label>New Password</label>
                    <input type="password"
                           name="new_password"
                           class="input-1"
                           placeholder="New Password">
                </div>

                <div class="input-holder">
                    <label>Confirm New Password</label>
                    <input type="password"
                           name="confirm_password"
                           class="input-1"
                           placeholder="Confirm Password">
                </div>

                <!-- BUTTONS -->
                <div class="profile-actions">

                <div class="left-btn">
                <a href="profile.php" class="edit-btn">
                <i class="fa fa-arrow-left"></i> Back
                </a>
                </div>

                <div class="right-btn">
                <button type="submit" class="edit-btn">
                <i class="fa fa-pencil"></i> Change
                </button>
                </div>

</div>

</div>

            </form>

        </div>
    </div>

</section>

</div>

<script>
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