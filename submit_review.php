<?php
session_start();
include 'db_connection.php';

if (isset($_POST['submit-review'])) {
    $filmName = $conn->real_escape_string($_POST['review-film-name']);
    $filmID = $conn->real_escape_string($_POST['review-film']);
    $content = $conn->real_escape_string($_POST['review-content']);
    $rating = $conn->real_escape_string($_POST['review-rating']);
    $userID = $_SESSION['user_id'];
    $reviewDate = date('Y-m-d H:i:s');

    $sql = "INSERT INTO reviews (user_id, film_name, film, content, rating, review_date) 
            VALUES ('$userID', '$filmName', '$filmID', '$content', '$rating', '$reviewDate')";

    if ($conn->query($sql) === TRUE) {
        header('Location: profile.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>