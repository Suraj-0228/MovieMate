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
    <title>MovieMate - Manage Movies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="shortcut icon" href="../assets/img/favicon.svg" type="image/x-icon">
</head>

<body>

    <main class="d-flex">

        <!-- Navbar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Manage Movies Section -->
        <section class="content-area flex-grow-1" style="margin-left: 260px; padding: 20px;">
            <section class="container my-3 px-4">
                <?php
                require 'includes/dbconnection.php';

                $limit = 4;
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                if ($page < 1) {
                    $page = 1;
                }
                $offset = ($page - 1) * $limit;
                $count_query = "SELECT COUNT(*) AS total FROM movies_details";
                $count_result = mysqli_query($con, $count_query);
                $total_records = 0;
                if ($count_result && mysqli_num_rows($count_result) > 0) {
                    $count_row = mysqli_fetch_assoc($count_result);
                    $total_records = (int)$count_row['total'];
                }
                $total_pages = $total_records > 0 ? ceil($total_records / $limit) : 1;
                $sql_query = "SELECT * FROM movies_details ORDER BY release_date DESC LIMIT $limit OFFSET $offset";
                $result = mysqli_query($con, $sql_query);
                ?>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                    <h1 class="fw-bold text-primary text-uppercase mb-3 mb-md-0">
                        <i class="fa-solid fa-video me-2"></i> Manage Movies
                    </h1>
                    <a href="add_movie.php" class="bg-success text-white rounded border-0 w-md-auto px-5 py-2 fw-semibold shadow-sm text-decoration-none">
                        <i class="fa-solid fa-plus me-2"></i> Add Movie </a>
                </div>
                <?php if ($result && mysqli_num_rows($result) > 0) : ?>
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

                        <?php while ($rows = mysqli_fetch_assoc($result)) :
                            $id = $rows['movie_id'];
                            $movie_title = $rows['title'];
                            $language = $rows['language'];
                            $release_date = $rows['release_date'];
                            $genre = $rows['genre'];
                            $rating = $rows['rating'];
                            $poster = $rows['poster_url'];
                            $description = $rows['description'];
                        ?>
                            <div class="col">
                                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                                    <div class="position-relative">
                                        <img src="<?= htmlspecialchars($poster) ?>"
                                            alt="<?= htmlspecialchars($movie_title) ?>"
                                            class="card-img-top"
                                            style="height: 250px; object-fit: cover;">
                                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2 rounded-pill px-3 py-1">
                                            <i class="fa-solid fa-star me-1"></i><?= $rating ?>
                                        </span>
                                    </div>
                                    <div class="card-body d-flex flex-column p-4">
                                        <h5 class="card-title fw-bold text-truncate" title="<?= htmlspecialchars($movie_title) ?>">
                                            <?= htmlspecialchars($movie_title) ?>
                                        </h5>
                                        <p class="mb-1 small text-muted">
                                            <i class="fa-solid fa-language me-1"></i><?= htmlspecialchars($language) ?>
                                            &nbsp; | &nbsp;
                                            <i class="fa-solid fa-calendar-days me-1"></i><?= htmlspecialchars($release_date) ?>
                                        </p>
                                        <p class="mb-2">
                                            <span class="badge bg-secondary px-3 py-2"><?= htmlspecialchars($genre) ?></span>
                                        </p>
                                        <p class="card-text text-muted small" style="max-height: 60px; overflow: hidden;">
                                            <?= htmlspecialchars($description) ?>
                                        </p>
                                        <div class="d-flex gap-3">
                                            <a href="update_movie.php?movie_id=<?= $id ?>" class="text-success text-decoration-none" data-bs-toggle="tooltip" title="Edit Movie">
                                                <i class="fa-solid fa-pen-to-square fa-lg"></i> Edit
                                            </a>
                                            <a href="controllers/delete-movie.php?movie_id=<?= $id ?>" class="text-danger text-decoration-none" onclick="return confirm('Are you sure you want to delete this movie?');"
                                                data-bs-toggle="tooltip" title="Delete Movie">
                                                <i class="fa-solid fa-trash fa-lg"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>

                    </div>

                <?php else : ?>
                    <div class="alert alert-light border text-center py-4 mt-4">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        <span class="text-muted">No movies available.</span>
                    </div>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if ($total_pages > 1) : ?>
                    <nav aria-label="Movies pagination">
                        <ul class="pagination justify-content-center mt-5">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $page > 1 ? '?page=' . ($page - 1) : '#' ?>"
                                    tabindex="-1">
                                    &laquo; Prev
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $page < $total_pages ? '?page=' . ($page + 1) : '#' ?>">
                                    Next &raquo;
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </section>
        </section>

    </main>

</body>

</html>