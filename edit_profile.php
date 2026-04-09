<?php
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == 'employee') {
    include "DB_connection.php";
    include "app/Model/User.php";

    
    $user = get_user_by_id($conn, $id = $_SESSION['id']);

    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <h4 class= "title">Edit Profile <a href="profile.php">Profile</a></h4>
            <form class= "form-1"
            method="POST"
            action="app/update-profile.php">
            <?php if (isset($_GET['error'])) { ?> 
        <div class="danger" role="alert">
            <?php echo stripslashes($_GET['error']); ?>
        </div>
        <?php } 
            ?>
        <?php if (isset($_GET['success'])) { ?> 
        <div class="success" role="alert">
            <?php echo stripslashes($_GET['success']); ?>
        </div>
        <?php }  ?>
                <div class="input-holder">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="input-1" placeholder="Full Name"value="<?=$user['full_name']?>"><br>
                </div>
                <div class="input-holder">
                    <label>Old Password</label>
                    <input type="text"name="password" class="input-1" placeholder="Old Password"><br>
                </div>
                <input type="text" name="id" value="<?=$user['id']?>"  hidden>
                <div class="input-holder">
                    <label>New Password</label>
                    <input type="text" name="new_password" class="input-1" placeholder="New Password"><br>
                </div>
                <input type="text" name="id" value="<?=$user['id']?>"  hidden>
                <div class="input-holder">
                    <label>Confirm New Password</label>
                    <input type="text" name="confirm_password" class="input-1" placeholder="Confirm Password"><br>
                </div>
                <input type="text" name="id" value="<?=$user['id']?>"  hidden>
                <button class="edit-btn">Change</button>
       </form>
            
            
        </section>
    </div>
    <script type="text/javascript">
        var active = document.querySelector("#navlist li:nth-child(3)");
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