<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])&& $_SESSION['role'] == 'admin') {
    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
       <section class="section-1">

    <div class="eu-container">

        <div class="eu-header">

            <div>
                <h1>Add User</h1>
                <p>Create a new account</p>
            </div>

            <div class="eu-icon">
                <i class="fa fa-user-plus"></i>
            </div>

        </div>

        <div class="eu-card">

            <form method="POST" action="app/add-user.php">

                <?php if (isset($_GET['error'])) { ?>
                    <div class="eu-danger">
                        <?php echo stripslashes($_GET['error']); ?>
                    </div>
                <?php } ?>

                <?php if (isset($_GET['success'])) { ?>
                    <div class="eu-success">
                        <?php echo stripslashes($_GET['success']); ?>
                    </div>
                <?php } ?>

                <div class="eu-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="eu-input" placeholder="Enter full name">
                </div>

                <div class="eu-group">
                    <label>Username</label>
                    <input type="text" name="user_name" class="eu-input" placeholder="Enter username">
                </div>

                <div class="eu-group">
                    <label>Password</label>
                    <input type="password" name="password" class="eu-input" placeholder="Enter password">
                </div>

                <button class="eu-btn">Add User</button>

            </form>

        </div>

    </div>

</section>
    <script type="text/javascript">
        var active = document.querySelector("#navlist li:nth-child(2)");
        active.classList.add("active");
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