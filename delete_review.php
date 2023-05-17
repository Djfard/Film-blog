<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['review_id'])) {
    header('Location: login.php');
    exit;
}

// Check if user is admin
$sql = "SELECT user_type FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$sql = '';

if ($user['user_type'] === 'admin') {
    // If user is admin, delete any review
    $sql = "DELETE FROM reviews WHERE review_id = ?";
} else {
    // If user is not admin, only delete own reviews
    $sql = "DELETE FROM reviews WHERE review_id = ? AND user_id = ?";
}

$stmt = $conn->prepare($sql);

if ($user['user_type'] === 'admin') {
    $stmt->bind_param("i", $_POST['review_id']);
} else {
    $stmt->bind_param("ii", $_POST['review_id'], $_SESSION['user_id']);
}

$stmt->execute();

if ($stmt->affected_rows > 0) {
    header('Location: profile.php');
    exit;
} else {
    if ($stmt->error) {
        echo "Error deleting review: " . $stmt->error;
    } else {
        echo "Error deleting review. No matching review found.";
    }
    exit;
}
?>