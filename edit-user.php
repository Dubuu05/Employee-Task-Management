<?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == 'admin') {

    include "DB_connection.php";
    include "app/Model/User.php";

    if (!isset($_GET['id'])) {
        header("Location: user.php");
        exit();
    }

    $id = $_GET['id'];
    $user = get_user_by_id($conn, $id);

    if (!$user) {
        header("Location: user.php");
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
            <h1>Edit User</h1>
            <p>Update user account information</p>
        </div>

        <div class="eu-icon">
            <i class="fa fa-user"></i>
        </div>
    </div>

    <div class="eu-card">

        <form method="POST" action="app/update-user.php">

            <?php if (isset($_GET['error'])) { ?>
                <div class="eu-danger">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php } ?>

            <?php if (isset($_GET['success'])) { ?>
                <div class="eu-success">
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </div>
            <?php } ?>

            <div class="eu-group">
                <label>Full Name</label>
                <input type="text" name="full_name"
                    class="eu-input"
                    value="<?= htmlspecialchars($user['full_name']) ?>">
            </div>

            <div class="eu-group">
                <label>Username</label>
                <input type="text" name="user_name"
                    class="eu-input"
                    value="<?= htmlspecialchars($user['username']) ?>">
            </div>

            <!-- FIXED PASSWORD FIELD -->
            <div class="eu-group">
                <label>Password</label>
                <input type="password" name="password"
                    class="eu-input"
                    placeholder="Leave blank if you don't want to change password">
            </div>

            <input type="hidden" name="id" value="<?= $user['id'] ?>">

            <button class="eu-btn">Update</button>

        </form>

    </div>

</div>

</section>

<script>
var active = document.querySelector("#navlist li:nth-child(2)");
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