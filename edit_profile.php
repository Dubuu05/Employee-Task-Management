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

<?php include "inc/header.php"; ?>

<div class="body">

<?php include "inc/nav.php"; ?>

<section class="section-1">

<div class="eu-container">

    <div class="eu-header">
        <div>
            <h1>Edit Profile</h1>
            <p>Update your account information</p>
        </div>

        <div class="eu-icon">
            <i class="fa fa-user"></i>
        </div>
    </div>

    <div class="eu-card">

        <?php if (isset($_GET['error'])) { ?>
            <div class="eu-danger">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php } ?>

        <?php if (isset($_GET['success'])) { ?>
            <div class="eu-success">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php } ?>

        <form method="POST" action="app/update-profile.php">

            <div class="eu-group">
                <label>Full Name</label>
                <input type="text"
                       name="full_name"
                       class="eu-input"
                       value="<?= htmlspecialchars($user['full_name']) ?>">
            </div>

            <div class="eu-group">
                <label>Old Password</label>
                <input type="password"
                       name="password"
                       class="eu-input"
                       placeholder="Enter old password">
            </div>

            <input type="hidden"
                   name="id"
                   value="<?= $user['id'] ?>">

            <div class="eu-group">
                <label>New Password</label>
                <input type="password"
                       name="new_password"
                       class="eu-input"
                       placeholder="Enter new password">
            </div>

            <div class="eu-group">
                <label>Confirm New Password</label>
                <input type="password"
                       name="confirm_password"
                       class="eu-input"
                       placeholder="Confirm new password">
            </div>
                <button type="submit" class="eu-btn">
                    <i class="fa fa-save"></i> Update Profile
                </button>
            </div>

        </form>

    </div>

</div>

</section>

</div>

<script>
var active = document.querySelector("#navlist li:nth-child(3)");
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