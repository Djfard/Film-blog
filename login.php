<?php
session_start();
include 'db_connection.php';
include 'check_remember_me.php';

$error_message = '';

if (isset($_POST['login'])) {
    // Check if username or password fields are empty
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error_message = "Username or password cannot be empty!";
    } else {
        // Sanitize username and password inputs
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);

        // Check if username is in valid format
        if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            $error_message = "Invalid username format!";
        } else {
            $sql = "SELECT * FROM users WHERE username = '$username'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_type'] = $user['user_type'];

                    if (isset($_POST['remember-me']) && $_POST['remember-me'] == 'on') {
                        $token = bin2hex(openssl_random_pseudo_bytes(24)); // generate a token
                        setcookie('token', $token, time() + (86400 * 30), "/");

                        // Set the expiration date/time for the token to 30 days from now
                        $token_expires = date('Y-m-d H:i:s', time() + (86400 * 30));

                        // Store the token and its expiration date/time in the database, associated with this user
                        $stmt = $conn->prepare("UPDATE users SET remember_me_token=?, token_expires=? WHERE user_id=?");
                        $stmt->bind_param('ssi', $token, $token_expires, $user['user_id']);
                        $stmt->execute();
                    } else {
                        // Clear the token from the database and the user's cookies if "Remember me" is not checked
                        $stmt = $conn->prepare("UPDATE users SET remember_me_token=NULL, token_expires=NULL WHERE user_id=?");
                        $stmt->bind_param('i', $user['user_id']);
                        $stmt->execute();

                        setcookie('token', '', time() - 3600, "/");
                    }

                    header('Location: profile.php');
                    exit;
                } else {
                    $error_message = "Incorrect password!";
                }
            } else {
                $error_message = "User not found!";
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
        <title>Login</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="login.css">
    </head>

    <body>
        <div class="container">
            <div class="row justify-content-center align-items-center" style="height:100vh;">
                <div class="col-4">
                    <h1 class="text-center titleColor mb-5">Login</h1>
                    <p class="text-danger text-center">
                        <?php echo $error_message; ?>
                    </p>
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" name="username" id="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" id="remember-me" name="remember-me">
                            <label for="remember-me">Remember me</label>
                        </div>

                        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                    </form>
                    <hr>
                    <p class="text-center">Don't have an account already? <a href="register.php">Register now!</a></p>
                    <p class="text-center">Or</p>
                    <a href="/" class="btn btn-secondary btn-block">Go to Home</a>
                </div>
            </div>
        </div>

        <script src="theme.js"></script>

    </body>

</html>