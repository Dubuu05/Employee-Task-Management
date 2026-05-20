<?php
session_start();

if (isset($_POST['user_name']) && isset($_POST['password'])) {

    include "../DB_connection.php";

    function validate_input($data) {
        return trim(htmlspecialchars(stripslashes($data)));
    }

    $user_name = validate_input($_POST['user_name']);
    $password = validate_input($_POST['password']);

    if (empty($user_name)) {
        header("Location: ../login.php?error=" . urlencode("User name is required!"));
        exit();
    }

    if (empty($password)) {
        header("Location: ../login.php?error=" . urlencode("Password is required!"));
        exit();
    }

    try {

        $sql = "SELECT * FROM users WHERE LTRIM(RTRIM(username)) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_name]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            $usernameDb = trim($user['username']);
            $passwordDb = $user['password'];
            $role = $user['role'];
            $id = $user['id'];

            if (password_verify($password, $passwordDb)) {

                $_SESSION['role'] = $role;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $usernameDb;
                $_SESSION['full_name'] = $user['full_name'] ?? $usernameDb;

                header("Location: ../index.php");
                exit();

            } else {
                header("Location: ../login.php?error=" . urlencode("Incorrect username or password!"));
                exit();
            }

        } else {
            header("Location: ../login.php?error=" . urlencode("Incorrect username or password!"));
            exit();
        }

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }

} else {
    header("Location: ../login.php?error=" . urlencode("Unknown error occurred!"));
    exit();
}
?>