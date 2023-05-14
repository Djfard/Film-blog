<?php
session_start();
include 'db_connection.php';

$error_message = "";
$is_error = false;

if (isset($_POST['register'])) {
    // Check if form fields are set
    $username = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : null;
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;
    $password = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : null;
    $confirm_password = isset($_POST['confirm_password']) ? $conn->real_escape_string($_POST['confirm_password']) : null;

    // Check if form fields are empty
    if (!$username || !$email || !$password || !$confirm_password) {
        $error_message = "Username, email or password cannot be empty!";
        $is_error = true;
    } else {
        if ($password != $confirm_password) {
            $error_message = 'Passwords do not match!';
            $is_error = true;
        } else {
            if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
                $error_message = "Invalid username format!";
                $is_error = true;
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = "Invalid email format!";
                $is_error = true;
            } else {
                $uppercase = preg_match('@[A-Z]@', $password);
                $lowercase = preg_match('@[a-z]@', $password);
                $number = preg_match('@[0-9]@', $password);
                $specialChars = preg_match('@[^\w]@', $password);

                if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 6 || strlen($password) > 20) {
                    $error_message = 'Password should be between 6 and 20 characters and include at least one upper case letter, one number, and one special character.';
                    $is_error = true;
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

                    if ($conn->query($sql) === TRUE) {
                        // Get the user_id of the registered user
                        $user_id = $conn->insert_id;

                        // Set session variables and redirect to profile page
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['username'] = $username;
                        $_SESSION['email'] = $email;
                        header("Location: profile.php");
                        exit();
                    } else {
                        $error_message = "Error: " . $sql . "<br>" . $conn->error;
                        $is_error = true;
                    }
                }
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
        <title>Register</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="login.css">
    </head>

    <body>
        <div class="container">
            <div class="row justify-content-center align-items-center" style="height:100vh;">
                <div class="col-4">
                    <h1 class="text-center titleColor mb-5">Register</h1>
                    <?php if ($error_message != ""): ?>
                        <p class="<?php echo $is_error ? 'error-message' : 'success-message'; ?>">
                            <?php echo $error_message; ?>
                        </p>
                    <?php endif; ?>
                    <form action="register.php" method="post">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" name="username" id="username" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password:</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                                required>
                        </div>
                        <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
                    </form>
                    <hr>
                    <p class="text-center">Already have an account? <a href="login.php">Login now!</a></p>
                    <p class="text-center">Or</p>
                    <a href="/" class="btn btn-secondary btn-block">Go to Home</a>
                </div>
            </div>
        </div>
    </body>

</html>