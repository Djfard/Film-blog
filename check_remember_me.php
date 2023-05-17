<?php
if (isset($_COOKIE['token']) && !empty($_COOKIE['token'])) {
    $token = $_COOKIE['token'];

    // Find the user associated with this token
    $stmt = $conn->prepare("SELECT * FROM users WHERE remember_me_token=?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the token has expired
        if (date('Y-m-d H:i:s') > $user['token_expires']) {
            // The token has expired - clear it from the database and the user's cookies
            $stmt = $conn->prepare("UPDATE users SET token=NULL, token_expires=NULL WHERE user_id=?");
            $stmt->bind_param('i', $user['user_id']);
            $stmt->execute();
            setcookie('token', '', time() - 3600, "/");
        } else {
            // The token has not expired - log the user in
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
        }
    }
}
?>