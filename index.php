<?php
session_start();
include 'db_connection.php';
include 'check_remember_me.php';

$sql = "SELECT reviews.*, users.username FROM reviews INNER JOIN users ON reviews.user_id=users.user_id ORDER BY review_date DESC LIMIT 10";
$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReelRatings</title>
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

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg colorNavBar fs-4 sticky-top">
      <div class="container-fluid d-flex justify-content-between">
        <a href="/"><i class="fas fa-video fa-2x" style="color: white;"></i></a>

        <div id="navbarSupportedContent">
          <ul class="navbar-nav fs-4">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#topTenRated">Films</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#reviews">Reviews</a>
            </li>
          </ul>
        </div>

        <div class="navbar-end">
          <ul class="navbar-nav fs-4">
            <li class="nav-item">
              <a class="nav-link" href="profile.php"><i class="fas fa-user" style="color: white;"></i></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- about landing -->
    <section id="about" class="d-flex align-items-center justify-content-center">
      <p>Discover, review, and rate your favorite movies and TV shows, and connect with other film enthusiasts.</p>
    </section>

    <!-- Top ten films -->
    <section id="topTenRated" class="container mt-5">
      <h2 class="text-center mb-4 recentReviewsTitle">Top Rated Films</h2>
      <div class="row slick-carousel">
      </div>
    </section>

    <!-- Ten most recent reviews -->
    <section id="reviews" class="container mt-5">
      <h2 class="text-center mb-4 recentReviewsTitle">Recent Reviews</h2>
      <?php
      if ($result->num_rows > 0) {
        echo "<div class='container'>";
        while ($row = $result->fetch_assoc()) {
          echo "<div class='review-card'>";
          echo "<div class='card-body'>";
          echo "<h3>" . $row['username'] . " reviewed " . $row['film_name'] . "</h3>";
          echo "<h6 class='card-subtitle mb-2 text-muted'>Rating: " . $row['rating'] . "/5</h6>";
          echo "<p class='card-text card-content'>" . $row['content'] . "</p>";
          echo "<p class='card-text card-date'><small class='text-muted'>Date: " . $row['review_date'] . "</small></p>";
          echo "</div>";
          echo "</div>";
        }
        echo "</div>";
      } else {
        echo "No reviews yet.";
      }
      ?>
    </section>


    <!-- footer -->
    <footer class="footer mt-3 py-4 bg-dark">
      <div class="container text-center text-white">
        <span>Educational purposes only! No rights owned!</span><br>
        <a href="https://www.imdb.com/" class="text-white" target="_blank">IMDB</a> |
        <a href="https://www.omdbapi.com/" class="text-white" target="_blank">OMDb API</a> |
        <a href="https://infinityfree.net/" class="text-white" target="_blank">InfinityFree Hosting</a> |
        <a href="https://kenwheeler.github.io/slick/" class="text-white" target="_blank">Slick Carousel</a>
      </div>
    </footer>


    <script src="script.js"></script>
    <script src="theme.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"></script>
    <script type="text/javascript"
      src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
  </body>

</html>

<?php
$conn->close();
?>