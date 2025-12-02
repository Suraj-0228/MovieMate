<?php
session_start();

// If session not set but cookie exists, restore session
if (!isset($_SESSION['adminname']) && isset($_COOKIE['adminname'])) {
    $_SESSION['adminname'] = $_COOKIE['adminname'];
}

// If neither session nor cookie, redirect to login
if (!isset($_SESSION['adminname'])) {
    echo "<script>
        alert('Please, Login to Access MovieMate Admin!!');
        window.location.href = '../login.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMate - Update Movies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="../assets/img/favicon.svg" type="image/x-icon">
</head>

<body>

    <main class="d-flex">

        <!-- Navbar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Admin Dashboard Section -->
        <section class="content-area flex-grow-1" style="margin-left: 260px; padding: 20px;">
            <section class="container my-3 px-4" style="width: 80rem;">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header text-center text-white py-4 admin-header">
                        <h1 class="fw-bold text-uppercase mb-0">
                            <i class="fa-solid fa-film me-2"></i> Update Movie Details
                        </h1>
                    </div>
                    <div class="card-body p-4 p-md-5 bg-light">
                        <?php
                        require 'includes/dbconnection.php';
                        if (isset($_GET['movie_id'])) {
                            $id = $_GET['movie_id'];
                            $sql_query = "SELECT * FROM movies_details WHERE movie_id = '$id'";
                            $result = mysqli_query($con, $sql_query);

                            if ($rows = mysqli_fetch_assoc($result)) {
                                $movie_title = $rows['title'];
                                $language = $rows['language'];
                                $release_date = $rows['release_date'];
                                $genre = $rows['genre'];
                                $rating = $rows['rating'];
                                $poster = $rows['poster_url'];
                                $description = $rows['description'];
                            }
                        }
                        ?>
                        <form action="controllers/update-movies.php" method="post" class="needs-validation" novalidate>
                            <input type="hidden" name="movie_id" value="<?= $id ?>">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="title1" class="form-label fw-semibold">Movie Title:</label>
                                    <input type="text" class="form-control" id="title1" name="title1"
                                        placeholder="Enter movie title..." value="<?= htmlspecialchars($movie_title) ?>">
                                    <div class="invalid-feedback">Please enter the movie title.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="language1" class="form-label fw-semibold">Language:</label>
                                    <input type="text" class="form-control" id="language1" name="language1"
                                        placeholder="Enter movie language..." value="<?= htmlspecialchars($language) ?>">
                                    <div class="invalid-feedback">Please enter a language.</div>
                                </div>
                                <?php $today = date('Y-m-d'); ?>

                                <div class="col-md-6">
                                    <label for="release_date1" class="form-label fw-semibold">Release Date:</label>
                                    <input type="date" class="form-control" id="release_date1" name="release_date1"
                                        value="<?= htmlspecialchars($release_date) ?>" min="<?= $today ?>">
                                    <div class="invalid-feedback">Please select a release date.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="genre1" class="form-label fw-semibold">Genre:</label>
                                    <input type="text" class="form-control" id="genre1" name="genre1"
                                        placeholder="Enter movie genre..." value="<?= htmlspecialchars($genre) ?>">
                                    <div class="invalid-feedback">Please enter a genre.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="rating1" class="form-label fw-semibold">Movie Rating:</label>
                                    <input type="number" class="form-control" id="rating1" name="rating1"
                                        step="0.1" min="0" max="10" value="<?= htmlspecialchars($rating) ?>">
                                    <div class="invalid-feedback fw-semibold">Please, Provide a Rating between 0 and 10!!</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="poster1" class="form-label fw-semibold">Poster URL:</label>
                                    <input type="url" class="form-control" id="poster1" name="poster1"
                                        placeholder="Enter poster image URL..." value="<?= htmlspecialchars($poster) ?>">
                                    <div class="invalid-feedback">Please enter a valid image URL.</div>
                                </div>
                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold">Description:</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"
                                        placeholder="Enter movie description..."><?= htmlspecialchars($description) ?></textarea>
                                    <div class="invalid-feedback">Please provide a description.</div>
                                </div>
                            </div>
                            <div class="text-center mt-5 d-flex flex-column flex-md-row gap-3 justify-content-center">
                                <button type="submit" class="bg-success px-5 py-2 text-white border border-0 rounded w-100 fw-semibold">
                                    <i class="fa-solid fa-upload me-2"></i> Update Movie
                                </button>
                                <a href="manage_movies.php" class="bg-danger text-white rounded w-100 w-md-auto px-5 py-2 fw-semibold shadow-sm text-decoration-none">
                                    <i class="fa-solid fa-xmark me-2"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </section>
    </main>

    <script>
        document.querySelector("form").addEventListener("submit", function(e) {

            const ratingFields = ["rating", "rating1"]; // both rating inputs
            let isValid = true;

            ratingFields.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    const value = parseFloat(input.value);

                    if (isNaN(value) || value < 0 || value > 10) {
                        input.classList.add("is-invalid");
                        isValid = false;
                    } else {
                        input.classList.remove("is-invalid");
                    }
                }
            });

            if (!isValid) {
                e.preventDefault(); // Stop form submission
            }
        });
    </script>

</body>

</html>