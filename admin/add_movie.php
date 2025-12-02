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
    <title>MovieMate - Add Movie Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="../assets/img/favicon.svg" type="image/x-icon">
</head>

<body>

    <main class="d-flex">

        <!-- Navbar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Add Movie Form -->
        <section class="content-area flex-grow-1" style="margin-left: 260px; padding: 20px;">
            <section class="container my-3 px-4" style="width: 80rem;">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header text-center text-white py-4 admin-header">
                        <h1 class="fw-bold text-uppercase mb-0">
                            <i class="fa-solid fa-circle-plus me-2"></i> Add New Movie
                        </h1>
                    </div>
                    <div class="card-body p-4 p-md-5 bg-light">
                        <form action="controllers/add-process.php" method="post" class="needs-validation" novalidate>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="title" class="form-label fw-semibold">Movie Title:</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        placeholder="Enter movie title...">
                                    <div class="invalid-feedback">Please enter the movie title.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="language" class="form-label fw-semibold">Language:</label>
                                    <input type="text" class="form-control" id="language" name="language"
                                        placeholder="Enter movie language...">
                                    <div class="invalid-feedback">Please enter a language.</div>
                                </div>
                                <?php $today = date('Y-m-d'); ?>
                                <div class="col-md-6">
                                    <label for="release_date" class="form-label fw-semibold">Release Date:</label>
                                    <input type="date" class="form-control" id="release_date" name="release_date" min="<?= $today; ?>" required>
                                    <div class="invalid-feedback">Please select a release date.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="genre" class="form-label fw-semibold">Genre:</label>
                                    <input type="text" class="form-control" id="genre" name="genre"
                                        placeholder="Enter movie genre...">
                                    <div class="invalid-feedback">Please enter a genre.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="rating" class="form-label fw-semibold">Movie Rating:</label>
                                    <input type="number" step="0.1" min="0" max="10" class="form-control" id="rating" name="rating"
                                        placeholder="Enter rating between 0–10">
                                    <div class="invalid-feedback fw-semibold">Please, Provide a Rating between 0 and 10!!</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="poster_url" class="form-label fw-semibold">Poster URL:</label>
                                    <input type="url" class="form-control" id="poster_url" name="poster_url"
                                        placeholder="Enter movie poster URL...">
                                    <div class="invalid-feedback">Please enter a valid image URL.</div>
                                </div>
                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold">Description:</label>
                                    <textarea class="form-control" id="description" name="description"
                                        rows="4" placeholder="Enter movie description..."></textarea>
                                    <div class="invalid-feedback">Please enter a description.</div>
                                </div>
                            </div>
                            <div class="text-center mt-5 d-flex flex-column flex-md-row gap-3 justify-content-center">
                                <button type="submit" class="bg-success text-white rounded border-0 w-100 w-md-auto px-5 py-2 fw-semibold shadow-sm text-decoration-none">
                                    <i class="fa-solid fa-plus me-2"></i> Add Movie
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
            const ratingInput = document.getElementById("rating");
            const rating = parseFloat(ratingInput.value);

            if (rating < 0 || rating > 10) {
                ratingInput.classList.add("is-invalid");
                e.preventDefault(); // stop form submission
            } else {
                ratingInput.classList.remove("is-invalid");
            }
        });
    </script>

</body>

</html>