<?php
session_start();
include 'db_connection.php';

if (isset($_POST['submit-review'])) {
    $filmName = $_POST['review-film-name'];
    $filmID = $_POST['review-film'];
    $content = $_POST['review-content'];
    $rating = $_POST['review-rating'];
    $userID = $_SESSION['user_id'];
    $reviewDate = date('Y-m-d H:i:s');

    $sql = "INSERT INTO reviews (user_id, film_name, film, content, rating, review_date) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issiis", $userID, $filmName, $filmID, $content, $rating, $reviewDate);

    if ($stmt->execute()) {
        header('Location: profile.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

$conn->close();
?>
