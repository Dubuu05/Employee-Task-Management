<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Task Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bruno+Ace+SC&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar-tech">

    <div class="logo-container">

        <img src="img/TechNova_logo.png"
             alt="TechNova Logo"
             class="nav-logo">

        <span class="logo-text">
            Tech<b>Nova</b>
        </span>

    </div>

</nav>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-overlay">
    </div>

    <div class="hero-content">
        <h1>Welcome</h1>
        <p>
            Organize projects, manage teams, and boost productivity
            with TechNova's smart management platform.
        </p>

        <!-- LOGIN FORM -->
        <form method="POST" action="app/login.php" class="shadow p-4 login-form">

            <h3 class="display-6 mb-3">Login</h3>

            <?php if (isset($_GET['error'])) { ?> 
            <div class="alert alert-danger" role="alert">
                <?php echo stripslashes($_GET['error']); ?>
            </div>
            <?php } ?>

            <?php if (isset($_GET['success'])) { ?> 
            <div class="alert alert-success" role="alert">
                <?php echo stripslashes($_GET['success']); ?>
            </div>
            <?php } ?>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="user_name">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password">
            </div>

            <button type="submit" class="btn btn-tech">
                Login
            </button>

        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>