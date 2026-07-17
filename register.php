<?php
session_start();
require_once "db_connect.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validation
    if ($password !== $confirm_password) {

        $message = "Passwords do not match.";

    } else {

        // Check username or email already exists
        $check = $conn->prepare("
            SELECT id
            FROM users
            WHERE username = ?
            OR email = ?
        ");

        $check->bind_param("ss", $username, $email);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "Username or Email already exists.";

        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("
                INSERT INTO users
                (username, email, password)
                VALUES (?, ?, ?)
            ");

            $insert->bind_param(
                "sss",
                $username,
                $email,
                $hashedPassword
            );

            if ($insert->execute()) {

            // Get the new user's ID
            $userId = $conn->insert_id;

            // Auto login
            $_SESSION["user_id"] = $userId;
            $_SESSION["username"] = $username;

            // Redirect to Home
            header("Location: home.php");
            exit;

            } else {

                $message = "Registration failed.";

            }

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | JomMakan</title>

    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<div class="background">

    <div class="logo">
        <a href="home.php">JomMakan</a>
    </div>

    <div class="register-card">

        <h2>Create Account</h2>

        <?php if (!empty($message)) : ?>

        <p class="message">
        <?php echo $message; ?>
        </p>

        <?php endif; ?>

        <form action="" method="POST">

            <input
                type="text"
                name="username"
                placeholder="Username"
                required
            >

            <input
                type="email"
                name="email"
                placeholder="Email"
                required
            >

            <div class="password-group">

    <input
    type="password"
    id="loginPassword"
    name="password"
    placeholder="Password"
    required
>

<button
    type="button"
    class="toggle-password"
    data-target="loginPassword">

    <i class="fa-regular fa-eye-slash"></i>

</button>

</div>

           <div class="password-group">

    <input
        type="password"
        id="confirmPassword"
        name="confirm_password"
        placeholder="Confirm Password"
        required
    >

    <button
        type="button"
        class="toggle-password"
        data-target="confirmPassword">

        <i class="fa-regular fa-eye-slash"></i>

    </button>

</div>

            <button type="submit">
                REGISTER
            </button>

        </form>

        <p class="login-link">
            Already have an account?
            <a href="login.php">Login</a>
        </p>

    </div>

</div>

<script src="js/auth.js"></script>

</body>
</html>