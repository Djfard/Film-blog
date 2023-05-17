<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    session_destroy();
    header('Location: login.php');
    exit;
} else {
    echo "Error deleting account. Please try again.";
    exit;
}
?>