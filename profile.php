<?php
session_start();
include 'db_connection.php';
include 'check_remember_me.php';

if (!isset($_SESSION['username']) && isset($_COOKIE['username'])) {
  $_SESSION['username'] = $_COOKIE['username'];
  $sql = "SELECT user_id, email, user_type FROM users WHERE username = '{$_COOKIE['username']}'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['user_type'] = $user['user_type'];
  }
} elseif (isset($_SESSION['username']) && !isset($_SESSION['user_type'])) {
  $sql = "SELECT user_type FROM users WHERE username = '{$_SESSION['username']}'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_type'] = $user['user_type'];
  }
}

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
?>



<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReelRating</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
      href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css"
      href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://kit.fontawesome.com/yourkitcode.js" crossorigin="anonymous"></script>
  </head>

  <body>

    <nav class="navbar navbar-expand-lg colorNavBar fs-4 sticky-top">
      <div class="container-fluid d-flex justify-content-between">
        <a href="/"><i class="fas fa-video fa-2x" style="color: white;"></i></a>

        <div id="navbarSupportedContent">
          <ul class="navbar-nav fs-4">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://reelrating.lovestoblog.com/#topTenRated">Films</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://reelrating.lovestoblog.com/#reviews">Reviews</a>
            </li>
          </ul>
        </div>

        <div class="navbar-end">
          <ul class="navbar-nav fs-4">
            <li class="nav-item">
              <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#themeModal"><i
                  class="fas fa-paint-brush" style="color: white;"></i></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt" style="color: white;"></i></a>
            </li>
          </ul>
        </div>

      </div>
    </nav>



    <section id="greetings" class="d-flex align-items-center justify-content-center mt-5">
      <h1>Welcome,
        <?php echo $_SESSION['username']; ?>!
      </h1>
    </section>

    <!-- Add Review Section -->
    <section id="addReview" class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8">
          <h2 class="text-center mb-4">Add Your Review</h2>
          <form action="submit_review.php" method="post" class="form-group">
            <div class="mb-3">
              <label for="review-film" class="form-label">Film:</label>
              <select name="review-film" id="review-film" class="form-select"></select>
              <input type="hidden" name="review-film-name" id="review-film-name" required>
            </div>
            <div class="mb-3">
              <label for="review-content" class="form-label">Review:</label>
              <textarea name="review-content" id="review-content" rows="5" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
              <label for="review-rating" class="form-label">Rating (1-5):</label>
              <input type="number" name="review-rating" id="review-rating" min="1" max="5"
                class="form-control text-center" required>
            </div>
            <div class="d-grid gap-2">
              <input type="submit" name="submit-review" value="Submit Review" class="btn btn-primary">
            </div>
          </form>
        </div>
      </div>
    </section>

    <!-- Reviews -->
    <section id="reviews" class="container mt-5">
      <div class="row">
        <div class="col-md-6 border-end border-3 pe-5" style="border-color: #1C2833;">
          <h2 class="text-center mb-4" style="color: #DC143C;">Your Reviews</h2>

          <?php
          $sql = "SELECT * FROM reviews WHERE user_id = {$_SESSION['user_id']} ORDER BY review_date DESC";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<div class='review-card mb-4'>";
              echo "<div class='card-body' style='background-color: #DDDDDD;'>";
              echo "<h3>" . $_SESSION['username'] . " reviewed " . $row['film_name'] . "</h3>";
              echo "<h6 class='card-subtitle mb-2 text-muted'>Rating: " . $row['rating'] . "/5</h6>";
              echo "<p class='card-text card-content' style='color: #333333;'>" . $row['content'] . "</p>";
              echo "<p class='card-text card-date'><small class='text-muted'>Date: " . $row['review_date'] . "</small></p>";
              echo "<form action='delete_review.php' method='POST'>";
              echo "<input type='hidden' name='review_id' value='" . $row['review_id'] . "'>";
              echo "<input type='submit' value='Delete Review' class='btn btn-danger'>";
              echo "</form>";
              echo "<form action='edit_review.php' method='POST'>";
              echo "<input type='hidden' name='review_id' value='" . $row['review_id'] . "'>";
              echo "</form>";
              echo "</div>";
              echo "</div>";
            }
          } else {
            echo "<p class='no-reviews text-center'>No reviews found!</p>";
          }
          ?>

        </div>

        <div class="col-md-6 ps-5">
          <h2 class="text-center mb-4" style="color: #DC143C;">Recent Reviews</h2>

          <!-- Recent Reviews -->
          <?php
          $sql_recent = "SELECT reviews.*, users.username FROM reviews JOIN users ON reviews.user_id = users.user_id ORDER BY review_date DESC LIMIT 10";
          $result_recent = $conn->query($sql_recent);

          if ($result_recent->num_rows > 0) {
            while ($row = $result_recent->fetch_assoc()) {
              echo "<div class='review-card mb-4'>";
              echo "<div class='card-body' style='background-color: #DDDDDD;'>";
              echo "<h3>" . $row['username'] . " reviewed " . $row['film_name'] . "</h3>";
              echo "<h6 class='card-subtitle mb-2 text-muted'>Rating: " . $row['rating'] . "/5</h6>";
              echo "<p class='card-text card-content' style='color: #333333;'>" . $row['content'] . "</p>";
              echo "<p class='card-text card-date'><small class='text-muted'>Date: " . $row['review_date'] . "</small></p>";

              // If the user is an admin, echo out the delete button
              if ($_SESSION['user_type'] === 'admin') {
                echo "<form action='delete_review.php' method='post'>";
                echo "<input type='hidden' name='review_id' value='{$row['review_id']}'>";
                echo "<input type='submit' name='delete-review' value='Delete Review' class='btn btn-danger'>";
                echo "</form>";
              }

              echo "</div>";
              echo "</div>";
            }
          } else {
            echo "No recent reviews found!";
          }
          ?>


        </div>
      </div>
    </section>

    <div class="container text-center mt-3 mb-3">
      <form action="delete_account.php" method="POST">
        <input type="submit" value="Delete Account" class="btn btn-danger btn-large">
      </form>
    </div>

    <?php if ($_SESSION['user_type'] === 'admin'): ?>
      <div class="d-grid gap-2">
        <a href="admin_users.php" class="btn btn-primary" target="_blank">View All Users</a>
      </div>
    <?php endif; ?>

    <!-- Theme Modal -->
    <div class="modal fade" id="themeModal" tabindex="-1" aria-labelledby="themeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title theme-modal-title" id="themeModalLabel">Choose Your Theme</h5>
            <button type="button" class="btn-close theme-modal-close" data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body d-flex justify-content-center align-items-center">
            <div class="form-check form-switch d-flex align-items-center justify-content-between theme-modal-text">
              <label class="form-check-label me-2" for="themeSwitch">Grey</label>
              <input class="form-check-input theme-modal-switch" type="checkbox" id="themeSwitch">
              <label class="form-check-label ms-2" for="themeSwitch">Light</label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary theme-modal-button" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary theme-modal-button" id="saveTheme">Save changes</button>
          </div>
        </div>
      </div>
    </div>




    <footer class="footer py-4 bg-dark">
      <div class="container text-center text-white">
        <span>Educational purposes only! No rights owned!</span><br>
        <a href="https://www.imdb.com/" class="text-white" target="_blank">IMDB</a> |
        <a href="https://www.omdbapi.com/" class="text-white" target="_blank">OMDb API</a> |
        <a href="https://infinityfree.net/" class="text-white" target="_blank">InfinityFree Hosting</a> |
        <a href="https://kenwheeler.github.io/slick/" class="text-white" target="_blank">Slick Carousel</a>
      </div>
    </footer>

    <script src="profile.js"></script>
    <script src="theme.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"></script>
    <script type="text/javascript"
      src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

  </body>

</html>