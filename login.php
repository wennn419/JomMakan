<?php
session_start();
require_once "db_connect.php";

$message = "";

// ---------- Remember Me: auto-login via cookie ----------
if (!isset($_SESSION["user_id"]) && isset($_COOKIE["remember_me"])) {

    $cookie_parts = explode(":", $_COOKIE["remember_me"], 2);

    if (count($cookie_parts) === 2) {

        list($remember_uid, $remember_token) = $cookie_parts;
        $hashed_token = hash("sha256", $remember_token);

        $rstmt = $conn->prepare("
            SELECT id, username, role, remember_token, remember_expires
            FROM users
            WHERE id = ?
        ");
        $rstmt->bind_param("i", $remember_uid);
        $rstmt->execute();
        $rresult = $rstmt->get_result();

        if ($rresult->num_rows == 1) {

            $ruser = $rresult->fetch_assoc();

            $valid_token   = !empty($ruser["remember_token"]) && hash_equals($ruser["remember_token"], $hashed_token);
            $not_expired   = !empty($ruser["remember_expires"]) && strtotime($ruser["remember_expires"]) > time();

            if ($valid_token && $not_expired) {

                session_regenerate_id(true);

                $_SESSION["user_id"]       = $ruser["id"];
                $_SESSION["username"]      = $ruser["username"];
                $_SESSION["role"]          = $ruser["role"];
                $_SESSION["last_activity"] = time();

                header("Location: home.php");
                exit;

            } else {
                // stale/invalid cookie - clear it
                setcookie("remember_me", "", time() - 3600, "/");
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("
        SELECT id, username, password, role
        FROM users
        WHERE username = ?
    ");

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["last_activity"] = time();

        // ---------- Remember Me: issue token + cookie ----------
        if (!empty($_POST["remember"])) {

            $remember_token = bin2hex(random_bytes(32));
            $hashed_token = hash("sha256", $remember_token);
            $expires_at = date("Y-m-d H:i:s", time() + (30 * 24 * 60 * 60)); // 30 days

            $ustmt = $conn->prepare("
                UPDATE users
                SET remember_token = ?, remember_expires = ?
                WHERE id = ?
            ");
            $ustmt->bind_param("ssi", $hashed_token, $expires_at, $user["id"]);
            $ustmt->execute();

            setcookie(
                "remember_me",
                $user["id"] . ":" . $remember_token,
                [
                    "expires"  => time() + (30 * 24 * 60 * 60),
                    "path"     => "/",
                    "httponly" => true,
                    "secure"   => true,
                    "samesite" => "Lax"
                ]
            );
        }

        header("Location: home.php");
        
        exit;

        } else {

            $message = "Invalid username or password.";

        }

    } else {

        $message = "Invalid username or password.";

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | JomMakan</title>

    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<div class="background">

    <div class="logo">
    JomMakan
    </div>

    <div class="login-card">

    <h2>Welcome Back</h2>

<!-- Registration Success -->
<?php if (isset($_GET["registered"])) : ?>

<p class="success">
    Registration successful.
</p>

<?php endif; ?>


<!-- Session Expired -->
<?php if (isset($_GET["expired"])) : ?>

<p class="message">
    Your session has expired. Please login again.
</p>

<?php endif; ?>


<!-- Login Failed -->
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

            <div class="login-options">

                <label>

                    <input type="checkbox" name="remember">

                    Remember me

                </label>

            </div>

            <button type="submit">

                LOGIN

            </button>

        </form>

        <p class="register-link">

            Don't have an account?

            <a href="register.php">

                Sign up

            </a>

        </p>

    </div>

</div>

<script src="js/auth.js"></script>

</body>

</html>