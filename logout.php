<?php
session_start();
include 'db_connection.php';

if (isset($_COOKIE['token']) && !empty($_COOKIE['token'])) {
    $token = $_COOKIE['token'];

    // Clear the token from the database
    $stmt = $conn->prepare("UPDATE users SET remember_me_token=NULL, token_expires=NULL WHERE remember_me_token=?");

    if ($stmt === false) {
        die('prepare() failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param('s', $token);
    $stmt->execute();

    // Clear the token from the user's cookies
    setcookie('token', '', time() - 3600, "/");
}

if (isset($_COOKIE['username'])) {
    // Clear the username from the user's cookies
    setcookie('username', '', time() - 3600, "/");
}

// Unset all of the session variables.
$_SESSION = array();

// Destroy the session.
session_destroy();

header('Location: index.php');
?>